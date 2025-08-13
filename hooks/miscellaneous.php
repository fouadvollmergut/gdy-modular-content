<?php



	/************************************* IMAGE GALLERY CAPTION ***********************************/

	add_action( 'gdymc_galleryimage_after', 'gdymc_galleryimage_caption', 10, 2 );

	function gdymc_galleryimage_caption( $imageID, $imageObject ) {

		if( !empty( $imageObject->post_excerpt ) ):

			echo '<div class="gdymc_gallery_item_caption">' . $imageObject->post_excerpt . '</div>';

		endif;
	
	}




	/************************************* MODULE: MODULE INTEGRITY ERRORS ***********************************/

	add_action( 'gdymc_error_module_missing', 'gdymc_error_module_missing', 10, 1 );

	function gdymc_error_module_missing( $module ) {

		if( gdymc_logged() ):

			$error = apply_filters( 'gdymc_error_module_missing_message', str_replace( '%s', $module->type, __( 'The module type "%s" seems not to exist.', 'gdy-modular-content' ) ), $module );

			optionError( $error );

		endif;
	
	}

	add_action( 'gdymc_error_module_incomplete', 'gdymc_error_module_incomplete', 10, 1 );

	function gdymc_error_module_incomplete( $module ) {

		if( gdymc_logged() ):

			$error = apply_filters( 'gdymc_error_module_incomplete_message', str_replace( '%s', $module->type, __( 'The module type "%s" is incomplete.', 'gdy-modular-content' ) ), $module );

			optionError( $error );

		endif;
	
	}


	add_action( 'gdymc_error_module_missing', 'gdymc_module_error_folder_actions', 10, 1 );
	add_action( 'gdymc_error_module_incomplete', 'gdymc_module_error_folder_actions', 10, 1 );

	function gdymc_module_error_folder_actions( $module ) {

		if( gdymc_logged() ):

			echo '<div class="gdymc_not_existing_module_actions gdymc_inside">';

			echo '<button class="gdymc_delete_moduletype">' . __( 'Delete this module type', 'gdy-modular-content' ) . '</button>';
			echo '<button class="gdymc_change_moduletype">' . __( 'Change this module type', 'gdy-modular-content' ) . '</button>';

			echo '</div>';

		endif;
	
	}



	/************************************* MODULE AREA ERRORS ***********************************/


	add_action( 'gdymc_error_area_nomodules', 'gdymc_error_area_nomodules', 10, 1 );

	function gdymc_error_area_nomodules( $module ) {

		if( gdymc_logged() ):

			$error = apply_filters( 'gdymc_errormessage_area_nomodules', __( 'There are no modules', 'gdy-modular-content' ) );

			optionError( $error );

		endif;
	
	}



	/************************************* ADMINBAR MODULE LIST: SETUP ERRORS ***********************************/

	add_action( 'gdymc_error_adminbar_nomodulefolder', 'gdymc_error_adminbar_nomodulefolder', 10 );

	function gdymc_error_adminbar_nomodulefolder() {

		$error = apply_filters( 'gdymc_errormessage_adminbar_nomodulefolder', __( 'There is no GDYMC modules folder.', 'gdy-modular-content' ) );

		echo '<div id="gdymc_nomodules"><span class="dashicons dashicons-info"></span>' . $error . '</div>';
	
	}


	add_action( 'gdymc_error_adminbar_nomodules', 'gdymc_error_adminbar_nomodules', 10 );

	function gdymc_error_adminbar_nomodules() {

		$error = apply_filters( 'gdymc_errormessage_adminbar_nomodules', __( 'There are no modules in your modules folder.', 'gdy-modular-content' ) );

		echo '<div id="gdymc_nomodules"><span class="dashicons dashicons-info"></span>' . $error . '</div>';
	
	}


	add_action( 'gdymc_error_adminbar_noarea', 'gdymc_error_adminbar_noarea', 10 );

	function gdymc_error_adminbar_noarea() {

		$error = apply_filters( 'gdymc_errormessage_adminbar_noarea', __( 'Create a module area with the areaCreate() function or the [gdymc_area] shortcode.', 'gdy-modular-content' ) );

		echo '<div id="gdymc_nomodules"><span class="dashicons dashicons-info"></span>' . $error . '</div>';
	
	}



	/************************************* AUTOMATIC MODULE AREA ***********************************/

	/*

	Removed since 0.9.2. Experiment for future integrations

	add_filter( 'the_content', 'gdymc_automatic_module_area', -100 );

	function gdymc_automatic_module_area( $content ) {

		return $content . '[gdymc_area]';

	}

	*/



	/************************************* MODULE AREA SHORTCODE ***********************************/

	add_shortcode( 'gdymc_area', 'gdymc_area_create_shortcode' );

	function gdymc_area_create_shortcode() {

		ob_start();
		
		areaCreate();

		$ob_content = ob_get_contents();

		ob_end_clean();

		return $ob_content;

	}



	/************************************* MAKE ATTACHMENT WIDTH AND HEIGHT ACCESSIBLE AS POST META ***********************************/

	add_action( 'gdymc_transfer_attachment_image_size', 'gdymc_transfer_attachment_image_sizes', 10, 2 );

	function gdymc_transfer_attachment_image_sizes() {


		$query = new WP_Query( array(

			'posts_per_page' => -1,
		    'post_type'      => 'attachment',
		    'post_status'    => 'any',
		    'meta_query' => array(

		    	'relation' => 'OR',
			    array(
					'key' => '_gdymc_image_width',
					'compare' => 'NOT EXISTS'
			    ),
			    array(
					'key' => '_gdymc_image_height',
					'compare' => 'NOT EXISTS'
			    ),

			)

		) );




		if( $query->have_posts() ): while( $query->have_posts() ): $query->the_post();

			$meta = wp_get_attachment_metadata();

			if( isset( $meta[ 'width' ] ) ) update_metadata( 'post', get_the_ID(), '_gdymc_image_width', (int) $meta[ 'width' ] );

			if( isset( $meta[ 'height' ] ) ) update_metadata( 'post', get_the_ID(), '_gdymc_image_height', (int) $meta[ 'height' ] );

		endwhile; endif;


		wp_reset_query(); 


	}



	/**************************** ADD MODULE LIST POSTMETA ****************************/
	
	/*

	Deprecated since version 0.9.0. There is no longer a existing meta necesary for gdymc_module_array()

	add_action( 'template_redirect', 'gdymc_after_setup_theme', 10 );

	function gdymc_after_setup_theme() {


		// Get object information

		$gdymc_object_id = gdymc_object_id();
		$gdymc_object_type = gdymc_object_type();
		


		// Add module list if it doesn't exist

		if( $gdymc_object_id AND !metadata_exists( $gdymc_object_type, $gdymc_object_id, '_gdymc_modulelist') ):

			add_metadata( $gdymc_object_type, $gdymc_object_id, '_gdymc_modulelist', '[]', true );

		endif;


	}

	*/


	/**************************** LOAD MODULE FUNCTIONS ****************************/

	/*

	Deprecated since version 0.8.6. These function are now always loaded.

	if( defined( 'DOING_AJAX' ) ):

		add_action( 'after_setup_theme', 'gdymc_load_module_functions', 10 );

	else:

		add_action( 'template_redirect', 'gdymc_load_module_functions', 10 );
		
	endif;
	*/

	add_action( 'init', 'gdymc_load_module_functions', 10 );

	function gdymc_load_module_functions() {

		global $gdymc_module_types;

		gdymc_register_module_types();

		if ( $gdymc_module_types ) {

			foreach( $gdymc_module_types as $moduleType ):

				do_action( 'gdymc_modulefunctions_before', $moduleType );

				$functionsPath = $moduleType . '/functions.php';

				error_log($functionsPath);
				if( file_exists( $functionsPath ) ) require_once( $functionsPath );

			do_action( 'gdymc_modulefunctions_after', $moduleType );

			endforeach;

		}

	}

?>