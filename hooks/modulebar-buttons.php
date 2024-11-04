<?php
	




	// Left modulebar buttons

	add_action( 'gdymc_modulebarbuttons_left', 'gdymc_hook_moduleoptions', 10, 1 );

	function gdymc_hook_moduleoptions( $module ) {

		echo '<li><button data-gdymc-tip="' . __( 'Module options', 'gdy-modular-content' ) . '" class="gdymc_moduleoptionsbutton"><span class="dashicons dashicons-admin-generic"></span></button></li>';
		
	}



	add_action( 'gdymc_modulebarbuttons_left', 'gdymc_hook_modulemoveup', 20, 1 );

	function gdymc_hook_modulemoveup( $module ) {

		echo '<li><button data-gdymc-tip="' . __( 'Move module up', 'gdy-modular-content' ) . '" class="gdymc_moduleposition_moveup"><span class="dashicons dashicons-arrow-up-alt2"></span></button></li>';
	
	}



	add_action( 'gdymc_modulebarbuttons_left', 'gdymc_hook_modulemovedown', 30, 1 );

	function gdymc_hook_modulemovedown( $module ) {

		echo '<li><button data-gdymc-tip="' . __( 'Move module down', 'gdy-modular-content' ) . '" class="gdymc_moduleposition_movedown"><span class="dashicons dashicons-arrow-down-alt2"></span></button></li>';
	
	}







	// Right modulebar buttons

	// add_action( 'gdymc_modulebarbuttons_right', 'gdymc_hook_modulebatch', 10, 1 );

	// function gdymc_hook_modulebatch( $module ) {

	// 	echo '<li><button data-gdymc-tip="' . __( 'Select this module', 'gdy-modular-content' ) . '" class="gdymc_select_module"><span class="dashicons dashicons-admin-post"></span></button></li>';
	
	// }


	add_action( 'gdymc_modulebarbuttons_right', 'gdymc_hook_moduledelete', 20, 1 );

	function gdymc_hook_moduledelete( $module ) {

		echo '<li><button data-gdymc-tip="' . __( 'Delete this module', 'gdy-modular-content' ) . '" class="gdymc_delete_module"><span class="dashicons dashicons-trash"></span></button></li>';
	
	}
	
	

	



	// Module option tab: defaults

	add_action( 'gdymc_module_options_defaults', function ( $module ) {
		

		$gdymc_modules = gdymc_get_modules();

		echo '<div class="gdymc_formpart gdymc_optioncontainer">';

			echo '<label class="gdymc_optionlabel" for="gdymc_module_' . $module->id . '_option_type">' . __( 'Module type', 'gdy-modular-content' ) . '</label>';

			if( $gdymc_modules ):

				$select = '<select id="gdymc_module_' . $module->id . '_option_type" class="gdymc_option_nosave gdymc_change_single_moduletype">';
				foreach( $gdymc_modules as $handler ):

					if( $module->type == $handler->type ):
						$select .= '<option value="' . $handler->type . '" selected>' . $handler->type . '</option>';
					else:
						$select .= '<option value="' . $handler->type . '">' . $handler->type . '</option>';
					endif;

				endforeach;
				$select .= '</select>';

				echo $select;

			else:

				echo '<input id="gdymc_module_' . $module->id . '_option_type" class="gdymc_option_nosave gdymc_change_single_moduletype" value="' . $module->id . '" readonly />';
				
			endif;

		echo '</div>';


		echo '<div class="gdymc_formpart gdymc_optioncontainer">';

			echo '<label class="gdymc_optionlabel" for="gdymc_module_' . $module->id . '_option_moduleid">' . __( 'Module ID', 'gdy-modular-content' ) . '</label>';

			echo '<input id="gdymc_module_' . $module->id . '_option_moduleid" class="gdymc_option_nosave gdymc_option-text" value="' . $module->id . '" readonly />';

		echo '</div>';


		echo '<div class="gdymc_formpart gdymc_optioncontainer">';

			echo '<label class="gdymc_optionlabel" for="gdymc_module_' . $module->id . '_option_elementid">' . __( 'Element ID', 'gdy-modular-content' ) . '</label>';

			echo '<input id="gdymc_module_' . $module->id . '_option_elementid" class="gdymc_option_nosave gdymc_option-text" value="#gdymc_module_' . $module->id . '" readonly />';

		echo '</div>';

		echo '<div class="gdymc_formpart gdymc_optioncontainer">';

			echo '<label class="gdymc_optionlabel" for="gdymc_module_' . $module->id . '_option_permalink">' . __( 'Permalink', 'gdy-modular-content' ) . '</label>';

			echo '<input id="gdymc_module_' . $module->id . '_option_permalink" class="gdymc_option_nosave gdymc_option-text" value="' . get_the_permalink() . '#gdymc_module_' . $module->id . '" readonly />';

		echo '</div>';

		echo '<span class="gdymc_hint">' . __( 'You can use the permalink to jump directly to that module', 'gdy-modular-content' ) . '</span>';


	}, 10, 1 );



	// Module option tab: visibility
	
	add_action( 'gdymc_module_options_visibility', function ( $module ) {
		
		
		optionInput( 'visibility', array(
	
			'type' => 'select',
			'options' => array(
										
				'1' => __('Visible', 'gdy-modular-content'),
				'0' => __('Invisible', 'gdy-modular-content'),
			
			),
			'default' => '1',
			'label' => __('Module visibility', 'gdy-modular-content'),
		
		), $module->id );


		optionInput( 'visibility_timer', array(
	
			'type' => 'select',
			'options' => array(
										
				'1' => __('Activated', 'gdy-modular-content'),
				'0' => __('Deactivated', 'gdy-modular-content'),
			
			),
			'default' => '0',
			'label' => __('Switch delayed', 'gdy-modular-content'),
		
		), $module->id );


		optionInput( 'visibility_switch', array(
	
			'type' => 'text',
			'label' => __('Date', 'gdy-modular-content'),
			'default' => date_i18n( 'Y-m-d h:i' ),
		
		), $module->id );

		echo '<span class="gdymc_hint">'.__('Enter the date in the following format "yyyy-mm-dd hh:mm"', 'gdy-modular-content').'</span>';



		
		
	}, 10, 1 );
	
			
	
	
	
?>