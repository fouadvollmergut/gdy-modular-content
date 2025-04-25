<?php
	
	
	/**************************** VERSION CHECK ****************************/ 

	function gdymc_version_smaller_than( $version ) {


		$gdymc_current_version = get_option( 'gdymc_plugin_version' );


		if( version_compare( $gdymc_current_version, $version ) == -1 ):

			return true;

		else:

			return false;

		endif;


	}



	/**************************** UPGRADE HOOK AND VERSIONING ****************************/ 

	add_action( 'init', 'gdymc_upgrade_check' );

	function gdymc_upgrade_check() {


		// Get old version

		$old_plugin_version = get_option( 'gdymc_plugin_version' );


		// Upgrade hook

	    if( gdymc_version_smaller_than( GDYMC_PLUGIN_VERSION ) ):

			do_action( 'gdymc_plugin_upgrade', $old_plugin_version, GDYMC_PLUGIN_VERSION );

		endif;


		// Set plugin version

		update_option( 'gdymc_plugin_version', GDYMC_PLUGIN_VERSION );


		// Set installation date 

		if( !get_option( 'gdymc_installation_date' ) ) add_option( 'gdymc_installation_date', time() );


	}

	




	/**************************** UPGRADE TO 0.6.4 OR HIGHER ****************************/ 
	
	if( gdymc_version_smaller_than( '0.6.4' ) ):

		global $wpdb;

		$wpdb->query( "UPDATE $wpdb->postmeta SET meta_key = REPLACE(meta_key, 'gdy_modularContent_', 'gdymc_')  WHERE meta_key LIKE 'gdy_modularContent_%'" );

		$modules = $wpdb->get_results( "SELECT post_id, meta_key, meta_value FROM $wpdb->postmeta WHERE meta_key LIKE 'gdymc_list'" );

		foreach( $modules as $module ):

			$moduleIDs = explode( ',', str_replace( array( '[', ']' ), array( '', '' ), $module->meta_value ) );

			foreach( $moduleIDs as $moduleID ):

				update_metadata( 'post', $module->post_id, '_gdymc_' . $moduleID . '_option_visibility', '1' );

			endforeach;

		endforeach;

	endif;



	/**************************** UPGRADE TO 0.6.8 OR HIGHER ****************************/ 
	
	if( gdymc_version_smaller_than( '0.6.8' ) ):

		global $wpdb;

		// Adjusting database names

		$wpdb->query( "UPDATE $wpdb->postmeta SET meta_key = CONCAT('_', meta_key) WHERE meta_key LIKE 'gdymc_%'" );
		$wpdb->query( "UPDATE $wpdb->postmeta SET meta_key = REPLACE(meta_key, '_gdymc_Content_', '_gdymc_singlecontent_')  WHERE meta_key LIKE '_gdymc_Content_%'" );
		$wpdb->query( "UPDATE $wpdb->postmeta SET meta_key = REPLACE(meta_key, '_gdymc_object_content', '_gdymc_object_contents')  WHERE meta_key LIKE '_gdymc_object_content'" );
		$wpdb->query( "UPDATE $wpdb->postmeta SET meta_key = REPLACE(meta_key, '_gdymc_list', '_gdymc_modulelist')  WHERE meta_key LIKE '_gdymc_list'" );

	endif;




	/**************************** UPGRADE TO 0.9.0 OR HIGHER ****************************/ 

	if( gdymc_version_smaller_than( '0.9.0' ) ):

		global $wpdb;

		// Change module IDs to JSON

		$modules = $wpdb->get_results( "SELECT post_id, meta_key, meta_value FROM $wpdb->postmeta WHERE meta_key LIKE '_gdymc_modulelist'" );

		foreach( $modules as $module ):

			$moduleIDs = explode( ',', str_replace( array( '[', ']' ), array( '', '' ), $module->meta_value ) );

			$encoded = json_encode( $moduleIDs );

			update_metadata( 'post', $module->post_id, $module->meta_key, $encoded );

		endforeach;

		// Change content IDs to JSON

		$contents = $wpdb->get_results( "SELECT post_id, meta_key, meta_value FROM $wpdb->postmeta WHERE meta_key LIKE '_gdymc_%_content'" );

		foreach( $contents as $content ):

			$contentIDs = explode( ',', str_replace( array( '[', ']' ), array( '', '' ), $content->meta_value ) );

			$encoded = json_encode( $contentIDs );

			update_metadata( 'post', $content->post_id, $content->meta_key, $encoded );

		endforeach;

	endif;


	/**************************** UPGRADE TO 0.9.7 OR HIGHER ****************************/ 

	if( gdymc_version_smaller_than( '0.9.7' ) ):

		global $wpdb;

		// Change global contents to JSON (post)

		$contents = $wpdb->get_results( "SELECT post_id, meta_key, meta_value FROM $wpdb->postmeta WHERE meta_key = '_gdymc_object_contents'" );

		foreach( $contents as $content ):

			$contentIDs = explode( ',', str_replace( array( '[', ']' ), array( '', '' ), $content->meta_value ) );

			$encoded = json_encode( $contentIDs );

			update_metadata( 'post', $content->post_id, $content->meta_key, $encoded );

		endforeach;

		// Change global contents to JSON (term)

		$contents = $wpdb->get_results( "SELECT term_id, meta_key, meta_value FROM $wpdb->termmeta WHERE meta_key = '_gdymc_object_contents'" );

		foreach( $contents as $content ):

			$contentIDs = explode( ',', str_replace( array( '[', ']' ), array( '', '' ), $content->meta_value ) );

			$encoded = json_encode( $contentIDs );

			update_metadata( 'term', $content->term_id, $content->meta_key, $encoded );

		endforeach;


	endif;

	/**************************** UPGRADE TO 0.9.95 OR HIGHER ****************************/

	if( gdymc_version_smaller_than( '0.9.95' ) ):

		global $wpdb;

		$contents = $wpdb->get_results( "SELECT post_id, meta_key, meta_value FROM $wpdb->postmeta WHERE meta_key LIKE '_gdymc_%_type'" );

		foreach( $contents as $content ):

			if (!str_contains($content->meta_value, '/')) {

				$updated_meta_value = 'themes/' . end(explode('/', get_template_directory())) . '/modules/' . $content->meta_value;

				update_metadata( 'post', $content->post_id, $content->meta_key, $updated_meta_value );
			}

		endforeach;
	endif;




?>