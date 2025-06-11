<?php

  function areaCreate() {

    // Global variables
    global $gdymc_module;
    global $gdymc_area;

    // Only create area if it doesn't exist already
    if( $gdymc_area ):

      if( WP_DEBUG ): trigger_error( 'areaCreate was already called' ); endif;

    else:

      if( !gdymc_object_type() ):

        if( WP_DEBUG ): trigger_error( 'areaCreate ist not supported on this object type' ); endif;

      else:

        // Area exists
        $gdymc_area = true;

        // Current object information
        $gdymc_object_id = gdymc_object_id();
        $gdymc_object_type = gdymc_object_type();

        // Get placed modules for this object
        $moduleArray = gdymc_module_array( $gdymc_object_id, $gdymc_object_type );


        // Check-Loop
        if( count( $moduleArray ) > 0 ):

          foreach( $moduleArray as $key => $value ):

            if( !metadata_exists( $gdymc_object_type, $gdymc_object_id, '_gdymc_' . $value . '_type' ) ):

              if( ( $key = array_search( $value, $moduleArray ) ) !== false ):

                unset( $moduleArray[ $key ] );

              endif;

            endif;

          endforeach;

        endif;


        // Area container: start
        do_action( 'gdymc_area_before', $moduleArray );

        $class = apply_filters( 'gdymc_area_class', array( 'gdymc_area' ) );
        echo '<div class="' . implode( ' ', $class ) . '">';

        do_action( 'gdymc_areainner_before', $moduleArray );


        // Has modules
        if( count( $moduleArray ) == 0 ):

          // No modules
          do_action( 'gdymc_error_area_nomodules' );

        else:

          $moduleCount = 0; 

          // Iterate through module array
          foreach( $moduleArray as $key => $id ): $moduleCount++;

            // Open module
            $gdymc_module = new GDYMC_MODULE( $id, $gdymc_object_id, $gdymc_object_type );

            // If module is visible
            if( $gdymc_module->is_visible() OR gdymc_logged() ):

              // Hook: Before module
              do_action( 'gdymc_module_before', $gdymc_module );

              // Module container start
              echo '<div ' . $gdymc_module->get_attributes() . '>';

              // Module settings (if logged)
              if( gdymc_logged() AND current_user_can( 'edit_posts', gdymc_object_id() ) ):

                echo '<div class="gdymc_overlay_module gdymc_overlay_window gdymc_inside gdymc_tabs_container" style="display: none;">';

                echo '<div class="gdymc_overlay_head"><div class="gdymc_overlay_head_inner">';

                echo '<button class="gdymc_overlay_close"></button>';

                echo '<div class="gdymc_overlay_title">' . __('Module options', 'gdy-modular-content') . '</div>';


                // Get option areas (tabs and tab content)
                $option_tabs = apply_filters( 'gdymc_module_options', array(

                  'defaults' => __( 'Defaults', 'gdy-modular-content' ),
                  'visibility' => __( 'Visibility', 'gdy-modular-content' ),
                  'settings' => __( 'Settings', 'gdy-modular-content' )

                ), $gdymc_module );


                // Check areas for content (empty ones are not visible)
                $bufferedAreas = array();

                foreach( $option_tabs as $key => $value ):

                  ob_start();

                  do_action( 'gdymc_module_options_' . $key, $gdymc_module );

                  $handler = ob_get_clean();

                  if( !empty( $handler ) ) $bufferedAreas[ $key ] = $handler;

                endforeach;


                // Build tabs
                echo '<div class="gdymc_tabs_navigation">';

                  $i = 0; foreach( $option_tabs as $key => $value ): if( array_key_exists( $key, $bufferedAreas ) ):
                    
                    $class = ( ++$i == 1 ) ? 'gdymc_tabs_button gdymc_active' : 'gdymc_tabs_button';

                    echo '<button class="' . $class . '" data-tab="' . $key . '">' . $value . '</button>';

                  endif; endforeach;

                echo '</div>'; // .gdymc_tabs_navigation

                echo '</div></div>';


                // Build areas
                echo '<div class="gdymc_overlay_content">';
                  echo '<div class="gdymc_overlay_content_inner">';

                    $i = 0; foreach( $bufferedAreas as $key => $value ):

                      $class = ( ++$i == 1 ) ? 'gdymc_tabs_content gdymc_active' : 'gdymc_tabs_content';

                      echo '<div class="' . $class . '" data-tab="' . $key . '">';

                        echo $value;

                      echo '</div>'; // .gdymc_tabs_content

                    endforeach;

                  echo '</div>'; // .gdymc_overlayInner
                echo '</div>'; // .gdymc_overlayContent


                echo '<div class="gdymc_overlay_foot">';
                  echo '<div class="gdymc_overlay_foot_inner gdymc_fix">';

                  echo '<div class="gdymc_left">';
                    echo '<button class="gdymc_save gdymc_button">'.__('Save', 'gdy-modular-content').'</button>';
                  echo '</div>';

                  echo '<div class="gdymc_right">';
                    echo '<button class="gdymc_button_delete gdymc_delete_module gdymc_delete_link">'.__('Delete Permanently', 'gdy-modular-content').'</button>';
                  echo '</div>';

                  echo '</div>';
                echo '</div>';

                echo '</div>';


                // Module bar
                do_action( 'gdymc_modulebar_before', $gdymc_module );

                echo '<div class="gdymc_inside gdymc_modulebar gdymc_fix ">';

                  echo '<ul class="gdymc_modulebarbuttons_left gdymc_left gdymc_fix">';

                    do_action( 'gdymc_modulebarbuttons_left', $gdymc_module );

                  echo '</ul>'; // .gdymc_modulebarbuttons_left


                  echo '<ul class="gdymc_modulebarbuttons_right gdymc_right gdymc_fix">';

                    do_action( 'gdymc_modulebarbuttons_right', $gdymc_module );

                  echo '</ul>'; // .gdymc_modulebarbuttons_right

                echo '</div>'; // .gdymc_modulebar

                do_action( 'gdymc_modulebar_after', $gdymc_module );

              endif;


              // Module inner start
              echo '<div class="gdymc_moduleinner">';

              // Hook: Before module content
              do_action( 'gdymc_module_before_content', $gdymc_module );
              /* DEPRECATED */ do_action( 'gdymc_module_' . $gdymc_module->type . '_before_content', $gdymc_module );

              // Include module if possible
              if( !file_exists( $gdymc_module->path ) OR empty( $gdymc_module->path ) ):

                // Missing folder
                do_action( 'gdymc_error_module_missing', $gdymc_module );

              elseif( !file_exists( $gdymc_module->file ) OR empty( $gdymc_module->file ) ):

                // Missing index.php
                do_action( 'gdymc_error_module_incomplete', $gdymc_module );

              else:
                
                // Include the module file
                include( $gdymc_module->file );

              endif;

              // Hook: Before after content
              do_action( 'gdymc_module_after_content', $gdymc_module );
              /* DEPRECATED */ do_action( 'gdymc_module_' . $gdymc_module->type . '_after_content', $gdymc_module );

              // Module inner end
              echo '</div>'; // .gdymc_moduleinner

              // Close module container
              echo '</div>'; // .gdymc_module

              // Hook: After module
              do_action( 'gdymc_module_after', $gdymc_module );
              /* DEPRECATED */ do_action( 'gdymc_module_' . $gdymc_module->type . '_after', $gdymc_module );

            endif;


            // Save module contents
            update_metadata( $gdymc_object_type, $gdymc_object_id, '_gdymc_' . $gdymc_module->id . '_content', $gdymc_module->content_string() );

            // Close module
            $gdymc_module = false;

          endforeach;

          // Save module list
          update_metadata( $gdymc_object_type, $gdymc_object_id, '_gdymc_modulelist', json_encode( $moduleArray ) );

        endif;


        // Area inner: end
        do_action( 'gdymc_areainner_after', $moduleArray );

        echo '</div>'; // .gdymc_area

        // Area container: end
        do_action( 'gdymc_area_after', $moduleArray );

      endif;

    endif;

  }

?>