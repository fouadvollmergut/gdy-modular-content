<?php



	function optionSection( $label ) {

		echo '<div class="gdymc_options_section"><span>' . $label . '</span></div>';

	}



	
	// Shows an error message if the user is logged
	
	function optionError( $errorMessage ) {
		
		if( gdymc_logged() ):
		
			echo '<div class="gdymc_error gdymc_inside"><span class="dashicons dashicons-info"></span>' . $errorMessage . '</div>';
		
		endif;
		
	}
	
	
	
	// Checks if a gdymc-option exists or not
	
	function optionExists( $optionName, $moduleIDP = '', $objectIDP = '', $objectTypeP = '' ) {
		
		global $moduleID;
		$moduleID = ( empty( $moduleIDP ) ) ? $moduleID : $moduleIDP;
		$objectID = ( empty( $objectIDP ) ) ? gdymc_object_id() : $objectIDP;
		$objectType = ( empty( $objectTypeP ) ) ? gdymc_object_type() : $objectTypeP;
		
		if( metadata_exists( $objectType, $objectID, '_gdymc_' . $moduleID . '_option_' . $optionName ) ):
		
			return true;
		
		else:
			
			return false;
		
		endif;
	
	}
	
	
	
	// Returns a gdymc-option if its exists
	
	function optionGet( $optionName, $moduleIDP = '', $objectIDP = '', $objectTypeP = '' ) {
		
		global $moduleID;
		$moduleID = empty( $moduleIDP ) ? $moduleID : $moduleIDP;
		$objectID = empty( $objectIDP ) ? gdymc_object_id() : $objectIDP;
		$objectType = empty( $objectTypeP ) ? gdymc_object_type() : $objectTypeP;
				
		if( optionExists( $optionName, $moduleID, $objectID, $objectType ) ):
		
			return get_metadata( $objectType, $objectID, '_gdymc_' . $moduleID . '_option_' . $optionName, true );
		
		else:
		
			return null;
		
		endif;
		
	}
	
	
	
	// Shows a gdymc-option
	
	function optionShow( $optionName, $moduleIDP = '', $objectIDP = '', $objectTypeP = '' ) {
		
		echo optionGet( $optionName, $moduleIDP, $objectIDP, $objectTypeP );
		
	}
	
	
	
	// Saves a gdymc-option
	
	function optionSave( $optionName, $optionValue, $moduleIDP = '', $objectIDP = '', $objectTypeP = '' ) {
		
		global $moduleID;
		$moduleID = empty( $moduleIDP ) ? $moduleID : $moduleIDP;
		$objectID = empty( $objectIDP ) ? gdymc_object_id() : $objectIDP;
		$objectType = empty( $objectTypeP ) ? gdymc_object_type() : $objectTypeP;
			
		update_metadata( $objectType, $objectID, '_gdymc_' . $moduleID . '_option_' . $optionName, $optionValue );
		
	}
	
	

	
	
	// Generates the input for changing gdymc-options
	
	function optionInput( $optionName, $optionSettings, $moduleID ) {		
	

		
		// Settings

		$settingDefaults = array(
			
            'type' => null,
			'label' => null,
			'placeholder' => null,
			'default' => null,
            'options' => null,
            'attributes' => null	
			
        );
        
		$settings = wp_parse_args( $optionSettings, $settingDefaults );




        // Attributes

        $attributeDefaults = array(
        	'data-object' => gdymc_object_id(),
        	'data-module' => $moduleID,
        	'data-name' => $optionName,
        	'id' => 'gdymc_module_' . $moduleID . '_option_' . $optionName,
        	'class' => 'gdymc_option gdymc_option_' . strtolower( $optionSettings['type'] ),
        	'placeholder' => $settings[ 'placeholder' ],
        	'type' => $settings[ 'type' ],
        	'value' => optionGet( $optionName, $moduleID ),
        );

        $attributesArray = wp_parse_args( $settings[ 'attributes' ], $attributeDefaults );




		// Default value

		if( $attributesArray[ 'value' ] == '' AND $settings[ 'default' ] != '' ):

			$attributesArray[ 'value' ] = $settings[ 'default' ];

			optionSave( $optionName, $settings[ 'default' ], $moduleID, gdymc_object_id() );

		endif;




		// Attribute string

		$attributes = array();

		foreach( $attributesArray as $key => $value ):

			$attributes[] = $key . '="' . esc_attr( $value ) . '"'; 

		endforeach;

		$attributes = implode( ' ', $attributes );




		// Wrapper open

		if( $settings[ 'type' ] != 'hidden' ) echo '<div class="gdymc_formpart gdymc_optioncontainer gdymc_optioncontainer_' . strtolower( $settings['type'] ) . '">';

		


			// Label

			if( $settings[ 'type' ] != 'hidden' ) echo '<label for="' . esc_attr( $attributesArray[ 'id' ] ) . '">' . $settings[ 'label' ] . '</label>';

			

			// Input

			if( in_array( $settings[ 'type' ], array( 'area', 'textarea' ) ) ):
				

				echo '<textarea ' . $attributes . '>' . $attributesArray[ 'value' ] . '</textarea>';
				

			elseif( in_array( $settings[ 'type' ], array( 'select' ) ) ):
				

				echo '<select ' . $attributes . '>';
					
					if( !empty( $settings['placeholder'] ) ):
						$selected = ( $attributesArray[ 'value' ] == '' ) ? 'selected' : '';
						echo '<option value="" disabled="disabled"' . $selected . '>' . $settings['placeholder'] . '</option>';
					endif;
						
					foreach( $settings['options'] as $key => $value ):
					
						$selected = ( $attributesArray[ 'value' ] == $key ) ? ' selected' : '';
						
						echo '<option value="' . esc_attr( $key ) . '"' . $selected . '>' . $value . '</option>';
					
					endforeach;
				
				echo '</select>';


			else:

				echo '<input ' . $attributes . '>';


			endif;
		
		


		// Wrapper close

		if( $settings[ 'type' ] != 'hidden' ) echo '</div>';


		
				
	}
	
	
	

?>