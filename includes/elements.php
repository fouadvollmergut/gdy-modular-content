<?php
	

	



	/**************************** EXIT HARDPREVIEW ****************************/ 

	add_action( 'wp_footer', 'gdymc_exit_hardpreview' );
	
	function gdymc_exit_hardpreview() {
		
		if( gdymc_hardpreview() ):

			echo '<a id="gdymc_exit_hardpreview" href="' . add_query_arg( 'gdymc_hardpreview', '0', gdymc_current_url() ) . '">' . __( 'Exit preview', 'gdy-modular-content' ) . '<span class="dashicons dashicons-migrate"></span></a>';

		endif;

	}



	/**************************** OVERLAYS ****************************/ 
	
	add_action( 'wp_footer', 'gdymc_helpers' );
	
	function gdymc_helpers() {
	
		if( gdymc_logged() ):

			// the overlay
			echo '<div id="gdymc_overlay_shadow" class="gdymc_inside"></div>';
			
			// the crop window
			echo '<table id="gdymc_croptable" class="gdymc_inside"><tr><td id="gdymc_cropholder"></td></tr></table>';

		endif;

	}


	/**************************** DROPZONE ****************************/ 
	
	add_action( 'wp_footer', 'gdymc_dropzone' );
	
	function gdymc_dropzone() {

		if( gdymc_logged() ):

			echo '<div id="gdymc_dropzone_overlay" class="gdymc_inside"><div>' . __( 'Drop files here', 'gdy-modular-content' ) . '</div></div>';

			echo '<div id="gdymc_dropzone_uploader_container" class="gdymc_inside">';
			echo '<div id="gdymc_dropzone_uploader" class="gdymc_inside">';

				echo '<div id="gdymc_dropzone_header" class="gdymc_fix">';
					echo '<div id="gdymc_dropzone_headline" class="gdymc_left">' . __( 'Uploader', 'gdy-modular-content' ) . '</div>';
					echo '<a class="gdymc_button gdymc_right" href="' . get_admin_url( '', 'upload.php' ) . '" target="blank">' . __( 'Open media library', 'gdy-modular-content' ) . '</a>';
					echo '<button class="gdymc_button gdymc_right" id="gdymc_dropzone_trigger">' . __( 'Upload files', 'gdy-modular-content' ) . '</button>';
				echo '</div>';

				echo '<div id="gdymc_dropzone_progressHolder"><div id="gdymc_dropzone_progressBar"></div></div>';

				echo '<div id="gdymc_dropzone_preview_shadow"><div id="gdymc_dropzone_preview"></div></div>';
			
			echo '</div><!-- #gdymc_dropzone_uploader -->';
			echo '</div><!-- #gdymc_dropzone_uploader_container -->';

		endif;
	}
	

	
	/**************************** ADMIN BAR ****************************/ 
	
	add_action( 'wp_footer', 'gdymc_adminbar' );
	
	function gdymc_adminbar() {


		if( gdymc_logged() ): 

			

			// Adminbar

			echo '<div id="gdymc_adminbar" class="gdymc_inside gdymc_adminbar">';
				

				echo '<ul id="gdymc_adminbarbuttons_left" class="gdymc_left gdymc_fix">';

					do_action( 'gdymc_adminbarbuttons_left' );

				echo '</ul><!-- #gdymc_adminbarbuttons_left -->';



				echo '<ul id="gdymc_adminbarbuttons_right" class="gdymc_right gdymc_fix">';

					do_action( 'gdymc_adminbarbuttons_right' );

				echo '</ul><!-- #gdymc_adminbarbuttons_right -->';
			
				
			echo '</div><!-- #gdymc_adminbar -->';
		
			


			
			// Module list
	
			require_once( GDYMC_BASE_PATH . 'includes/modulelist.php' );
			
			
		
		endif;
		
		
	}

	
	

?>