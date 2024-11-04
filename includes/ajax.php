<?php


	// Delete Module
	
	add_action( 'wp_ajax_gdymc_action_deletemodule', 'gdymc_action_deletemodule' );
	
	function gdymc_action_deletemodule( $moduleIDs = null, $objectID = null, $objectType = null ) {
		
		if( gdymc_logged() ):

			// Get info

			$moduleIDs = isset( $_POST[ 'id' ] ) ? $_POST[ 'id' ] : $moduleIDs;
			$objectID = isset( $_POST[ 'object_id' ] ) ? $_POST[ 'object_id' ] : $objectID;
			$objectType = isset( $_POST[ 'object_type' ] ) ? $_POST[ 'object_type' ] : $objectType;
			
			// Delete modules
			
			$moduleIDs = explode( ',', $moduleIDs );

			foreach( $moduleIDs as $moduleID ):

				$module = gdymc_module( $moduleID, $objectID, $objectType );

				if( $module ) $module->delete();

			endforeach;

		endif;
		
	}



	// DELETES ALL MODULES OF AN TYPE
	
	add_action( 'wp_ajax_gdymc_action_deletemoduletype', 'gdymc_action_deletemoduletype' );
	
	function gdymc_action_deletemoduletype( $moduleType = '' ) {
		
		if( gdymc_logged() ):
		

			$moduleType = isset( $_POST[ 'type' ] ) ? $_POST[ 'type' ] : $moduleType;
			
			global $wpdb;


			$modules = $wpdb->get_results("SELECT post_id, meta_key FROM $wpdb->postmeta WHERE meta_key LIKE '_gdymc_%_type' AND meta_value='$moduleType'");
			
			foreach( $modules as $module ):

				$moduleKey = $module->meta_key;
				$moduleID = str_replace( '_gdymc_', '', $moduleKey );
				$moduleID = str_replace( '_type', '', $moduleID );

				$module = gdymc_module( $moduleID, $module->post_id, 'post' );

				if( $module ) $module->delete();

			endforeach;


			$modules = $wpdb->get_results("SELECT term_id, meta_key FROM $wpdb->termmeta WHERE meta_key LIKE '_gdymc_%_type' AND meta_value='$moduleType'");
			
			foreach( $modules as $module ):

				$moduleKey = $module->meta_key;
				$moduleID = str_replace( '_gdymc_', '', $moduleKey );
				$moduleID = str_replace( '_type', '', $moduleID );

				$module = gdymc_module( $moduleID, $module->term_id, 'term' );

				if( $module ) $module->delete();

			endforeach;


		endif;
		
	}



	// Change Module Type
	
	add_action( 'wp_ajax_gdymc_action_changemoduletype', 'gdymc_action_changemoduletype' );
	
	function gdymc_action_changemoduletype( $oldModule = '', $newModule = '', $objectID = '' ) {
		
		if( gdymc_logged() ):

			// Get IDs

			$oldModule = isset( $_POST[ 'oldModule' ] ) ? $_POST[ 'oldModule' ] : $oldModule;
			$newModule = isset( $_POST[ 'newModule' ] ) ? $_POST[ 'newModule' ] : $newModule;
			$objectID = isset( $_POST[ 'object' ] ) ? $_POST[ 'object' ] : $objectID;
			
			global $wpdb;
			$modules = $wpdb->get_results( "UPDATE $wpdb->postmeta SET meta_value='$newModule' WHERE meta_key LIKE '_gdymc_%_type' AND meta_value='$oldModule'" );

		endif;
		
	}


	// Change single Module Type
	
	add_action( 'wp_ajax_gdymc_action_changesinglemoduletype', 'gdymc_action_changesinglemoduletype' );
	
	function gdymc_action_changesinglemoduletype( $moduleid = null, $moduletype = null, $objectID = null, $objectType = null ) {
		
		if( gdymc_logged() ):

			// Get IDs

			$moduleid = isset( $_POST[ 'moduleid' ] ) ? $_POST[ 'moduleid' ] : $moduleid;
			$moduletype = isset( $_POST[ 'moduletype' ] ) ? $_POST[ 'moduletype' ] : $moduletype;
			$objectID = isset( $_POST[ 'object_id' ] ) ? $_POST[ 'object_id' ] : $objectID;
			$objectType = isset( $_POST[ 'object_type' ] ) ? $_POST[ 'object_type' ] : $objectType;
			
			update_metadata( $objectType, $objectID, '_gdymc_'.$moduleid.'_type', $moduletype );

		endif;
		
	}
	
	
	
	
	// Add Module
	
	add_action( 'wp_ajax_gdymc_action_addmodule', 'gdymc_action_addmodule' );
	
	function gdymc_action_addmodule() {

		if( gdymc_logged() ):
		
			// Get Information
			$moduleType = $_POST[ 'type' ];
			$objectID = $_POST[ 'object_id' ];
			$objectType = $_POST[ 'object_type' ];
			
			// Create a unique module id

			$insertID = uniqid();
			

			// Insert module into postmeta

			update_metadata( $objectType, $objectID, '_gdymc_' . $insertID . '_type', $moduleType );
			update_metadata( $objectType, $objectID, '_gdymc_' . $insertID . '_content', '[]' );
			update_metadata( $objectType, $objectID, '_gdymc_' . $insertID . '_option_visibility', apply_filters( 'gdymc_default_module_visibility', 1 ) );
			

			// Get module List

			$moduleArray = gdymc_module_array( $objectID, $objectType );
			

			// Push module into list

			array_push( $moduleArray, $insertID );
			

			// Save module list

			update_metadata( $objectType, $objectID, '_gdymc_modulelist', json_encode( array_values( $moduleArray ) ) );


			// Return module ID

			die( $insertID );

		endif;
		
	}
	
	
	
	
	// Saving contents
	
	add_action( 'wp_ajax_gdymc_action_save', 'gdymc_action_save' );
	
	function gdymc_action_save() {

		if( isset( $_POST['contents'] ) && isset( $_POST['object_id'] ) && isset( $_POST['object_type'] ) && isset( $_POST['modules'] ) && isset( $_POST['options'] ) && gdymc_logged() ) {

			$object_id = $_POST['object_id'];
			$object_type = $_POST['object_type'];
			$contents = json_decode( stripslashes( $_POST['contents'] ) );
			$options = json_decode( stripslashes( $_POST['options'] ) );
			$modules = stripslashes( $_POST['modules'] );

			update_metadata( $object_type, $object_id, '_gdymc_modulelist', $modules);


			
			foreach( $contents as $key => $value ):
				
				// Prevent the wp_unslash function from destroying the contents
				$value[1] = str_replace( "\\", "\\\\", $value[1] );

				update_metadata( $object_type, $object_id, '_gdymc_singlecontent_' . $value[0], $value[1] );
			
			endforeach;
			
			foreach( $options as $key => $value ):
			
				optionSave( $value[0], $value[1], $value[2], $object_id, $object_type );
			
			endforeach;

			die();
		
		}
		
	}
	


	// Link window

	add_action( 'wp_ajax_gdymc_action_insertlink', 'gdymc_action_insertlink' );

	function gdymc_action_insertlink() {

		if( gdymc_logged() ):


			echo '<div class="gdymc_overlay_head"><div class="gdymc_overlay_head_inner">';

				echo '<button class="gdymc_overlay_close gdymc_overlay_close_trigger"></button>';
				echo '<div class="gdymc_overlay_title">' . __('Insert Link', 'gdy-modular-content') . '</div>';
				
				echo '<div class="gdymc_tabs_navigation">';

					echo '<button class="gdymc_tabs_button gdymc_active" data-tab="link">'.__('Link', 'gdy-modular-content').'</button>';
					echo '<button class="gdymc_tabs_button" data-tab="file">'.__('File', 'gdy-modular-content').'</button>';
					echo '<button class="gdymc_tabs_button" data-tab="page">'.__('Page', 'gdy-modular-content').'</button>';
					echo '<button class="gdymc_tabs_button" data-tab="post">'.__('Post', 'gdy-modular-content').'</button>';
					echo '<button class="gdymc_tabs_button" data-tab="category">'.__('Category', 'gdy-modular-content').'</button>';
					
					do_action( 'gdymc_linktabbuttons' );
				
				echo '</div>';
				
			
			echo '</div></div>';
			
			
			
			echo '<div class="gdymc_overlay_foot">';
			echo '<div class="gdymc_overlay_foot_inner gdymc_fix">';
			
			echo '<div class="gdymc_left">';
				
				echo '<button id="gdymc_insertlink_button" class="gdymc_button">' . __( 'Insert Link', 'gdy-modular-content' ) . '</button>';
				
				echo '<input type="checkbox" id="gdymc_insertlink_target" /> <label for="gdymc_insertlink_target">' . __( 'Open in new tab or window', 'gdy-modular-content' ) . '</label>';
			
			echo '</div>';
			
			
			echo '</div>';
			echo '</div>';
			
			
			
			echo '<div class="gdymc_overlay_content">';
				echo '<div class="gdymc_overlay_content_inner">';

					echo '<div id="gdymc_tabs_content_linkadress" class="gdymc_tabs_content gdymc_active" data-tab="link">';
						
						echo '<input id="gdymc_insertlink_input" type="text" placeholder="' . __('Link adress', 'gdy-modular-content') . '">';

						echo '<input id="gdymc_insertlink_classes" type="text" placeholder="' . __('Additional classes', 'gdy-modular-content') . '">';

					echo '</div><!-- gdymc_tabs_content -->';
					
					echo '<div id="gdymc_tabs_content_files" class="gdymc_tabs_content" data-tab="file">';
						
						gdymc_action_filelist();
					
					echo '</div><!-- gdymc_tabs_content -->';

					echo '<div id="gdymc_tabs_content_pages" class="gdymc_tabs_content" data-tab="page">';
						
						gdymc_action_pagelist();
					
					echo '</div><!-- gdymc_tabs_content -->';

					echo '<div id="gdymc_tabs_content_posts" class="gdymc_tabs_content" data-tab="post">';
						
						gdymc_action_postlist();
					
					echo '</div><!-- gdymc_tabs_content -->';

					echo '<div id="gdymc_tabs_content_categories" class="gdymc_tabs_content" data-tab="category">';
						
						gdymc_action_categorylist();
					
					echo '</div><!-- gdymc_tabs_content -->';

					
				echo '</div><!-- .gdymc_overlayInner -->';
				echo '</div><!-- .gdymc_overlayContent -->';
			
			
			die();

		endif;

	}



	// Batch window

	// add_action( 'wp_ajax_gdymc_action_modulebatch', 'gdymc_action_modulebatch' );

	// function gdymc_action_modulebatch() {

	// 	if( gdymc_logged() ):


	// 		echo '<div class="gdymc_overlay_head"><div class="gdymc_overlay_head_inner">';

	// 			echo '<button class="gdymc_overlay_close gdymc_overlay_close_trigger"></button>';
	// 			echo '<div class="gdymc_overlay_title">' . __('Batch editing', 'gdy-modular-content') . '</div>';
				
	// 			echo '<div class="gdymc_tabs_navigation">';

	// 				echo '<div class="gdymc_tabs_button gdymc_active" data-tab="selection">' . __('Selection', 'gdy-modular-content') . '</div>';
	// 				echo '<div class="gdymc_tabs_button" data-tab="actions">' . __('Actions', 'gdy-modular-content') . '</div>';
					
	// 				do_action( 'gdymc_batchtabbuttons' );
				
	// 			echo '</div>';
				
			
	// 		echo '</div></div>';
			
			
			
	// 		echo '<div class="gdymc_overlay_foot">';
	// 		echo '<div class="gdymc_overlay_foot_inner gdymc_fix">';
				
	// 			echo '<div class="gdymc_button gdymc_overlay_close_trigger">' . __( 'Done', 'gdy-modular-content' ) . '</div>';

	// 		echo '</div>';
	// 		echo '</div>';
			
			
			
	// 		echo '<div class="gdymc_overlay_content">';
	// 			echo '<div class="gdymc_overlay_content_inner">';


	// 				echo '<div class="gdymc_tabs_content gdymc_active" data-tab="selection">';
						
	// 					optionSection( __( 'Selection', 'gdy-modular-content' ) );

	// 					echo '<button id="gdymc_batch_action_select_all" class="gdymc_button">' . __( 'Select all modules', 'gdy-modular-content' ) . '</button>';
	// 					echo '<br /><br /><button id="gdymc_batch_action_select_nothing" class="gdymc_button">' . __( 'Unselect all modules', 'gdy-modular-content' ) . '</button>';
	// 					echo '<br /><br /><button id="gdymc_batch_action_select_visible" class="gdymc_button">' . __( 'Add visible modules to selection', 'gdy-modular-content' ) . '</button>';
	// 					echo '<br /><br /><button id="gdymc_batch_action_select_invisible" class="gdymc_button">' . __( 'Add invisible modules to selection', 'gdy-modular-content' ) . '</button>';
						

	// 				echo '</div><!-- gdymc_tabs_content -->';


	// 				echo '<div class="gdymc_tabs_content" data-tab="actions">';
						

	// 					optionSection( __( 'Delete', 'gdy-modular-content' ) );

	// 					echo '<button id="gdymc_batch_action_delete" class="gdymc_button">' . __( 'Delete selected modules', 'gdy-modular-content' ) . '</button>';
						

	// 					optionSection( __( 'Move or copy', 'gdy-modular-content' ) );

	// 					echo '<div>';

	// 						echo '<div class="gdymc_optioncontainer">';

	// 							echo '<div class="gdymc_optionlabel">' . __( 'Target ID', 'gdy-modular-content' ) . '</div>';

	// 							echo '<input id="gdymc_batch_action_target_input" class="gdymc_option_nosave gdymc_option-text" />';
								
	// 						echo '</div>';

	// 						echo '<div class="gdymc_optioncontainer gdymc_fix">';

	// 							echo '<button id="gdymc_batch_action_move" class="gdymc_button" style="margin-right: 20px;">' . __( 'Move selected modules', 'gdy-modular-content' ) . '</button>';
								
	// 							echo '<button id="gdymc_batch_action_copy" class="gdymc_button">' . __( 'Copy selected modules', 'gdy-modular-content' ) . '</button>';

	// 						echo '</div>';

	// 					echo '</div>';
						
						
	// 				echo '</div><!-- gdymc_tabs_content -->';
					

	// 			echo '</div><!-- .gdymc_overlayInner -->';
	// 		echo '</div><!-- .gdymc_overlayContent -->';

			
	// 		die();

	// 	endif;

	// }



	// Move multiple modules

	// add_action( 'wp_ajax_gdymc_action_movemodules', 'gdymc_action_movemodules' );

	// function gdymc_action_movemodules() {

	// 	if( gdymc_logged() ):



	// 		// Get database object

	// 		global $wpdb;



	// 		// Get informations

	// 		$objectID = $_POST[ 'object_id' ];
	// 		$objectType = $_POST[ 'object_type' ];
	// 		$targetID = $_POST[ 'target' ];
	// 		$modules = explode( ',', ltrim( $_POST[ 'modules' ], ',' ) );
	// 		$postStatus = get_post_status( $targetID );

	// 		if( empty( $postStatus ) ):

	// 			die( '_e:Target doesnt exist' );

	// 		else:


	// 			// Catch module lists

	// 			$currentModuleArray = gdymc_module_array( $objectID );
	// 			$targetModuleArray = gdymc_module_array( $targetID );



	// 			// Iterate through modules to move

	// 			foreach( $modules as $moduleID ): if( !empty( $moduleID ) ):



	// 				// Delete module from current objects list

	// 				if( ( $key = array_search( $moduleID, $currentModuleArray ) ) !== false ) {

	// 					unset( $currentModuleArray[ $key ] );

	// 				}
					

	// 				// Insert module in new objects list

	// 				array_push( $targetModuleArray, $moduleID );



	// 				// Move contents

	// 				$currentContents = get_metadata( $objectType, $objectID, '_gdymc_' . $moduleID . '_content', true );
	// 				$contents = explode( ',', trim( trim( $currentContents, '[' ), ']' ) );

	// 				foreach( $contents as $contentID ): if( !empty( $contentID ) ):

	// 					$wpdb->query( 'UPDATE ' . $wpdb->postmeta . ' SET post_id="' . $targetID . '" WHERE post_id=' . $objectID . ' AND meta_key LIKE "_gdymc_singlecontent_' . $contentID . '"' );

	// 				endif; endforeach;



	// 				// Move module
					
	// 				$wpdb->query( 'UPDATE ' . $wpdb->postmeta . ' SET post_id="' . $targetID . '" WHERE post_id=' . $objectID . ' AND meta_key LIKE "_gdymc_' . $moduleID . '_%"' );



	// 			endif; endforeach;


	// 			// Save module lists
	// 			update_metadata( $objectType, $objectID, '_gdymc_modulelist', json_encode( array_values( $currentModuleArray ) ) );
	// 			update_metadata( $objectType, $targetID, '_gdymc_modulelist', json_encode( array_values( $targetModuleArray ) ) );


	// 			// Return target permalink

	// 			die( get_the_permalink( $targetID ) );

	// 		endif;


	// 	endif;

	// }



	// Copy multiple modules

	// add_action( 'wp_ajax_gdymc_action_copymodules', 'gdymc_action_copymodules' );

	// function gdymc_action_copymodules() {

	// 	if( gdymc_logged() ):


	// 		// Get database object

	// 		global $wpdb;


	// 		// Get informations

	// 		$objectID = $_POST[ 'object_id' ];
	// 		$objectType = $_POST[ 'object_type' ];
	// 		$targetID = $_POST[ 'target' ];
	// 		$modules = explode( ',', ltrim( $_POST[ 'modules' ], ',' ) );
	// 		$postStatus = get_post_status( $targetID );

	// 		if( empty( $postStatus ) ):

	// 			die( '_e:Target doesnt exist' );

	// 		else:


	// 			// Catch module lists

	// 			$currentModuleArray = gdymc_module_array( $objectID );
	// 			$targetModuleArray = gdymc_module_array( $targetID );



	// 			// Iterate through modules to move

	// 			foreach( $modules as $oldModuleID ): if( !empty( $oldModuleID ) ):


	// 				// Create a new module id

	// 				$newModuleID = uniqid();



	// 				// Insert new module in new objects list

	// 				array_push( $targetModuleArray, $newModuleID );



	// 				// Copy contents
					
	// 				$newContents = array();
	// 				$currentContents = get_metadata( $objectType, $objectID, '_gdymc_' . $oldModuleID . '_content', true );
	// 				$contents = explode( ',', trim( trim( $currentContents, '[' ), ']' ) );


	// 				foreach( $contents as $oldContentID ):

	// 					$newContentID = uniqid();

	// 					array_push( $newContents, $newContentID );

	// 					$currentContent = get_metadata( $objectType, $objectID, '_gdymc_singlecontent_' . $oldContentID, true );
	// 					$result = update_metadata( $objectType, $targetID, '_gdymc_singlecontent_' . $newContentID, $currentContent );

	// 				endforeach;
					

					
	// 				// Copy options and stuff

	// 				$contents = $wpdb->get_results( "SELECT * FROM $wpdb->postmeta WHERE post_id=$objectID AND meta_key LIKE '_gdymc_$oldModuleID%'" );


	// 				foreach( $contents as $content ):

	// 					$result = update_metadata( $objectType, $targetID, str_replace( $oldModuleID, $newModuleID, $content->meta_key ), $content->meta_value );

	// 				endforeach;

					
	// 				// Save module contents

	// 				$result = update_metadata( $objectType, $targetID, '_gdymc_'.$newModuleID.'_content', '[' . implode( ',', $newContents ) . ']' );
					



	// 			endif; endforeach;



	// 			// Save module list

	// 			update_metadata( $objectType, $objectID, '_gdymc_modulelist', json_encode( array_values( $targetModuleArray ) ) );


	// 			// Return target permalink

	// 			die( get_the_permalink( $targetID ) );

	// 		endif;


	// 	endif;

	// }




	// Image window

	add_action( 'wp_ajax_gdymc_action_imageoverlay', 'gdymc_action_imageoverlay' );

	function gdymc_action_imageoverlay() {


		if( gdymc_logged() ):
			

			// Settings
			
			if(isset($_POST['w'])) $targetWidth = $_POST['w'];
			if(isset($_POST['h'])) $targetHeight = $_POST['h'];
			$currentImages = ( isset( $_POST['i'] ) AND json_decode( stripcslashes( $_POST['i'] ) ) != NULL ) ? json_decode( stripcslashes( $_POST['i'] ) ) : NULL;
			if(isset($_POST['multiple'])) $allowMultiple = $_POST['multiple']; else $allowMultiple = false;
			if(isset($_POST['m'])) $currentMode = $_POST['m']; else $currentMode = 'exact';
			
			
			$targetNumericWidth = is_numeric($targetWidth) ? $targetWidth : 0;
			$targetNumericHeight = is_numeric($targetHeight) ? $targetHeight : 0;

			?>

			<script type="text/javascript">
			
			
				jQuery( document ).ready( function() {

					var gdymc_image_infinitescroll_page = null;

					jQuery( '#gdymc_overlay_content_images' ).scroll( function() {

						var holder = jQuery( this );
						var outerOffset = holder.offset().top;
						var target = holder.find( '.gdymc_overlay_content_inner' );
						var targetOffset = target.offset().top;
						var measure = target.height() - holder.height() - 200;
						var scrolled = Math.abs( targetOffset - outerOffset );
						var newPage = jQuery( '#gdymc_loadmore_images' ).attr('data-page');

						if( scrolled >= measure && gdymc_image_infinitescroll_page != newPage ) {
							gdymc_image_infinitescroll_page = newPage;
							jQuery( '#gdymc_loadmore_images' ).click();
						}

					} );

				} );


			</script>


			<?php

			echo '<div class="gdymc_overlay_head"><div class="gdymc_overlay_head_inner">';

				echo '<button class="gdymc_overlay_close gdymc_overlay_close_trigger"></button>';

				if( $allowMultiple ):
					echo '<div class="gdymc_overlay_title">' . __('Insert Images', 'gdy-modular-content') . ': ' . $targetWidth . ' x ' . $targetHeight . '</div>';
				else:
					echo '<div class="gdymc_overlay_title">' . __('Insert Image', 'gdy-modular-content') . ': ' . $targetWidth . ' x ' . $targetHeight . '</div>';
				endif;

				echo '<div class="gdymc_tabs_navigation">';

					echo '<button class="gdymc_tabs_button gdymc_active" data-mode="exact">'.__('Matching images', 'gdy-modular-content').'</button>';
					echo '<button class="gdymc_tabs_button" data-mode="bigger">'.__('Bigger images', 'gdy-modular-content').'</button>';
					echo '<button class="gdymc_tabs_button" data-mode="all">'.__('All images', 'gdy-modular-content').'</button>';
					
					do_action( 'gdymc_imagetabbuttons' );
				
				echo '</div>';
				
			
			echo '</div></div>';



			




			echo '<div class="gdymc_overlay_search">';
			echo '<div class="gdymc_overlay_search_inner gdymc_fix">';
			
			echo '<input type="search" id="gdymc_search_images" placeholder="'.__('Search', 'gdy-modular-content').'" />';

			echo '</div>';
			echo '</div>';
			

			
			echo '<div id="gdymc_overlay_content_images" class="gdymc_overlay_content">';
				echo '<div class="gdymc_overlay_content_inner">';

					echo '<div id="gdymc_imagelist_holder" class="gdymc_imagelist_mode_exact gdymc_overlay_content_padding gdymc_fix" data-multiple="'.$allowMultiple.'" data-ci=\''.json_encode( $currentImages, JSON_UNESCAPED_SLASHES ).'\' data-tw="'.$targetWidth.'" data-th="'.$targetHeight.'"  data-tnw="'.$targetNumericWidth.'"  data-tnh="'.$targetNumericHeight.'" data-p="1">';
						
						gdymc_action_imagelist( $_POST['i'], $targetWidth, $targetHeight, $targetNumericWidth, $targetNumericHeight, 1 );
					
					echo '</div>';

				echo '</div>';
			echo '</div>';


			echo '<div id="gdymc_overlay_content_imageinfo"></div>';



			echo '<div class="gdymc_overlay_foot"><div class="gdymc_overlay_foot_inner gdymc_fix">';

			if( is_array( $currentImages ) AND count( $currentImages ) == 1 ):

				$buttonTitle = __( 'Use image and save', 'gdy-modular-content' );

			elseif( is_array( $currentImages ) AND count( $currentImages ) > 1 ):

				$buttonTitle = str_replace( '%s', count( $currentImages ), __( 'Use %s images and save', 'gdy-modular-content' ) );

			else:

				$buttonTitle = __('Use void image and save', 'gdy-modular-content');

			endif;

			echo '<div class="gdymc_left">';

				echo '<button id="gdymc_imageinsert" class="gdymc_button">'.$buttonTitle.'</button>';

				echo '<ul class="gdymc_image_selection gdymc_fix">';

					if( $currentImages ): foreach( $currentImages as $currentImage ):

						echo '<li class="gdymc_imagethumb" data-image=\'' . json_encode( $currentImage ) . '\' data-id="' . $currentImage[0] . '">';
						echo '<div class="gdymc_imagethumb_edit"></div>';
						echo '<div class="gdymc_imagethumb_holder">' . wp_get_attachment_image( $currentImage[0], 'thumbnail', true, array( 'class' => 'gdymc_mediathumb_' . $currentImage[0] ) ) . '</div>';
						echo '</li>';

					endforeach; endif;

				echo '</ul>';

			echo '</div>';


			echo '</div></div>';
			
		

		endif;

		die();

	}



	add_action( 'wp_ajax_gdymc_action_imageinfo', 'gdymc_action_imageinfo' );

	function gdymc_action_imageinfo() {

		$imageID = $_POST[ 'image' ];

		if( !$post = get_post( $imageID ) ):

			status_header( 404 ); die( "Image doesn't exist" );

		else:

			$meta = wp_get_attachment_metadata( $imageID );
			$alt = get_metadata( 'post', $imageID, '_wp_attachment_image_alt', true );

			echo '<div id="gdymc_overlay_content_imageinfoinner" data-id="' . $imageID . '">';

				$image = wp_get_attachment_image_src( $imageID, 'medium' );
				echo '<a id="gdymc_overlay_content_imageinfothumb" href="' . $post->guid . '" target="_blank" style="background-image: url(' . $image[ 0 ] . ');">';
					echo '<div>' . $meta[ 'width' ] . ' x ' . $meta[ 'height' ] . ', ' . size_format( filesize( get_attached_file( $imageID ) ) ) . '</div>';
				echo '</a>';


				echo '<div id="gdymc_overlay_content_imageinfotext">';


					echo '<div id="gdymc_overlay_content_imageinfo_local" style="display: none;">';

						optionSection( __( 'Local image settings', 'gdy-modular-content' ) );

						echo '<label for="gdymc_imageinfo_linkurl">' . __( 'Image link', 'gdy-modular-content' ) . '</label><input id="gdymc_imageinfo_linkurl" type="text" value=""  class="gdymc_imageinfo_local_input" />';

						echo '<br />';

						echo '<input type="checkbox" id="gdymc_imageinfo_linktarget" class="gdymc_imageinfo_local_input" /> <label for="gdymc_imageinfo_linktarget">' . __( 'Open in new tab or window', 'gdy-modular-content' ) . '</label>';
					
					echo '</div>';



					echo '<div id="gdymc_overlay_content_imageinfo_global">';

						optionSection( __( 'Global image settings', 'gdy-modular-content' ) );

						echo '<label for="gdymc_imageinfo_title">' . __( 'Title' ) . '</label><input class="gdymc_imageinfo_global_input" id="gdymc_imageinfo_title" type="text" value="' . $post->post_title . '" />';

						echo '<br />';

						echo '<label for="gdymc_imageinfo_caption">' . __( 'Caption' ) . '</label><textarea class="gdymc_imageinfo_global_input" id="gdymc_imageinfo_caption">' . $post->post_excerpt . '</textarea>';

						echo '<br />';

						echo '<label for="gdymc_imageinfo_alt">' . __( 'Alt Text' ) . '</label><input class="gdymc_imageinfo_global_input" id="gdymc_imageinfo_alt" type="text" value="' . $alt . '" />';

						echo '<br />';

						echo '<label for="gdymc_imageinfo_description">' . __( 'Description' ) . '</label><textarea class="gdymc_imageinfo_global_input" id="gdymc_imageinfo_description">' . $post->post_content . '</textarea>';

					echo '</div>';


					//echo edit_post_link( __( 'Edit Image' ), null, null, $imageID );
					//echo '<button class="gdymc_delete_link">' . __( 'Delete Permanently', 'gdy-modular-content' ) . '</button>';
					echo '<br /><button class="gdymc_close_imageinfo gdymc_delete_link">' . __( 'Cancel', 'gdy-modular-content' ) . '</button>';


				echo '</div>';

			echo '</div>';

		endif;
		
		die();

	}



	add_action( 'wp_ajax_gdymc_update_attachment_image', 'gdymc_update_attachment_image' );

	function gdymc_update_attachment_image( $imageID = '', $title = '', $caption = '', $alt = '', $description = '' ) {

		$imageID = $_POST[ 'imageID' ] ? $_POST[ 'imageID' ] : $imageID;
		$title = $_POST[ 'title' ] ? $_POST[ 'title' ] : $title;
		$caption = $_POST[ 'caption' ] ? $_POST[ 'caption' ] : $caption;
		$alt = $_POST[ 'alt' ] ? $_POST[ 'alt' ] : $alt;
		$description = $_POST[ 'description' ] ? $_POST[ 'description' ] : $description;

		$my_post = array(
			'ID' => $imageID,
			'post_title' => $title,
			'post_content' => $description,
			'post_excerpt' => $caption,
		);

		update_metadata( 'post', $imageID, '_wp_attachment_image_alt', $alt );

		wp_update_post( $my_post );

	}






	// List images

	add_action( 'wp_ajax_gdymc_action_imagelist', 'gdymc_action_imagelist' );

	function gdymc_action_imagelist( $currentImages = '', $targetWidth = '', $targetHeight = '', $targetNumericWidth = '', $targetNumericHeight = '', $page = '' ) {


		// Transfers the image meta size to postmeta for the query
		do_action( 'gdymc_transfer_attachment_image_size' );



		$currentImages = (isset($_POST['ci'])) ? json_decode( stripcslashes( $_POST['ci'] ) ) : json_decode( stripcslashes( $currentImages ) );
		$targetWidth = (isset($_POST['tw'])) ? $_POST['tw'] : $targetWidth;
		$targetHeight = (isset($_POST['th'])) ? $_POST['th'] : $targetHeight;
		$targetNumericWidth = (isset($_POST['tnw'])) ? $_POST['tnw'] : $targetNumericWidth;
		$targetNumericHeight = (isset($_POST['tnh'])) ? $_POST['tnh'] : $targetNumericHeight;
		$ajax = ( isset( $_POST[ 'ajax' ] ) ) ? true : false;
		$mode = ( isset( $_POST[ 'mode' ] ) ) ? $_POST[ 'mode' ] : 'exact';
		$page = ( isset( $_POST[ 'p' ] ) ) ? $_POST[ 'p' ] : 1;
		$posts_per_page = 50;
		$offset = $posts_per_page * ( $page - 1 );




		// Main query
		
		$args = array(

			'orderby' => 'date',
			'order' => 'DESC',
		    'post_type' => 'attachment',
		    'post_mime_type' => 'image',
		    'post_status' => 'any',
		    'posts_per_page' => $posts_per_page

		);



		



		
		if( $mode == 'exact' ):


			// Exact

			$meta_query = array( 'relation' => 'AND' );




			$meta_query_handler = array( 'relation' => 'AND' );

			if( is_numeric( $targetWidth ) ):

				array_push( $meta_query_handler, array(
		            'key'     => '_gdymc_image_width',
		            'value'   => $targetWidth,
		            'type'    => 'numeric',
		            'compare' => '=',
		        ) );

			endif;

			if( is_numeric( $targetHeight ) ):

				array_push( $meta_query_handler, array(
		            'key'     => '_gdymc_image_height',
		            'value'   => $targetHeight,
		            'type'    => 'numeric',
		            'compare' => '=',
		        ) );

			endif;

			array_push( $meta_query, $meta_query_handler );


		elseif( $mode == 'bigger' ):


			// Bigger

			$meta_query = array( 'relation' => 'AND' );



			$meta_query_handler = array( 'relation' => 'AND' );

			if( is_numeric( $targetWidth ) ):

				array_push( $meta_query_handler, array(
		            'key'     => '_gdymc_image_width',
		            'value'   => $targetWidth,
		            'type'    => 'numeric',
		            'compare' => '>=',
		        ) );

			endif;

			if( is_numeric( $targetHeight ) ):

				array_push( $meta_query_handler, array(
		            'key'     => '_gdymc_image_height',
		            'value'   => $targetHeight,
		            'type'    => 'numeric',
		            'compare' => '>=',
		        ) );

			endif;

			array_push( $meta_query, $meta_query_handler );



			$meta_query_handler = array( 'relation' => 'OR' );

			if( is_numeric( $targetWidth ) ):

				array_push( $meta_query_handler, array(
		            'key'     => '_gdymc_image_width',
		            'value'   => $targetWidth,
		            'type'    => 'numeric',
		            'compare' => '>',
		        ) );

			endif;

			if( is_numeric( $targetHeight ) ):

				array_push( $meta_query_handler, array(
		            'key'     => '_gdymc_image_height',
		            'value'   => $targetHeight,
		            'type'    => 'numeric',
		            'compare' => '>',
		        ) );

			endif;

			array_push( $meta_query, $meta_query_handler );


		endif;
		

		


		if( $mode != 'all' AND ( is_numeric( $targetWidth ) OR is_numeric( $targetHeight ) ) ):

			$args['meta_query'] = $meta_query;

		endif;





		// Extend query for search

		if( !empty( $_POST['s'] ) ):

			$args['s'] = $_POST['s'];

		endif;


		// Extend query for paging

		if( $page > 1 ):

			$args[ 'offset' ] = $offset;
			$pageNumber = $page;

		endif;



		
		// Fire the query

		$the_query = new WP_Query( $args );



		if( $the_query->have_posts() ): 

			while( $the_query->have_posts() ): $the_query->the_post();
				


				// IDs
			
				$currentID = get_the_ID();
				$imageGUIDHandler = wp_get_attachment_image_src( $currentID, 'fullsize' );
				$currentGUID = $imageGUIDHandler[0];


				// Get image dimensions

				$attachmentMeta = wp_get_attachment_metadata( $currentID );


				if( !empty( $attachmentMeta ) ):


					$currentWidth = $attachmentMeta[ 'width' ];
					$currentHeight = $attachmentMeta[ 'height' ];


					// Attributes
					
					$currentClass = 'gdymc_imagethumb';
					$currentMeta = 'data-id="' . $currentID . '"';
					$currentMeta .= ' data-guid="' . $currentGUID . '"';
					$currentMeta .= ' data-width="' . $currentWidth . '"';
					$currentMeta .= ' data-height="' . $currentHeight . '"';
					$currentMeta .= ' data-admin="' . admin_url('post.php?post=' . $currentID . '&action=edit') . '"';
					



					// Check size

					if( ( $targetWidth == 'auto' OR $targetNumericWidth == $currentWidth) AND ( $targetHeight == 'auto' OR $targetNumericHeight == $currentHeight ) ):

						$currentType = 'exact';
						$currentMeta .= ' data-type="exact"';
						$currentTip = __( 'Insert image', 'gdy-modular-content' );
					
					elseif( ( $targetWidth == 'auto' OR $targetNumericWidth <= $currentWidth) AND ( $targetHeight == 'auto' OR $targetNumericHeight <= $currentHeight ) ):
						
						if( current_user_can( 'upload_files', gdymc_object_id() ) ):

							$currentType = 'crop';
							$currentMeta .= ' data-type="crop"';

						else:

							$currentType = 'bigger';
							$currentMeta .= ' data-type="bigger"';

						endif;
					
					else:
						
						$currentType = 'smaller';
						$currentMeta .= ' data-type="smaller"';
					
					endif;
					


					// If selected

					$results = array();

					if( $currentImages ): foreach( $currentImages as $currentImage ):

						if( $currentImage[0] == $currentID ) array_push( $results, 1 );

					endforeach; endif;

					if( $results ) {
						$currentClass .= ' gdymc_selected';	
					}
					

					
					// Show image
					
					echo '<div class="gdymc_imagethumb_container">';
					echo '<button class="' . $currentClass . '" ' . $currentMeta . '>';

						echo '<div class="gdymc_imagethumb_edit"></div>';

						echo wp_get_attachment_image( $currentID, 'thumbnail', true, array( 'class' => 'gdymc_mediathumb_' . $currentID ) );
						
						echo '<div class="gdymc_imagethumb_size">' . $currentWidth . ' x ' . $currentHeight . '</div>';

					echo '</button>';
					echo '</div>';

			
				endif;

		
			endwhile; 
			

			$visible_posts = $offset + $the_query->post_count;
			$available_posts = $the_query->found_posts;


			if( $available_posts > $visible_posts ):

				echo '<div id="gdymc_loadmore_images" class="gdymc_loadmore">' . str_replace( '%s', $available_posts-$visible_posts, __( 'More images (%s)', 'gdy-modular-content' ) ) . '</div>';

			endif;


		else: 

			if( $page == 1 ):

				echo '<div class="gdymc_noentries">' . __( 'No contents', 'gdy-modular-content' ) . '</div>'; 

			endif;

		endif; 

		if( $ajax ) die();

		
	}













	
	add_action( 'wp_ajax_gdyModularContentUploadAction', 'gdyModularContentUploadAction' );

	function gdyModularContentUploadAction() {

		if( gdymc_logged() ):
			
			$result = media_handle_upload( 'gdymc_upload', $_POST[ 'object' ] );

			if( is_wp_error( $result ) ):

				var_dump( $result );

			else:

				echo get_edit_post_link( $result, '' );

			endif;
			
			die();	
			
		endif;

	}
	
	

	
	
	

	
	// List pages

	add_action( 'wp_ajax_gdymc_action_pagelist', 'gdymc_action_pagelist' );

	function gdymc_action_pagelist() {
		
		$size = 20;
		
		$args = array(
			'orderby' => 'date',
			'order' => 'DESC',
			'posts_per_page' => $size,
			'post_type' => 'page',
		);
		
		if(isset($_POST['s'])) {
			array_push($args, $_POST['s']);
		}
		if(isset($_POST['p']) && is_numeric($_POST['p'])) {
			$args['offset'] = $size*$_POST['p'];
			$pageNumber = $_POST['p'];	
			$external = true;
		} else {
			$external = false;
			$pageNumber = 0;	
		}
		
		$the_query = new WP_Query($args);
		
		
		
		if($the_query->have_posts()): while($the_query->have_posts()): $the_query->the_post();
			
			echo '<div class="gdymc_insertlink_source">';
			echo '<div class="gdymc_insertlink_source-title">' . get_the_title() . ' <span>' . get_the_date() . '</span></div>';
			
			echo '<div class="gdymc_insertlink_source-meta">';
			echo '<a class="gdymc_insertlink_source-use" data-guid="'.get_permalink().'" href="#">' . __( 'Use as link', 'gdy-modular-content' ) . '</a> | ';
			echo '<a href="' . get_permalink() . '" target="_blank">' . __( 'View', 'gdy-modular-content' ) . '</a> | ';
			echo '<a href="' . get_edit_post_link( get_the_ID() ) . '" target="_blank">' . __( 'Edit', 'gdy-modular-content' ) . '</a> | ';
			echo '<a class="gdymc_delete_link" href="' . get_delete_post_link( get_the_ID() ) . '" target="_blank">' . __( 'Trash', 'gdy-modular-content' ) . '</a>';
			echo '</div>';

			echo '</div>'; 
	
		endwhile; 
			
			$additionalContents = $the_query->found_posts - ( ( $pageNumber + 1 ) * $size );

			if( $additionalContents > 0 ):

				echo '<div id="gdymc_loadmore_pages" class="gdymc_loadmore" data-page="' . $pageNumber . '">' . __( 'Show more', 'gdy-modular-content' ) . ' (' . $additionalContents . ')</div>';
			
			endif;
			
		else: if(!$external) echo '<div class="gdymc_noentries">'.__('No contents', 'gdy-modular-content').'</div>'; endif; if($external) die();	
		
		
		
	}
	
	

	// List posts

	add_action( 'wp_ajax_gdymc_action_postlist', 'gdymc_action_postlist' );

	function gdymc_action_postlist() {
		
		$size = 20;

		$args = array(
			'orderby' => 'date',
			'order' => 'DESC',
			'posts_per_page' => $size,
			'post_type' => 'post',
		);
		
		if(isset($_POST['s'])) {
			array_push($args, $_POST['s']);
		}
		if(isset($_POST['p']) && is_numeric($_POST['p'])) {
			$args['offset'] = $size*$_POST['p'];
			$pageNumber = $_POST['p'];	
			$external = true;
		} else {
			$external = false;
			$pageNumber = 0;	
		}
		
		$the_query = new WP_Query($args);
		
		if($the_query->have_posts()): while($the_query->have_posts()): $the_query->the_post();
			
			echo '<div class="gdymc_insertlink_source">';
			echo '<div class="gdymc_insertlink_source-title">'.get_the_title().' <span>' . get_the_date() . '</span></div>';
			
			echo '<div class="gdymc_insertlink_source-meta">';
			echo '<a class="gdymc_insertlink_source-use" data-guid="'.get_permalink().'" href="#">' . __( 'Use as link', 'gdy-modular-content' ) . '</a> | ';
			echo '<a href="' . get_permalink() . '" target="_blank">' . __( 'View', 'gdy-modular-content' ) . '</a> | ';
			echo '<a href="' . get_edit_post_link( get_the_ID() ) . '" target="_blank">' . __( 'Edit', 'gdy-modular-content' ) . '</a> | ';
			echo '<a class="gdymc_delete_link" href="' . get_delete_post_link( get_the_ID() ) . '" target="_blank">' . __( 'Trash', 'gdy-modular-content' ) . '</a>';
			echo '</div>';

			echo '</div>'; 
	
		endwhile; 
			
			$additionalContents = $the_query->found_posts - ( ( $pageNumber + 1 ) * $size );

			if( $additionalContents > 0 ):

				echo '<div id="gdymc_loadmore_posts" class="gdymc_loadmore" data-page="' . $pageNumber . '">' . __( 'Show more', 'gdy-modular-content' ) . ' (' . $additionalContents . ')</div>';
			
			endif;
			
		else: if(!$external) echo '<div class="gdymc_noentries">'.__('No contents', 'gdy-modular-content').'</div>'; endif; if($external) die();
		
	}
	


	// List files
	
	add_action( 'wp_ajax_gdymc_action_filelist', 'gdymc_action_filelist' );

	function gdymc_action_filelist() {
		
		$size = 20;

		$args = array(
			'orderby' => 'date',
			'order' => 'DESC',
			'posts_per_page' => $size,
			'post_status' => 'any',
			'post_type' => 'attachment',
			'post_mime_type' => 'application',
		);
		
		if(isset($_POST['s'])) {
			$args['s'] = $_POST['s'];
		}
		if(isset($_POST['p']) && is_numeric($_POST['p'])) {
			$args['offset'] = $size*$_POST['p'];
			$pageNumber = $_POST['p'];	
			$external = true;
		} else {
			$external = false;
			$pageNumber = 0;	
		}
		
		$the_query = new WP_Query($args);
		
		if($the_query->have_posts()): while($the_query->have_posts()): $the_query->the_post();
			
			echo '<div class="gdymc_insertlink_source">';
			echo '<div class="gdymc_insertlink_source-title">'.get_the_title().' <span>' . get_the_date() . '</span></div>';

			echo '<div class="gdymc_insertlink_source-meta">';
			echo '<a class="gdymc_insertlink_source-use" data-guid="' . wp_get_attachment_url( get_the_ID() ) . '" href="#">' . __( 'Use as link', 'gdy-modular-content' ) . '</a> | ';
			echo '<a href="' . wp_get_attachment_url( get_the_ID() ) . '" target="_blank">' . __( 'View', 'gdy-modular-content' ) . '</a> | ';
			echo '<a href="' . get_edit_post_link( get_the_ID() ) . '" target="_blank">' . __( 'Edit', 'gdy-modular-content' ) . '</a> | ';
			echo '<a class="gdymc_delete_link" href="' . get_delete_post_link( get_the_ID() ) . '" target="_blank">' . __( 'Delete Permanently', 'gdy-modular-content' ) . '</a>';
			echo '</div>';

			echo '</div>'; 
	
		endwhile; 
		
			$additionalContents = $the_query->found_posts - ( ( $pageNumber + 1 ) * $size );

			if( $additionalContents > 0 ):

				echo '<div id="gdymc_loadmore_files" class="gdymc_loadmore" data-page="' . $pageNumber . '">' . __( 'Show more', 'gdy-modular-content' ) . ' (' . $additionalContents . ')</div>';
			
			endif;
			
		else: if(!$external) echo '<div class="gdymc_noentries">'.__('No contents', 'gdy-modular-content').'</div>'; endif; if($external) die();
		
	}


	// List categories

	add_action( 'wp_ajax_gdymc_action_categorylist', 'gdymc_action_categorylist' );

	function gdymc_action_categorylist() {
		
		$size = 20;



		$args = array(
			'number' => $size,
			'hide_empty' => false,
		);
		
		if(isset($_POST['s'])) {
			array_push($args, $_POST['s']);
		}
		if(isset($_POST['p']) && is_numeric($_POST['p'])) {
			$args['offset'] = $size*$_POST['p'];
			$pageNumber = $_POST['p'];	
			$external = true;
		} else {
			$external = false;
			$pageNumber = 0;	
		}
		
		$cats = get_categories($args);

		if( count( $cats ) > 0 ):

			foreach( $cats as $cat ):
				
				echo '<div class="gdymc_insertlink_source">';
				echo '<div class="gdymc_insertlink_source-title">'.$cat->name.'</div>';
				
				echo '<div class="gdymc_insertlink_source-meta">';
				echo '<a class="gdymc_insertlink_source-use" data-guid="'.get_category_link( $cat->term_id ).'" href="#">' . __( 'Use as link', 'gdy-modular-content' ) . '</a> | ';
				echo '<a href="' . get_category_link( $cat->term_id ) . '" target="_blank">' . __( 'View', 'gdy-modular-content' ) . '</a> | ';
				echo '<a href="' . get_edit_term_link( $cat->term_id, 'category', 'post' ) . '" target="_blank">' . __( 'Edit', 'gdy-modular-content' ) . '</a>';
				echo '</div>';

				echo '</div>';

			endforeach;
			
		endif;
		
	}


	
	
	// Can be removed?

	/*

	add_action('wp_ajax_gdyModularContentImageDetail', 'gdyModularContentImageDetail');
	function gdyModularContentImageDetail() {
		
		$imageID = $_POST['id'];
		$image = get_post($imageID);
		
	
		echo '<img src="'.wp_get_attachment_thumb_url($imageID).'" /><br />';
		
		
		echo gdymc_time_ago($image->post_date_gmt);
		
		echo '<div><div class="gdymc_redLink gdymc_deleteImage" data-choose="'.__('You must select an image!', 'gdy-modular-content').'" data-delete="'.__('Are you sure you want to delete this picture?', 'gdy-modular-content').'">'.__('Delete Permanently', 'gdy-modular-content').'</div></div>';
		
		
		echo '<div class="gdymc_formLabel">'.__('Title', 'gdy-modular-content').'</div>';
		echo '<input class="gdymc_formText" value="'.$image->post_title.'" />';
		
		echo '<div class="gdymc_formLabel">'.__('Caption', 'gdy-modular-content').'</div>';
		echo '<textarea class="gdymc_formArea">'.$image->post_excerpt.'</textarea>';
		
		echo '<div class="gdymc_formLabel">'.__('Alternative text', 'gdy-modular-content').'</div>';
		echo '<input class="gdymc_formText" value="'.$image->post_title.'" />';
		
		echo '<div class="gdymc_formLabel">'.__('Description', 'gdy-modular-content').'</div>';
		echo '<textarea class="gdymc_formArea">'.$image->post_content.'</textarea>';
		
		
		die();
		
	}

	*/
	
	

	
	add_action('wp_ajax_gdyModularContentDeleteAction', 'gdyModularContentDeleteAction');

	function gdyModularContentDeleteAction() {

		if(gdymc_logged()) {

			wp_delete_attachment($_POST['i']);
			
			die();

		}

	}
	
	


	// Cropping

	add_action('wp_ajax_gdymc_action_cropimage', 'gdymc_action_cropimage');

	function gdymc_action_cropimage() {

		if( gdymc_logged() ):
			

			// Get the image data

			$sourceID = $_POST['source_id'];
			$sourcePath = get_attached_file( $sourceID );

			$targetWidth = trim($_POST['target_w']);
			$targetHeight = trim($_POST['target_h']);

			$cropX = $_POST['crop_x'];
			$cropY = $_POST['crop_y'];
			$cropWidth = $_POST['crop_w'];
			$cropHeight = $_POST['crop_h'];

			

			// Set sizes for auto

			if( $targetWidth == 'auto' && $targetHeight == 'auto' ):

				$targetWidth = $cropWidth;
				$targetHeight = $cropHeight;

			else:

				if( $targetWidth == 'auto' ):

					$sizeHandler = $cropHeight / $targetHeight;
					$targetWidth = round( $cropWidth / $sizeHandler );

				endif;

				if( $targetHeight == 'auto' ):

					$sizeHandler = $cropWidth / $targetWidth;
					$targetHeight = round( $cropHeight / $sizeHandler );

				endif;

			endif;



			// Crop and save
			
			$upload_directory = wp_upload_dir();

			$new_name = wp_unique_filename( $upload_directory[ 'path' ], basename( $sourcePath ) );
			$new_path = wp_crop_image( $sourceID, $cropX, $cropY, $cropWidth, $cropHeight, $targetWidth, $targetHeight, false, $upload_directory[ 'path' ] . '/' . $new_name );


			if( is_wp_error( $new_path ) ):

				http_response_code( 400 ); die( $new_path->get_error_message() );

			endif;


			// Get mime type

			$mime_type = wp_check_filetype( $new_path );


			// Create attachment in WordPress

			$attachment = array(
				'guid' => $upload_directory[ 'url' ] . '/' . $new_name,
				'post_mime_type' => $mime_type[ 'type' ],
				'post_title' => $new_name,
				'post_content' => '',
				'post_status' => 'inherit'
			);
	
			$attach_id = wp_insert_attachment( $attachment, $new_path );
			$attach_data = wp_generate_attachment_metadata( $attach_id, $new_path );
			wp_update_attachment_metadata( $attach_id, $attach_data );
			

			// Response

			$output = array(
				'id' => $attach_id,
				'width' => $attach_data['width'],
				'height' => $attach_data['height'],
				'url' => $uploadDirectory['url'] . '/' . $new_name,
			);
			
			echo json_encode( $output );
		


		endif;

		die();

	}
	
	
	
	
	
	

?>