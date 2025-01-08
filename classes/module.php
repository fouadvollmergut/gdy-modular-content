<?php

	// Returns module object or false if the module doesn't exists
	function gdymc_module( $moduleID, $objectID = null, $objectType = false ) {

    $objectID = $objectID ? $objectID : gdymc_object_id();
		$objectType = $objectType ? $objectType : gdymc_object_type();

		$module = new GDYMC_MODULE( $moduleID, $objectID, $objectType );

		return $module->exists ? $module : false;

	}

	// Module class
	class GDYMC_MODULE {


		/************************* PROPERTIES *************************/

		public $exists = 0;
		public $id = null;
		public $object_id = null;
		public $object_type = null;
		public $type = null;
		public $path = null;
		public $file = null;
		public $functions = null;
		public $thumb = null;
		public $number = null;
		public $visibility = null;
		public $timer_status = null;
		public $timer_switch = null;
		public $classes = array();
		public $content = null;


		/************************* CONSTRUCTOR *************************/

		function __construct( $moduleID, $objectID, $objectType ) {

			$check = metadata_exists( $objectType, $objectID, '_gdymc_' . $moduleID . '_type' );

			if( !$check ):

				$this->exists = 0;

			else:

				// Set general properties

				$this->exists = 1;
				$this->id = $moduleID;
				$this->object_id = $objectID;
				$this->object_type = $objectType;
				$this->type = get_metadata( $this->object_type, $this->object_id, '_gdymc_' . $moduleID . '_type', true );
				$this->visibility = optionGet( 'visibility', $this->id );
				$this->timer_status = optionGet( 'visibility_timer', $this->id );
				$this->timer_switch = strtotime( optionGet( 'visibility_switch', $this->id ) );


				// Visibility switch

				if( $this->timer_status AND $this->timer_switch AND $this->timer_switch < date_i18n( 'U' ) ):

					if( $this->is_visible() ):

						$this->visibility = 0;

						optionSave( 'visibility', '0', $this->id, $this->object_id );
						optionSave( 'visibility_timer', '0', $this->id, $this->object_id );

					else:

						$this->visibility = 1;

						optionSave( 'visibility', '1', $this->id, $this->object_id );
						optionSave( 'visibility_timer', '0', $this->id, $this->object_id );

					endif;

				endif;


				// Paths

				$this->path = gdymc_module_path() . '/' . $this->type;
				$this->file = gdymc_module_path() . '/' . $this->type . '/index.php';
				$this->functions = gdymc_module_path() . '/' . $this->type . '/functions.php';
				$this->thumb = gdymc_module_path() . '/' . $this->type . '/thumb.svg';


				// Create module class

				$this->classes[] = 'gdymc_module';

				if( $this->is_visible() ) $this->classes[] = 'gdymc_visible';
				if( $this->is_invisible() ) $this->classes[] = 'gdymc_invisible';
				if( $this->is_timed() ) $this->classes[] = 'gdymc_timed';

				$this->classes[] = 'gdymc_module_' . $this->type;
				$this->classes[] = 'gdymc_module-' . $this->type; // deprecated since 0.8.1

				$this->classes = apply_filters( 'gdymc_module_class', $this->classes, $this );

				// Get content

				$this->content = $this->content_get();

			endif;

		}


		/************************* GENERAL FUNCTIONS *************************/

		// Show class array as string

		public function get_class() {

			return implode( ' ', $this->classes );

		}

		public function get_attributes() {

			// Attribute holder

			$attributes = array();


			// Assign default attributes

			$attributes[ 'id' ] = 'gdymc_module_' . $this->id;
			$attributes[ 'class' ] = $this->get_class();
			$attributes[ 'data-id' ] = $this->id;
			$attributes[ 'data-type' ] = $this->type;


			// Apply filter

			$attributes = apply_filters( 'gdymc_module_attributes', $attributes, $this );


			// Render attributes

			$output = array();

			foreach( $attributes as $key => $value ):

				$output[] = $key . '="' . $value . '"';

			endforeach;


			// Return

			return implode( ' ', $output );

		}


		/************************* VISIBILITY FUNCTIONS *************************/

		// Check if current module is visible

		public function is_visible() {

			return ( $this->visibility == 1 ) ? true : false;

		}


		// Check if current module is invisible

		public function is_invisible() {

			return ( $this->visibility == 0 ) ? true : false;

		}


		// Check if current module has a active visibility timer

		public function is_timed() {

			return ( $this->timer_status AND $this->timer_switch AND $this->timer_switch > date_i18n( 'U' ) ) ? true : false;

		}


		/************************* CONTENT FUNCTIONS *************************/

		// Get current module contents out of DB

		public function content_get() {

			$content = get_metadata( $this->object_type, $this->object_id, '_gdymc_' . $this->id . '_content', true );

			return ( $content == '[]' ) ? array() : $this->content_decode( $content );

		}

		// Creates a DB string out of an array of content IDs

		public function content_encode( $content ) {

			// Deprecated as of 0.9: return '[' . implode( ',', $content ) . ']';
			return json_encode( $content );

		}


		// Creates a array of content IDs out of a DB string 

		public function content_decode( $content ) {

			// Deprecated as of 0.9: return explode( ',', trim( trim( $content, '[' ), ']' ) );
			return json_decode( $content, true );

		}


		// Returns the content DB string

		public function content_string() {

			return $this->content_encode( $this->content );

		}


		//	Check if a content ID exists in this module

		public function content_exists( $contentID ) {

			return ( isset( $this->content[ $contentID ] ) AND !empty( $this->content[ $contentID ] ) ) ? true : false;

		}



		/************************* GENERAL ACTIONS *************************/

		public function delete() {

			global $wpdb;

			$dbname = ( $this->object_type == 'post' ) ? $wpdb->postmeta : $wpdb->termmeta;
			$dbkey = ( $this->object_type == 'post' ) ? 'post_id' : 'term_id';


			// Delete contents

			foreach( $this->content_get() as $contentID ):

				delete_metadata( $this->object_type, $this->object_id, "_gdymc_singlecontent_$contentID" );

			endforeach;


			// Delete module meta fields itself

			$wpdb->query( "DELETE FROM $dbname WHERE $dbkey = " . $this->object_id . " AND meta_key LIKE '_gdymc_" . $this->id . "_%'" );


			// Delete module from post/page

			$moduleArray = gdymc_module_array( $this->object_id, $this->object_type );

			if( ( $key = array_search( $this->id, $moduleArray ) ) !== false ) {

				unset( $moduleArray[ $key ] );

			}

			update_metadata( $this->object_type, $this->object_id, '_gdymc_modulelist', json_encode( array_values( $moduleArray ) ) );

			return true;

		}

	}

?>