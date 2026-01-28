<?php

add_action( 'admin_post_gdymc_activate_module', 'gdymc_activate_module' );

function gdymc_activate_module() {

    if( !isset( $_GET['module'] ) || empty( $_GET['module'] ) ) {
        return wp_redirect( admin_url( 'themes.php?page=gdymc-plugin-settings&tab=modules&status=error' ) );
        exit;
    }

    $module_type = urldecode( $_GET['module'] );

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

    if (!get_option( 'gdymc_inactive_modules' )) {
        add_option( 'gdymc_inactive_modules', array() );
    }

    $inactive_modules = get_option( 'gdymc_inactive_modules', array() );

    if ( ( $key = array_search( $module_type, $inactive_modules ) ) !== false ) {
        unset( $inactive_modules[ $key ] );
        update_option( 'gdymc_inactive_modules', $inactive_modules );
    }

    wp_redirect( admin_url( 'themes.php?page=gdymc-plugin-settings&tab=modules&status=activated' ) );
    exit;
}

add_action( 'admin_post_gdymc_deactivate_module', 'gdymc_deactivate_module' );

function gdymc_deactivate_module() {

    if( !isset( $_GET['module'] ) || empty( $_GET['module'] ) ) {
        return wp_redirect( admin_url( 'themes.php?page=gdymc-plugin-settings&tab=modules&status=error' ) );
        exit;
    }

    $module_type = urldecode( $_GET['module'] );

    error_log( 'Deactivating module: ' . $module_type  );

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

    if (!get_option( 'gdymc_inactive_modules' )) {
        add_option( 'gdymc_inactive_modules', array() );
    }

    $inactive_modules = get_option( 'gdymc_inactive_modules', array() );

    if ( ! in_array( $module_type, $inactive_modules ) ) {
        array_push( $inactive_modules, $module_type );
        update_option( 'gdymc_inactive_modules', $inactive_modules );
    }

    wp_redirect( admin_url( 'themes.php?page=gdymc-plugin-settings&tab=modules&status=deactivated' ) );
    exit;
}

