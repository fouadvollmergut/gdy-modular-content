<?php
	
	
	/**************************** RETURN OBJECT INFORMATION ****************************/


	// Returns the object ID

	function gdymc_object_id() {

		return get_queried_object_id();

	}


	// Returns the object type

	function gdymc_object_type() {

		if( is_singular() ):

			return 'post';

		elseif( is_tax() OR is_tag() OR is_category() ):

			return 'term';

		else:

			return false;

		endif;

	}




	/**************************** HELPER AND UTILITY FUNCTIONS ****************************/


	// Sets a cookie

	function gdymc_set_cookie( $key, $value ) {

		setcookie( $key, $value, 0, COOKIEPATH, COOKIE_DOMAIN );
		$_COOKIE[ $key ] = $value;

	}


	// Removes a cookie

	function gdymc_remove_cookie( $key ) {

		setcookie( $key, null, -1, COOKIEPATH, COOKIE_DOMAIN );
		unset( $_COOKIE[ $key ] );

	}


	// Returns current URL

	function gdymc_current_url() {

		return ( isset( $_SERVER['HTTPS'] ) ? "https" : "http" ) . '://' . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ];

	}
	


	/**************************** RETURN DIRECTORY INFORMATION ****************************/


	// Returns the url to the modules folder

	function gdymc_module_url() {

		return get_site_url() . '/wp-content';

	}


	// Extract the module type

	function gdymc_module_type( $location ) {

		global $gdymc_module_types;

		foreach( $gdymc_module_types as $module_path ):
			if ( str_contains( $location, $module_path ) ):
				return substr( $module_path, strlen(WP_CONTENT_DIR) + 1 );
			endif;
		endforeach;

	}


	// DEPRECATED: Extract the module name

	function gdymc_module_name( $location ) {

		return gdymc_module_type( $location );

	}


	/**************************** RETURN SINGLE MODULE INFORMATION ****************************/


	// Checks if a module is placed on a specific object

	function gdymc_module_is_placed( $module, $objectID = null ) {

		$objectID = $objectID ? $objectID : gdymc_object_id();

		$modules = gdymc_get_placed_modules( $objectID );

		return array_key_exists( $module, $modules ) ? true : false;

	}


	// Check if a module is installed on the site

	function gdymc_module_is_installed( $module ) {

		$modules = gdymc_get_modules();

		return array_key_exists( $module, $modules ) ? true : false;

	}


	// Synonym for gdymc_module_is_installed()

	function gdymc_module_exists( $module ) {

		return gdymc_module_is_installed( $module );

	}


	/**************************** RETURN MODULE LIST INFORMATION ****************************/

	function gdymc_register_module_types() {

		global $gdymc_module_folders;
		global $gdymc_module_types;

		$gdymc_module_folders = apply_filters( 'gdymc_modules_folder', [ get_template_directory() . '/modules' ] );;

		foreach ($gdymc_module_folders as $module_folder):

			if (file_exists( $module_folder )):
				$modules = array_filter( glob( $module_folder . '/*' ), 'is_dir' );
				$modules = apply_filters( 'gdymc_modules', $modules );

				$gdymc_module_types = array_merge( $gdymc_module_types, $modules );
			endif;

		endforeach;

	}

	// Returns the number of placed modules / False if the modules folder doesn't exists

	function gdymc_has_modules() {

		global $gdymc_module_types;
		return count( $gdymc_module_types );

	}


	// Returns an array of installed modules

	function gdymc_get_modules() {

		if( !gdymc_has_modules() ):

			return false;

		else:

			// Module holder
			global $gdymc_module_types;
			global $gdymc_modules;


			// Setup the modules
			foreach( $gdymc_module_types as $module_path ):

				// Handler
				$module = new stdClass;

				// Extract folder name (type)
				$module_type = substr( $module_path, strlen(WP_CONTENT_DIR) + 1 );


				// Set info

				$module->folder = $module_type;

				$module->type = $module_type;

				$module->title = apply_filters( 'gdymc_module_title', strtolower( str_replace( '_', ' ', end( explode('/', $module_type) ) ) ), $module_type );

				$module->thumbPath = $module_path . '/thumb.svg';
				
				$module->thumbURL = gdymc_module_url() . '/' . $module_type . '/thumb.svg';


				// Push handler into modules

				$gdymc_modules[ $module_type ] = $module;


			endforeach;

			return $gdymc_modules;

		endif;

	}



	// Retreives a an array of placed modules from the WPDB
	
	function gdymc_module_array( $objectID = false, $objectType = false ) {
		

		// Get information

		$object_id = $objectID ? $objectID : gdymc_object_id();
		$object_type = $objectType ? $objectType : gdymc_object_type();


		// Fetch meta

		$moduleList = get_metadata( $object_type, $object_id, '_gdymc_modulelist', true );


		// Convert meta into array

		if( !is_array( $moduleArray = json_decode( $moduleList, true ) ) ) $moduleArray = array();

		
		// Return

		return $moduleArray;
		

	}










	// Returns the modules placed on a specific object

	function gdymc_get_placed_modules( $objectID = false, $objectType = false ) {


		// Fetch object information

		$object_id = $objectID ? $objectID : gdymc_object_id();
		$object_type = $objectType ? $objectType : gdymc_object_type();


		
		// Get objects modules
		
		$moduleArray = gdymc_module_array( $objectID, $objectType );
		


		// Holder array

		$moduleTypes = array();
		


		// Iterate through modules and push each type once into the holder

		foreach( $moduleArray as $key => $value ):
			
			$moduleType = get_metadata( $object_type, $object_id, '_gdymc_' . $value . '_type', true );

			if( isset( $moduleTypes[ $moduleType ] ) ):
				
				$moduleTypes[ $moduleType ] = $moduleTypes[ $moduleType ]+1;

			else:

				$moduleTypes[ $moduleType ] = 1;

			endif;
			
		endforeach;



		
		// Filter array

		$filteredTypes = gdymc_get_modules();


		if( $filteredTypes ) foreach( $filteredTypes as $key => $module ):

			if( !array_key_exists( $module->type, $moduleTypes ) ):

				unset( $filteredTypes[ $key ] );

			endif;

		endforeach;



		return $filteredTypes;


	}




	
	/**************************** VIEW MODE INFORMATION ****************************/
	
	// Checks if hard preview is active

	function gdymc_hardpreview() {

		return ( isset( $_COOKIE[ 'gdymc_hardpreview' ] ) AND $_COOKIE[ 'gdymc_hardpreview' ] == 1 ) ? true : false;
		
	}


	// Checks if soft preview is active

	function gdymc_softpreview() {
		
		return ( isset( $_COOKIE[ 'gdymc_softpreview' ] ) AND $_COOKIE[ 'gdymc_softpreview' ] == 1 ) ? true : false;
		
	}


	// Checks if any preview is active

	function gdymc_preview() {

		return ( gdymc_hardpreview() OR gdymc_softpreview() ) ? true : false;

	}


	// Checks if user is logged and not in customizer
	
	function gdymc_logged() {
		

		global $wp_customize;

		// Check if not in customizer and logged

		if ( !isset( $wp_customize ) AND current_user_can( 'edit_pages' ) ):

			return true;

		else:

			return false;

		endif;
		

	}


	

?>