<?php

// Activate Module

function gdymc_activate_module( $module_type, $redirect_url = '' ) {
    if ( ! gdymc_module_exists( $module_type ) ) {
        return wp_redirect( $redirect_url . '&status=not-found' );
        exit;
    }

    if (!get_option( 'gdymc_module_' . $module_type . '_status' )) {
        add_option( 'gdymc_module_' . $module_type . '_status', 'ACTIVE' );
    }

    update_option( 'gdymc_module_' . $module_type . '_status', 'ACTIVE' );
}

add_action( 'admin_post_gdymc_activate_module', 'gdymc_activate_module_action' );

function gdymc_activate_module_action() {
    $redirect_url = isset( $_GET['redirect'] ) ? urldecode( $_GET['redirect'] ) : admin_url( 'themes.php?page=gdymc-plugin-settings&tab=modules' );

    if( !isset( $_GET['gdymc_module_type'] ) || empty( $_GET['gdymc_module_type'] ) ) {
        return wp_redirect( $redirect_url . '&status=error' );
        exit;
    }

    $module_type = urldecode( $_GET['gdymc_module_type'] );

    gdymc_activate_module( $module_type, $redirect_url );

    wp_redirect( $redirect_url . '&status=activated' );
    exit;
}



// Deactivate Module

function gdymc_deactivate_module( $module_type, $redirect_url = '' ) {
    if ( ! gdymc_module_exists( $module_type ) ) {
        return wp_redirect( $redirect_url . '&status=not-found' );
        exit;
    }

    if (!get_option( 'gdymc_module_' . $module_type . '_status' )) {
        add_option( 'gdymc_module_' . $module_type . '_status', 'ACTIVE' );
    }

    update_option( 'gdymc_module_' . $module_type . '_status', 'INACTIVE' );
}

add_action( 'admin_post_gdymc_deactivate_module', 'gdymc_deactivate_module_action' );

function gdymc_deactivate_module_action() {
    $redirect_url = isset( $_GET['redirect'] ) ? urldecode( $_GET['redirect'] ) : admin_url( 'themes.php?page=gdymc-plugin-settings&tab=modules' );

    if( !isset( $_GET['gdymc_module_type'] ) || empty( $_GET['gdymc_module_type'] ) ) {
        return wp_redirect( $redirect_url . '&status=error' );
        exit;
    }

    $module_type = urldecode( $_GET['gdymc_module_type'] );

    gdymc_deactivate_module( $module_type, $redirect_url );

    wp_redirect( $redirect_url . '&status=deactivated' );
    exit;
}



// Rename Module

function gdymc_rename_module( $module_type, $new_name, $redirect_url = '' ) {
    if ( !gdymc_module_exists( $module_type ) ) {
        return wp_redirect( $redirect_url . '&status=not-found' );
        exit;
    }

    if ( !get_option( 'gdymc_module_' . $module_type . '_name' ) ) {
        $module = gdymc_get_module( $module_type );
        add_option( 'gdymc_module_' . $module_type . '_name', $module->title );
    }

    update_option( 'gdymc_module_' . $module_type . '_name', $new_name );
}

add_action( 'admin_post_gdymc_rename_module', 'gdymc_rename_module_action' );

function gdymc_rename_module_action() {
    $redirect_url = isset( $_GET['redirect'] ) ? urldecode( $_GET['redirect'] ) : admin_url( 'themes.php?page=gdymc-plugin-settings' );

    if( !isset( $_POST['gdymc_module_type'] ) || empty( $_POST['gdymc_module_type'] ) || !isset( $_POST['gdymc_module_name'] ) || empty( $_POST['gdymc_module_name'] ) ) {
        return wp_redirect( $redirect_url . '&status=error' );
        exit;
    }

    $module_type = sanitize_text_field( $_POST['gdymc_module_type'] );
    $new_name = sanitize_text_field( $_POST['gdymc_module_name'] );

    gdymc_rename_module( $module_type, $new_name, $redirect_url );

    wp_redirect( $redirect_url . '&status=renamed' );
    exit;
}


// Save Module

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

    gdymc_rename_module( $module_type, $module_name );

    wp_redirect( admin_url( 'themes.php?page=gdymc-plugin-settings&gdymc_module_type=' . urlencode( $module_type ) . '&status=saved' ) );
    exit;
}
