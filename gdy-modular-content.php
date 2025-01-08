<?php
/*
Plugin Name: GDY Modular Content
Description: Create and edit modular content from the frontend of your site.
Text Domain: gdy-modular-content
Domain Path: /languages/
License: GPLv2
Version: 0.9.92
*/



	/************************************* VERSION ***********************************/

	define( 'GDYMC_PLUGIN_VERSION', '0.9.92' );




	/************************************* PLUGIN BASE ***********************************/

	define( 'GDYMC_BASE_PATH', plugin_dir_path( __FILE__ ) );




	/*********************************** TRANSLATION *********************************/

	// Loads the textdomain

	add_action( 'init', 'gdymc_load_textdomain' );

	function gdymc_load_textdomain() {

		load_plugin_textdomain( 'gdy-modular-content', false, plugin_basename( __DIR__ ) . '/languages/' );

	}

	// Forces the plugin locale to the user language

	add_filter( 'plugin_locale', 'gdymc_force_locale', 10, 2 );

	function gdymc_force_locale( $locale, $domain ) {

		return ( $domain == 'gdy-modular-content' ) ? get_user_locale() : $locale;

	}
	


	



	/******************************** GLOBAL VARIABLES ********************************/
	
	$gdymc_module = false; // Holds the contents of an module
	$gdymc_object_contents = 0; // Holds the contents of an object (post or page)
	$gdymc_area = false; // For checking if the area function exists
	$gdymc_object_id = 0; // Save the page id in begin of area
	
	



	/*********************************** CLASSES ************************************/
	
	require_once( GDYMC_BASE_PATH . 'classes/module.php' );





	/*********************************** INCLUDES ************************************/
	
	require_once( GDYMC_BASE_PATH . 'includes/version.php' );
	require_once( GDYMC_BASE_PATH . 'includes/functions.php' );
	require_once( GDYMC_BASE_PATH . 'includes/options.php' );
	require_once( GDYMC_BASE_PATH . 'includes/elements.php' );
	require_once( GDYMC_BASE_PATH . 'includes/content.php' );
	require_once( GDYMC_BASE_PATH . 'includes/area.php' );
	require_once( GDYMC_BASE_PATH . 'includes/ajax.php' );
	//require_once( GDYMC_BASE_PATH . 'includes/editlock.php' ); // pending



	
	/************************************ HOOKS ***************************************/
	
	require_once( GDYMC_BASE_PATH . 'hooks/translation.php' );
	require_once( GDYMC_BASE_PATH . 'hooks/modulebar-buttons.php' );
	require_once( GDYMC_BASE_PATH . 'hooks/adminbar-buttons.php' );
	require_once( GDYMC_BASE_PATH . 'hooks/miscellaneous.php' );





	/************************************ PLUGIN ACTIVATION / DEACTIVATION / UNINSTALL ***************************************/

	register_activation_hook( __FILE__, 'gdymc_plugin_activation' );

	function gdymc_plugin_activation() {

		do_action( 'gdymc_plugin_activation' );
	    
	}


	register_deactivation_hook( __FILE__, 'gdymc_plugin_deactivation' );

	function gdymc_plugin_deactivation() {

		do_action( 'gdymc_plugin_deactivation' );
	    
	}


	register_uninstall_hook( __FILE__, 'gdymc_plugin_uninstall' );

	function gdymc_plugin_uninstall() {

		do_action( 'gdymc_plugin_uninstall' );
	    
	}
	





	/********************* PREVIEW COOKIES **********************/


	// Setup preview

	add_action( 'set_current_user', 'gdymc_preview_setup', 1000 );

	function gdymc_preview_setup() {
		

		global $current_user;

		if( is_user_logged_in() ):

			if( isset( $_GET[ 'gdymc_hardpreview' ] ) AND $_GET[ 'gdymc_hardpreview' ] == 0 ):


				gdymc_remove_cookie( 'gdymc_hardpreview' );
				gdymc_remove_cookie( 'gdymc_softpreview' );


			elseif( ( isset( $_COOKIE[ 'gdymc_hardpreview' ] ) AND $_COOKIE[ 'gdymc_hardpreview' ] == 1 AND !is_admin() AND $GLOBALS[ 'pagenow' ] != 'wp-login.php' ) OR ( isset( $_GET[ 'gdymc_hardpreview' ] ) AND $_GET[ 'gdymc_hardpreview' ] == 1 ) ):


				gdymc_set_cookie( 'gdymc_hardpreview', 1 );
				gdymc_remove_cookie( 'gdymc_softpreview' );

				$current_user = new WP_User();


			endif;

		endif;


	}



	// Remove cookies on logout

	add_action( 'wp_logout', 'gdymc_preview_reset' );

	function gdymc_preview_reset() {
	    
	    gdymc_remove_cookie( 'gdymc_hardpreview' );
	    gdymc_remove_cookie( 'gdymc_softpreview' );
	    
	}

	

	



	/**************************** REMOVE WORDPRESS ADMINBAR ****************************/
	
	add_filter( 'show_admin_bar', 'gdymc_disable_wordpress_adminbar' );

	function gdymc_disable_wordpress_adminbar() {

		return false;

	}
	
	
	


	/************************************** BODY CLASS ********************************/
	
	add_filter( 'body_class', 'gdymc_body_class' );

	function gdymc_body_class( $classes = '' ) {
		
		if( gdymc_logged() ):
			$classes[] = 'admin-bar'; 
			$classes[] = 'gdymc_bar';
			$classes[] = 'gdymc_logged';
		endif;

		if( !gdymc_preview() AND gdymc_logged() ) $classes[] = 'gdymc_edit';
		if( !gdymc_logged() AND !gdymc_hardpreview() ) $classes[] = 'gdymc_visitor';
		if( gdymc_hardpreview() ) $classes[] = 'gdymc_hardpreview gdymc_visitor';
		if( gdymc_softpreview() ) $classes[] = 'gdymc_softpreview';
		if( gdymc_hardpreview() ) $classes = array_filter( $classes, function( $v ) { return $v != "logged-in"; } );
		
		return $classes;
		
	}
	




	/************************************** SHUTDOWN ********************************/

	add_action( 'shutdown', 'gdymc_shutdown' );

	function gdymc_shutdown() {
		
		global $gdymc_object_contents;

		if( is_array( $gdymc_object_contents ) ) update_metadata( gdymc_object_type(), gdymc_object_id(), '_gdymc_object_contents', json_encode( $gdymc_object_contents ) );
	
	}




	/*************************** REGISTER STYLES & SCRIPTS ***************************/
	
	add_action( 'wp_enqueue_scripts', function() {
		

		// Admin styles and scripts

		if( gdymc_logged() ): 
			

			// Styles

			wp_enqueue_style( 'gdymc_style', plugins_url( '/_styles/style.css', __FILE__ ), array( 'dashicons' ), GDYMC_PLUGIN_VERSION );
			


			// Scripts

			// jQuery, jQueryUI and jCrop comes via WordPress

			wp_enqueue_script( 'log4javascript', plugins_url('/scripts/log4javascript.js', __FILE__ ), array( 'jquery' ) );
			wp_enqueue_script( 'rangy_core', plugins_url('/scripts/rangy_core.js', __FILE__ ), array( 'log4javascript' ) );
			wp_enqueue_script( 'rangy_selectionsaverestore', plugins_url('/scripts/rangy_selectionsaverestore.js', __FILE__ ), array( 'log4javascript', 'rangy_core' ) );
			wp_enqueue_script( 'rangy_classapplier', plugins_url('/scripts/rangy_classapplier.js', __FILE__ ), array( 'log4javascript', 'rangy_core') );
			wp_enqueue_script( 'kinetic', plugins_url('/scripts/kinetic.js', __FILE__ ), array( 'jquery' ) );
			wp_enqueue_script( 'mousetrap', plugins_url('/scripts/mousetrap.js', __FILE__ ), array( 'jquery' ) );
			wp_enqueue_script( 'dropzone', plugins_url('/scripts/dropzone.js', __FILE__ ), array( 'jquery' ) );
			wp_enqueue_script( 'fastlivefilter', plugins_url('/scripts/filter.js', __FILE__ ), array( 'jquery' ) );
			wp_enqueue_script( 'gdymc_core', plugins_url('/scripts/gdymc_core.js', __FILE__ ), array( 'jquery' ), GDYMC_PLUGIN_VERSION );
			wp_enqueue_script( 'gdymc_functions', plugins_url('/scripts/gdymc_functions.js', __FILE__ ), array( 'jquery', 'jquery-ui-core', 'jquery-ui-sortable', 'utils', 'gdymc_core', 'jcrop', 'kinetic', 'mousetrap', 'dropzone', 'fastlivefilter', 'rangy_core', 'rangy_selectionsaverestore', 'rangy_classapplier' ), GDYMC_PLUGIN_VERSION );
			

			// Javascript data

			wp_localize_script( 'gdymc_core', 'gdymc_dynamic_data', array(

				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'max_upload' => wp_max_upload_size() / 1024 / 1024,
				'allowed_filetypes' => implode( ',', get_allowed_mime_types() ),
				'object_id' => gdymc_object_id(),
				'object_type' => gdymc_object_type(),
				'role_uploads' => current_user_can( 'upload_files', gdymc_object_id() ),
				'cookie_path' => COOKIEPATH,
				'cookie_domain' => COOKIE_DOMAIN,
				'current_user' => get_current_user_id()

			) );



			// Javascript data language strings

			wp_localize_script( 'gdymc_core', 'gdymc_lang', apply_filters( 'gdymc_lang', '' ) );
			

		endif;



		// Hard preview styles and scripts

		if( gdymc_hardpreview() ): 
			

			// Styles

			wp_enqueue_style( 'gdymc_hardpreview', plugins_url( '/_styles/hardpreview.css', __FILE__ ), array(), GDYMC_PLUGIN_VERSION );



			// Scripts

			wp_enqueue_script( 'gdymc_hardpreview', plugins_url( '/scripts/gdymc_hardpreview.js', __FILE__ ), array( 'utils' ), GDYMC_PLUGIN_VERSION );


			// Javascript data

			wp_localize_script( 'gdymc_hardpreview', 'gdymc_dynamic_data', array(

				'cookie_path' => COOKIEPATH,
				'cookie_domain' => COOKIE_DOMAIN,

			) );

		endif;



	} );
	
	
	
	
?>