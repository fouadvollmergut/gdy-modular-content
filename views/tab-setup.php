<?php
  $front_page_id = get_option('page_on_front');
?>


<?php if ($front_page_id): ?>
  <h2 class="title"><?php esc_html_e( 'Setup', 'gdy-modular-content' ); ?></h2>
  <p>
    <b><?php esc_html_e( 'Yay! A frontpage is already set up.', 'gdy-modular-content' ); ?></b><br/>
    <?php esc_html_e( 'If you can\'t place modules within it, please paste the shortcode [gdymc_area] in the page content.', 'gdy-modular-content' ); ?>
  </p>    
  
<?php else: ?>
  <h2 class="title"><?php esc_html_e( 'Setup', 'gdy-modular-content' ); ?></h2>
  <p><?php esc_html_e( 'Here you can set up a new page as your homepage with a modular content area.', 'gdy-modular-content' ); ?></p>

  <?php if ( isset( $_GET['status'] ) && $_GET['status'] === 'success' ) : ?>
    <div class="notice notice-success is-dismissible">
      <p><?php echo sprintf( '<b>' . esc_html__( 'Setup completed successfully!', 'gdy-modular-content' ) . '</b>&nbsp;' . esc_html__( 'Your new page has been created. And you can edit it now. ', 'gdy-modular-content' ) . '<a href="' . home_url() . '">' . esc_html__( 'Edit page now', 'gdy-modular-content' ) . '</a>'); ?></p>
    </div>
  <?php elseif ( isset( $_GET['status'] ) && $_GET['status'] === 'error' ) : ?>
    <div class="notice notice-error is-dismissible">
      <p><?php esc_html_e( 'There was an error during setup. Please try again.', 'gdy-modular-content' ); ?></p>
    </div>
  <?php endif; ?>

  <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
    <input type="hidden" name="action" value="gdymc_run_setup">
    <input type="submit" class="button button-primary" value="<?php esc_attr_e( 'Start Setup', 'gdy-modular-content' ); ?>" />
  </form>
<?php endif; ?>