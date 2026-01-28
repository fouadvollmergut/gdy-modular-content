<?php

add_action( 'admin_post_gdymc_run_setup', 'gdymc_run_setup' );

function gdymc_run_setup() {
    $front_page_id = get_option('page_on_front');

    if ($front_page_id) {
        return wp_redirect( admin_url( 'themes.php?page=gdymc-plugin-settings&tab=setup&status=already-exists' ) );
        exit;
    }

    $new_page = array(
      'post_title'    => wp_strip_all_tags( 'Startseite' ),
      'post_content'  => '[gdymc_area]',
      'post_status'   => 'publish',
      'post_author'   => 1,
      'post_type'     => 'page',
    );

    $new_page_id = wp_insert_post( $new_page );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $new_page_id );

    wp_redirect( admin_url( 'themes.php?page=gdymc-plugin-settings&tab=setup&status=success' ) );
    exit;
}
