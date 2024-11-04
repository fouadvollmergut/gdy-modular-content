<?php
	
	
	
	add_filter( 'gdymc_lang', 'gdymc_translation_strings', 10, 1 );


	function gdymc_translation_strings ( $content ) {
		

		// Set up holder

		$content = array();


		// Translations

		$content[ 'nomodulesfound' ] = __( 'No modules found', 'gdy-modular-content' );

		$content[ 'error-ok' ] = __( 'Ok', 'gdy-modular-content' );

		$content[ 'error-title' ] = __( 'Error', 'gdy-modular-content' );

		$content[ 'error-details' ] = __( 'Details', 'gdy-modular-content' );

		$content[ 'focus-text' ] = __( 'Please select a editable text for this action.', 'gdy-modular-content' );

		$content[ 'focus-title' ] = __( 'Nothing selected', 'gdy-modular-content' );

		$content[ 'unsaved-title' ] = __( 'Discard contents?', 'gdy-modular-content' );

		$content[ 'unsaved-text' ] = __( 'There are unsaved contents. This contents will be lost if you leave the page without saving. Do you want to proceed?', 'gdy-modular-content' );

		$content[ 'deletemodules-title' ] = __( 'Delete modules?', 'gdy-modular-content' );

		$content[ 'deletemodules-text' ] = __( 'Are you sure to delete the marked modules irreversible.', 'gdy-modular-content' );

		$content[ 'deletemodule-title' ] = __( 'Delete module?', 'gdy-modular-content' );

		$content[ 'deletemodule-text' ] = __( 'Are you sure to delete this module irreversible.', 'gdy-modular-content' );

		$content[ 'deletemoduletype-title' ] = __( 'Delete module type?', 'gdy-modular-content' );

		$content[ 'deletemoduletype-text' ] = __( 'Are you sure to delete this module type? This means that all modules with this type are going to be deleted irreversible.', 'gdy-modular-content' );

		$content[ 'changesinglemoduletype-title' ] = __( 'Change module type?', 'gdy-modular-content' );

		$content[ 'changesinglemoduletype-text' ] = __( 'Are you sure to change this module type? Some contents may not be displayed correctly in the new module type.', 'gdy-modular-content' );

		$content[ 'changemoduletype-title' ] = __( 'Change module type?', 'gdy-modular-content' );

		// Create module type select
		$gdymc_modules = gdymc_get_modules();
		
		$select = '<select style="margin-top: 10px;"><option value="">'.__( 'No Changes', 'gdy-modular-content' ).'</option>';
		if( $gdymc_modules ) foreach( $gdymc_modules as $handler ):
			$select .= '<option value="' . $handler->type . '">' . $handler->type . '</option>';
		endforeach;
		$select .= '</select>';

		$content[ 'changemoduletype-text' ] = str_replace( '%s', $select, __( 'Are you sure to change this module type? This means that all modules with this type are going to be affected.<br /><br />Change module type into: %s', 'gdy-modular-content' ) );

		$content[ 'button-no' ] = __( 'No', 'gdy-modular-content' );

		$content[ 'button-yes' ] = __( 'Yes', 'gdy-modular-content' );

		$content[ 'button-cancel' ] = __( 'Cancel', 'gdy-modular-content' );

		$content[ 'button-change' ] = __( 'Change', 'gdy-modular-content' );

		$content[ 'removecol-text' ] = __( "You can't remove the last column.", 'gdy-modular-content' );

		$content[ 'removerow-text' ] = __( "You can't remove the last row.", 'gdy-modular-content' );

		$content[ 'swap-content' ] = __( "Swap content", 'gdy-modular-content' );

		$content[ 'batchmove-title' ] = __( "Action stopped", 'gdy-modular-content' );

		$content[ 'batchmove-text' ] = __( "The ID you entered does not belong to an existing target.", 'gdy-modular-content' );

		$content[ 'image-bigger' ] = __( "The image is too big.", 'gdy-modular-content' );

		$content[ 'image-smaller' ] = __( "The image is too small.", 'gdy-modular-content' );

		$content[ 'leavepreview-title' ] = __( "Preview mode", 'gdy-modular-content' );

		$content[ 'leavepreview-text' ] = __( "You cant do this action in preview mode. Do you want to switch into edit mode?", 'gdy-modular-content' );

		$content[ 'leavepreview-button' ] = __( "Switch view", 'gdy-modular-content' );

		$content[ 'showallformattingoptions' ] = __( "Show all formatting options", 'gdy-modular-content' );

		$content[ 'batchselectmodule-title' ] = __( 'No modules selected', 'gdy-modular-content' );

		$content[ 'batchselectmodule-text' ] = __( 'At least one selected module is required for this action.', 'gdy-modular-content' );

		$content[ 'batchnoid-title' ] = __( 'No target ID', 'gdy-modular-content' );

		$content[ 'batchnoid-text' ] = __( 'Enter a target ID for this action.', 'gdy-modular-content' );

		$content[ 'batchnumericid-title' ] = __( 'Wrong target ID', 'gdy-modular-content' );

		$content[ 'batchnumericid-text' ] = __( 'The target ID must be a number.', 'gdy-modular-content' );

		$content[ 'dropzone-invalidresponse' ] = __( 'Invalid server response.', 'gdy-modular-content' );

		$content[ 'dropzone-filetoobig' ] = str_replace( '%s', ( wp_max_upload_size() / 1024 / 1024 ) . ' MB', __( 'This file is too big. Max filesize is %s.', 'gdy-modular-content' ) );

		$content[ 'dropzone-invalidfiletype' ] = __( 'This file type is not allowed.', 'gdy-modular-content' );

		$content[ 'imageinsert-void' ] = __( 'Use void image and save', 'gdy-modular-content' );

		$content[ 'imageinsert-singular' ] = __( 'Use image and save', 'gdy-modular-content' );

		$content[ 'imageinsert-plural' ] = __( 'Use %s images and save', 'gdy-modular-content' );

		$content[ 'unload-warning' ] = __( 'There are unsaved contents. This contents will be lost when you leave the page.', 'gdy-modular-content' );

		$content[ 'maxtext-error' ] = __( 'There are too long texts. You must shorten these texts before you can continue.', 'gdy-modular-content' );


		// AJAX

		$content[ 'ajaxerror-text' ] = __( 'There was an error with your action/request.', 'gdy-modular-content' );



		// Return translation string

		return $content;
		
		
	}	
	
	
	
?>