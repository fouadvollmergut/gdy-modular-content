<?php


	/******************************* ADMIN SETTINGS PAGE *******************************/

	add_action( 'admin_menu', 'gdymc_register_settings_page' );

	function gdymc_register_settings_page() {

		add_options_page(
			__( 'GDY Modular Content Settings', 'gdy-modular-content' ),
			__( 'GDY Modular Content', 'gdy-modular-content' ),
			'manage_options',
			'gdymc-settings',
			'gdymc_render_settings_page'
		);

	}


	/******************************* REGISTER SETTINGS *******************************/

	add_action( 'admin_init', 'gdymc_register_settings' );

	function gdymc_register_settings() {

		register_setting( 'gdymc_settings_group', 'gdymc_openai_api_key', array(
			'sanitize_callback' => 'sanitize_text_field',
		) );

		add_settings_section(
			'gdymc_openai_section',
			__( 'OpenAI Translation', 'gdy-modular-content' ),
			'gdymc_render_openai_section_description',
			'gdymc-settings'
		);

		add_settings_field(
			'gdymc_openai_api_key',
			__( 'OpenAI API Key', 'gdy-modular-content' ),
			'gdymc_render_openai_api_key_field',
			'gdymc-settings',
			'gdymc_openai_section'
		);

	}


	function gdymc_render_openai_section_description() {

		echo '<p>' . esc_html__( 'Configure your OpenAI API key to enable automatic translation of text, button group, and table content.', 'gdy-modular-content' ) . '</p>';

	}


	function gdymc_render_openai_api_key_field() {

		$value = get_option( 'gdymc_openai_api_key', '' );

		echo '<input type="password" name="gdymc_openai_api_key" id="gdymc_openai_api_key" value="' . esc_attr( $value ) . '" class="regular-text" autocomplete="off" />';
		echo '<p class="description">' . esc_html__( 'Enter your OpenAI API key. You can find or create your API key at https://platform.openai.com/account/api-keys', 'gdy-modular-content' ) . '</p>';

	}


	/******************************* RENDER SETTINGS PAGE *******************************/

	function gdymc_render_settings_page() {

		if ( !current_user_can( 'manage_options' ) ) return;

		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
				<?php
				settings_fields( 'gdymc_settings_group' );
				do_settings_sections( 'gdymc-settings' );
				submit_button( __( 'Save Settings', 'gdy-modular-content' ) );
				?>
			</form>
		</div>
		<?php

	}

?>
