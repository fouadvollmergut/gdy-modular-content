<?php

add_action( 'admin_post_gdymc_activate_module', 'gdymc_activate_module' );

function gdymc_activate_module() {
    $redirect_url = isset( $_GET['redirect'] ) ? urldecode( $_GET['redirect'] ) : admin_url( 'themes.php?page=gdymc-plugin-settings&tab=modules' );

    if( !isset( $_GET['gdymc_module_type'] ) || empty( $_GET['gdymc_module_type'] ) ) {
        return wp_redirect( $redirect_url . '&status=error' );
        exit;
    }

    $module_type = urldecode( $_GET['gdymc_module_type'] );
    $gdymc_modules = gdymc_get_modules();
    $module_found = false;

    foreach ( $gdymc_modules as $module ) {
        if ( $module->type === $module_type ) {
            $module_found = true;
            break;
        }
    }

    if ( ! $module_found ) {
        return wp_redirect( $redirect_url . '&status=not-found' );
        exit;
    }

    if (!get_option( 'gdymc_inactive_modules' )) {
        add_option( 'gdymc_inactive_modules', array() );
    }

    $inactive_modules = get_option( 'gdymc_inactive_modules', array() );

    if ( ( $key = array_search( $module_type, $inactive_modules ) ) !== false ) {
        unset( $inactive_modules[ $key ] );
        update_option( 'gdymc_inactive_modules', $inactive_modules );
    }

    wp_redirect( $redirect_url . '&status=activated' );
    exit;
}



add_action( 'admin_post_gdymc_deactivate_module', 'gdymc_deactivate_module' );

function gdymc_deactivate_module() {
    $redirect_url = isset( $_GET['redirect'] ) ? urldecode( $_GET['redirect'] ) : admin_url( 'themes.php?page=gdymc-plugin-settings&tab=modules' );

    if( !isset( $_GET['gdymc_module_type'] ) || empty( $_GET['gdymc_module_type'] ) ) {
        return wp_redirect( $redirect_url . '&status=error' );
        exit;
    }

    $module_type = urldecode( $_GET['gdymc_module_type'] );
    $gdymc_modules = gdymc_get_modules();
    $module_found = false;

    foreach ( $gdymc_modules as $module ) {
        if ( $module->type === $module_type ) {
            $module_found = true;
            break;
        }
    }

    if ( ! $module_found ) {
        return wp_redirect( $redirect_url . '&status=not-found' );
        exit;
    }

    if (!get_option( 'gdymc_inactive_modules' )) {
        add_option( 'gdymc_inactive_modules', array() );
    }

    $inactive_modules = get_option( 'gdymc_inactive_modules', array() );

    if ( ! in_array( $module_type, $inactive_modules ) ) {
        array_push( $inactive_modules, $module_type );
        update_option( 'gdymc_inactive_modules', $inactive_modules );
    }

    wp_redirect( $redirect_url . '&status=deactivated' );
    exit;
}



add_action( 'admin_post_gdymc_save_module', 'gdymc_save_module' );

function gdymc_save_module() {

    if( !isset( $_POST['gdymc_module_type'] ) || empty( $_POST['gdymc_module_type'] ) || !isset( $_POST['gdymc_module_name'] ) || empty( $_POST['gdymc_module_name'] ) ) {
        return wp_redirect( admin_url( 'themes.php?page=gdymc-plugin-settings&tab=modules&status=error' ) );
        exit;
    }

    $module_type = sanitize_text_field( $_POST['gdymc_module_type'] );
    $module_name = sanitize_text_field( $_POST['gdymc_module_name'] );

    $gdymc_modules = gdymc_get_modules();

    $module_found = false;

    foreach ( $gdymc_modules as $module ) {
        if ( $module->type === $module_type ) {
            $module_found = true;
            break;
        }
    }

    if ( ! $module_found ) {
        return wp_redirect( admin_url( 'themes.php?page=gdymc-plugin-settings&tab=modules&status=not-found' ) );
        exit;
    }

    // Module name is stored as an option to allow renaming without affecting the module's internal type identifier, which is used for loading and other logic. The option name is prefixed with the module type to ensure uniqueness and easy retrieval.

    if (!get_option( 'gdymc_module_' . $module_type . '_name' )) {
        add_option( 'gdymc_module_' . $module_type . '_name', $module->title );
    }

    update_option( 'gdymc_module_' . $module_type . '_name', $module_name );

    wp_redirect( admin_url( 'themes.php?page=gdymc-plugin-settings&gdymc_module_type=' . urlencode( $module_type ) . '&status=saved' ) );
    exit;
}
