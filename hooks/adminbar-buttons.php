<?php



	/**************************************************** LEFT ADMINBAR BUTTONS ****************************************************/

	add_action( 'gdymc_adminbarbuttons_left', 'gdymc_hook_savebutton', 10, 0 );

	function gdymc_hook_savebutton() {
		
		echo '<li><button data-gdymc-tip="' . __( 'Save changes (Cmd+S)', 'gdy-modular-content' ) . '" id="gdymc_save" class="gdymc_save" data-id="'.get_the_ID().'">'.__('Save', 'gdy-modular-content').'</button></li>';
	
	}



	add_action( 'gdymc_adminbarbuttons_left', 'gdymc_hook_formatbutton_bold', 20, 0 );

	function gdymc_hook_formatbutton_bold() {

		echo '<li><button onmousedown="gdymc.editor.format( \'bold\', \'\' ); return false;" data-gdymc-tip="' . __( 'Bold (Cmd+B)', 'gdy-modular-content' ) . '"><span class="dashicons dashicons-editor-bold"></span></button></li>';
	
	}



	add_action( 'gdymc_adminbarbuttons_left', 'gdymc_hook_formatbutton_italic', 30, 0 );

	function gdymc_hook_formatbutton_italic() {

		echo '<li><button onmousedown="gdymc.editor.format( \'italic\', \'\' ); return false;" data-gdymc-tip="' . __( 'Italic (Cmd+I)', 'gdy-modular-content' ) . '"><span class="dashicons dashicons-editor-italic"></span></button></li>';
	
	}



	add_action( 'gdymc_adminbarbuttons_left', 'gdymc_hook_formatbutton_insertlink', 40, 0 );

	function gdymc_hook_formatbutton_insertlink() {

		echo '<li><button class="gdymc_insertlink" data-gdymc-tip="' . __( 'Insert link (Cmd+L)', 'gdy-modular-content' ) . '"><span class="dashicons dashicons-admin-links"></span></button></li>';
	
	}



	add_action( 'gdymc_adminbarbuttons_left', 'gdymc_hook_formatbutton_unlink', 50, 0 );

	function gdymc_hook_formatbutton_unlink() {

		echo '<li><button class="gdymc_unlink" onmousedown="gdymc.editor.format( \'unlink\', \'\' ); return false;" data-gdymc-tip="' . __( 'Remove link (Cmd+U)', 'gdy-modular-content' ) . '"><span class="dashicons dashicons-editor-unlink"></span></button></li>';
	
	}



	add_action( 'gdymc_adminbarbuttons_left', 'gdymc_hook_formatbutton_insertunorderedlist', 60, 0 );

	function gdymc_hook_formatbutton_insertunorderedlist() {

		echo '<li><button onmousedown="gdymc.editor.format( \'insertunorderedlist\', \'\' ); return false;" data-gdymc-tip="' . __( 'Unordered list', 'gdy-modular-content' ) . '"><span class="dashicons dashicons-editor-ul"></span></button></li>';
	
	}



	add_action( 'gdymc_adminbarbuttons_left', 'gdymc_hook_formatbutton_insertorderedlist', 70, 0 );

	function gdymc_hook_formatbutton_insertorderedlist() {

		echo '<li><button onmousedown="gdymc.editor.format( \'insertorderedlist\', \'\' ); return false;" data-gdymc-tip="' . __( 'Ordered list', 'gdy-modular-content' ) . '"><span class="dashicons dashicons-editor-ol"></span></button></li>';
	
	}



	add_action( 'gdymc_adminbarbuttons_left', 'gdymc_hook_formatbutton_justifyright', 80, 0 );

	function gdymc_hook_formatbutton_justifyright() {

		echo '<li><button onmousedown="gdymc.editor.format( \'justifyright\', \'\' ); return false;" data-gdymc-tip="' . __( 'Align right (Cmd+Shift+R)', 'gdy-modular-content' ) . '"><span class="dashicons dashicons-editor-alignright"></span></button></li>';
	
	}



	add_action( 'gdymc_adminbarbuttons_left', 'gdymc_hook_formatbutton_justifycenter', 90, 0 );

	function gdymc_hook_formatbutton_justifycenter() {

		echo '<li><button onmousedown="gdymc.editor.format( \'justifycenter\', \'\' ); return false;" data-gdymc-tip="' . __( 'Align center (Cmd+Shift+C)', 'gdy-modular-content' ) . '"><span class="dashicons dashicons-editor-aligncenter"></span></button></li>';
	
	}



	add_action( 'gdymc_adminbarbuttons_left', 'gdymc_hook_formatbutton_justifyleft', 100, 0 );

	function gdymc_hook_formatbutton_justifyleft() {

		echo '<li><button onmousedown="gdymc.editor.format( \'justifyleft\', \'\' ); return false;" data-gdymc-tip="' . __( 'Align left (Cmd+Shift+L)', 'gdy-modular-content' ) . '"><span class="dashicons dashicons-editor-alignleft"></span></button></li>';
	
	}



	add_action( 'gdymc_adminbarbuttons_left', 'gdymc_hook_formatbutton_subscript', 110, 0 );

	function gdymc_hook_formatbutton_subscript() {

		echo '<li><button onmousedown="gdymc.editor.format( \'superscript\', \'\' ); return false;" class="gdymc_format_supscript" data-gdymc-tip="' . __( 'Superscript', 'gdy-modular-content' ) . '"><span class="dashicons dashicons-editor-bold"></span></button></li>';
	
	}



	add_action( 'gdymc_adminbarbuttons_left', 'gdymc_hook_formatbutton_superscript', 120, 0 );

	function gdymc_hook_formatbutton_superscript() {

		echo '<li><button onmousedown="gdymc.editor.format( \'subscript\', \'\' ); return false;" class="gdymc_format_subscript" data-gdymc-tip="' . __( 'Subscript', 'gdy-modular-content' ) . '"><span class="dashicons dashicons-editor-bold"></span></button></li>';
	
	}



	add_action( 'gdymc_adminbarbuttons_left', 'gdymc_hook_formatbutton_removeformat', 130, 0 );

	function gdymc_hook_formatbutton_removeformat() {

		echo '<li><button onmousedown="gdymc.editor.format( \'removeformat\', \'\' ); return false;" data-gdymc-tip="' . __( 'Remove format', 'gdy-modular-content' ) . '"><span class="dashicons dashicons-editor-removeformatting"></span></button></li>';
	
	}



		

	/**************************************************** RIGHT ADMINBAR BUTTONS ****************************************************/


	// Batch button
	
	// add_action( 'gdymc_adminbarbuttons_right', 'gdymc_hook_adminbatch', 10, 0 );

	// function gdymc_hook_adminbatch() {

	// 	echo '<li><button data-gdymc-tip="' . __( 'Edit selected modules', 'gdy-modular-content' ) . '" id="gdymc_module_batch" style="display: none"><span class="dashicons dashicons-admin-post"></span><span id="gdymc_module_batch_number"></span></button></li>';
	
	// }


	// Module button

	add_action( 'gdymc_adminbarbuttons_right', 'gdymc_hook_modulebutton', 20, 0 );

	function gdymc_hook_modulebutton() {

		global $gdymc_area;

		$class = ( gdymc_has_modules() AND $gdymc_area ) ? '' : ' class="gdymc_nomodules"';

		echo '<li><button' . $class . ' data-gdymc-tip="' . __( 'Modules (Cmd+D)', 'gdy-modular-content' ) . '" id="gdymc_showmodules"><span class="dashicons dashicons-screenoptions"></span></button></li>';
		
	}



	// Preview button

	add_action( 'gdymc_adminbarbuttons_right', 'gdymc_hook_previewmenu', 30, 0 );

	function gdymc_hook_previewmenu() {

		echo '<li class="gdymc_dropdown_trigger">';


			echo '<button data-gdymc-tip="' . __( 'View modes', 'gdy-modular-content' ) . '" id="gdymc_preview"><span class="dashicons dashicons-visibility"></span><span class="dashicons dashicons-hidden"></span></button>';


			echo '<div id="gdymc_previewmenu" class="gdymc_dropdown">';
				
				echo '<a id="gdymc_togglesoftpreview" href="#"><span class="dashicons dashicons-visibility"></span><span class="dashicons dashicons-hidden"></span>'.__('Fast preview (Cmd+E)', 'gdy-modular-content').'</a>';
							
				echo '<a id="gdymc_togglehardpreview" href="' . esc_url( add_query_arg( 'gdymc_hardpreview', '1', gdymc_current_url() ) ) . '"><span class="dashicons dashicons-external"></span>'.__('Preview (Cmd+Shift+E)', 'gdy-modular-content').'</a>';

			echo '</div>';


		echo '</li>';
	
	}



	// Backend button

	add_action( 'gdymc_adminbarbuttons_right', 'gdymc_hook_backendmenu', 40, 0 );
	add_action( 'gdymc_noarea_right', 'gdymc_hook_backendmenu', 40, 0 );
	add_action( 'gdymc_roledeny_right', 'gdymc_hook_backendmenu', 40, 0 );

	function gdymc_hook_backendmenu() {


		echo '<li class="gdymc_dropdown_trigger">';

		
			echo '<button data-gdymc-tip="' . __( 'Go to backend', 'gdy-modular-content' ) . '" id="gdymc_dashboard"><span class="dashicons dashicons-wordpress-alt"></span></button>';


			echo '<div id="gdymc_wpmenu" class="gdymc_dropdown">';
				
				echo '<a class="gdymc_WPmenu-Backend" href="' . admin_url() . '"><span class="dashicons dashicons-dashboard"></span>' . __( 'Backend', 'gdy-modular-content' ).'</a>';

				if( gdymc_object_id() ):

					$type = gdymc_object_type(); 
					$edit = ( $type == 'post' ) ? get_edit_post_link( gdymc_object_id() ) : get_edit_term_link( gdymc_object_id() );

					if( $edit ):

						echo '<a href="' . $edit . '"><span class="dashicons dashicons-edit"></span>' . __( 'Edit', 'gdy-modular-content' ) . '</a>';

					endif;

				endif;

				if( current_user_can( 'edit_theme_options' ) ) echo '<a class="gdymc_WPmenu-Customizer" href="'.admin_url( 'customize.php?url='.get_permalink() ).'"><span class="dashicons dashicons-admin-appearance"></span>'.__( 'Customizer', 'gdy-modular-content' ).'</a>';
				
				echo '<a href="' . admin_url( 'upload.php' ) . '"><span class="dashicons dashicons-admin-media"></span>' . __( 'Media', 'gdy-modular-content' ).'</a>';		
				
				echo '<a class="gdymc_WPmenu-Logout" href="'.wp_logout_url( gdymc_current_url() ).'"><span class="dashicons dashicons-lock"></span>'.__( 'Logout', 'gdy-modular-content' ).'</a>';

			echo '</div>';


		echo '</li>';
	
	}
	
	
	
	
?>