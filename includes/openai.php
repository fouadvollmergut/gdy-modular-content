<?php


	/******************************* OPENAI TRANSLATION AJAX HANDLER *******************************/

	add_action( 'wp_ajax_gdymc_action_translate', 'gdymc_action_translate' );

	function gdymc_action_translate() {

		if ( !gdymc_logged() || !isset( $_POST['content_id'] ) || !isset( $_POST['object_id'] ) || !isset( $_POST['object_type'] ) || !isset( $_POST['target_language'] ) ) {

			wp_die( wp_json_encode( array( 'error' => __( 'Invalid request.', 'gdy-modular-content' ) ) ) );

		}

		$content_id    = sanitize_text_field( wp_unslash( $_POST['content_id'] ) );
		$object_id     = intval( $_POST['object_id'] );
		$object_type   = sanitize_text_field( wp_unslash( $_POST['object_type'] ) );
		$content_type  = isset( $_POST['content_type'] ) ? sanitize_text_field( wp_unslash( $_POST['content_type'] ) ) : 'text';
		$target_lang   = sanitize_text_field( wp_unslash( $_POST['target_language'] ) );

		$api_key = get_option( 'gdymc_openai_api_key', '' );

		if ( empty( $api_key ) ) {

			wp_die( wp_json_encode( array( 'error' => __( 'No OpenAI API key configured. Please add your API key in the GDY Modular Content settings.', 'gdy-modular-content' ) ) ) );

		}

		$content = get_metadata( $object_type, $object_id, '_gdymc_singlecontent_' . $content_id, true );

		if ( $content === '' || $content === false ) {

			wp_die( wp_json_encode( array( 'error' => __( 'Content is empty.', 'gdy-modular-content' ) ) ) );

		}

		$translation = gdymc_openai_translate( $content, $content_type, $target_lang, $api_key );

		if ( is_wp_error( $translation ) ) {

			wp_die( wp_json_encode( array( 'error' => $translation->get_error_message() ) ) );

		}

		update_metadata( $object_type, $object_id, '_gdymc_singlecontent_' . $content_id . '_translation', $translation );

		wp_die( wp_json_encode( array( 'success' => true, 'translation' => $translation ) ) );

	}


	/******************************* OPENAI TRANSLATION HELPER *******************************/

	function gdymc_openai_translate( $content, $content_type, $target_language, $api_key ) {

		switch ( $content_type ) {

			case 'table':
				$prompt = sprintf(
					/* translators: 1: target language, 2: content to translate */
					__( 'Translate the cell values in the following JSON table (array of arrays) to %1$s. Return only the translated JSON array of arrays, preserving the exact same structure. Content: %2$s', 'gdy-modular-content' ),
					$target_language,
					$content
				);
				break;

			case 'buttongroup':
				$prompt = sprintf(
					/* translators: 1: target language, 2: content to translate */
					__( 'Translate the "text" field values in the following JSON button group array to %1$s. Return only the translated JSON array, preserving all other fields (url, target, type) completely unchanged. Content: %2$s', 'gdy-modular-content' ),
					$target_language,
					$content
				);
				break;

			default:
				$prompt = sprintf(
					/* translators: 1: target language, 2: content to translate */
					__( 'Translate the following HTML content to %1$s. Return only the translated HTML, keeping all HTML tags and attributes intact, without any additional text or explanation. Content: %2$s', 'gdy-modular-content' ),
					$target_language,
					$content
				);
				break;

		}

		$response = wp_remote_post( 'https://api.openai.com/v1/chat/completions', array(
			'timeout' => 60,
			'headers' => array(
				'Authorization' => 'Bearer ' . $api_key,
				'Content-Type'  => 'application/json',
			),
			'body' => wp_json_encode( array(
				'model'    => 'gpt-3.5-turbo',
				'messages' => array(
					array(
						'role'    => 'system',
						'content' => 'You are a professional translator. Translate the content as requested and return only the translated content without any additional explanation.',
					),
					array(
						'role'    => 'user',
						'content' => $prompt,
					),
				),
			) ),
		) );

		if ( is_wp_error( $response ) ) {

			return $response;

		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( isset( $body['error'] ) ) {

			return new WP_Error( 'openai_error', $body['error']['message'] );

		}

		if ( !isset( $body['choices'][0]['message']['content'] ) ) {

			return new WP_Error( 'openai_error', __( 'Invalid response from OpenAI API.', 'gdy-modular-content' ) );

		}

		return $body['choices'][0]['message']['content'];

	}


	/******************************* TRANSLATION JS *******************************/

	add_action( 'wp_enqueue_scripts', 'gdymc_openai_enqueue_scripts' );

	function gdymc_openai_enqueue_scripts() {

		if ( !gdymc_logged() ) return;

		$inline_js = "
jQuery( document ).ready( function( $ ) {

	// Translate language click handler
	$( document ).on( 'click', '.gdymc_translate_language', function( e ) {

		e.preventDefault();

		var targetLang = $( this ).data( 'lang' );

		// Close dropdown
		$( '#gdymc_translatemenu' ).closest( '.gdymc_dropdown_trigger' ).removeClass( 'gdymc_active' );

		// Collect all translatable content IDs on the page
		var items = [];

		// Text content
		$( '.gdymc_text[data-id]' ).each( function() {
			items.push( { id: $( this ).data( 'id' ), type: 'text' } );
		} );

		// Button groups
		$( '.gdymc_button-group[data-id]' ).each( function() {
			items.push( { id: $( this ).data( 'id' ), type: 'buttongroup' } );
		} );

		// Tables
		$( '.gdymc_table[data-id]' ).each( function() {
			items.push( { id: $( this ).data( 'id' ), type: 'table' } );
		} );

		if ( items.length === 0 ) {
			gdymc.functions.error( { text: gdymc.lang( 'translate-nocontents' ) } );
			return;
		}

		var total = items.length;
		var done = 0;
		var errors = 0;

		$( 'body' ).addClass( 'gdymc_progress' );

		// Translate items one by one
		function translateNext( index ) {

			if ( index >= items.length ) {
				$( 'body' ).removeClass( 'gdymc_progress' );
				if ( errors > 0 ) {
					gdymc.functions.error( { text: gdymc.lang( 'translate-error' ) } );
				} else {
					gdymc.functions.error( { title: gdymc.lang( 'translate-title' ), text: gdymc.lang( 'translate-success' ), background: '#46b450' } );
				}
				return;
			}

			var item = items[ index ];

			$.ajax( {
				url: gdymc_dynamic_data.ajax_url,
				type: 'POST',
				data: {
					action: 'gdymc_action_translate',
					content_id: item.id,
					content_type: item.type,
					object_id: gdymc_dynamic_data.object_id,
					object_type: gdymc_dynamic_data.object_type,
					target_language: targetLang,
				},
				success: function( response ) {
					try {
						var parsed = JSON.parse( response );
						if ( parsed.error ) errors++;
					} catch(e) {
						errors++;
					}
					done++;
					translateNext( index + 1 );
				},
				error: function() {
					errors++;
					done++;
					translateNext( index + 1 );
				}
			} );

		}

		translateNext( 0 );

	} );

} );
";

		wp_add_inline_script( 'gdymc_core', $inline_js );

	}

?>

