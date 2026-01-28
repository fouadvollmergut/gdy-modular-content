<div class="wrap">
  <h1 class="wp-heading-inline"><?php esc_html_e( 'GDY Modular Content Settings', 'gdy-modular-content' ); ?></h1>
  <hr class="wp-header-end">

  <!-- Setup Form -->
  <?php if (isset($_GET['tab']) && $_GET['tab'] === 'setup') : ?>
    <nav class="nav-tab-wrapper wp-clearfix" aria-label="Secondary menu">
      <a href="/wp-admin/themes.php?page=gdymc-plugin-settings&tab=modules" class="nav-tab">
        <?php esc_html_e( 'Modules', 'gdy-modular-content' ); ?>
      </a>
      <a href="/wp-admin/themes.php?page=gdymc-plugin-settings&tab=setup" class="nav-tab nav-tab-active" aria-current="page">
        <?php esc_html_e( 'Setup', 'gdy-modular-content' ); ?>
      </a>
    </nav>

    <?php include GDYMC_BASE_PATH . '/views/tab-setup.php'; ?>
  <?php else : ?>
    <nav class="nav-tab-wrapper wp-clearfix" aria-label="Secondary menu">
      <a href="/wp-admin/themes.php?page=gdymc-plugin-settings&tab=modules" class="nav-tab nav-tab-active" aria-current="page">
        <?php esc_html_e( 'Modules', 'gdy-modular-content' ); ?>
      </a>
      <a href="/wp-admin/themes.php?page=gdymc-plugin-settings&tab=setup" class="nav-tab">
        <?php esc_html_e( 'Setup', 'gdy-modular-content' ); ?>
      </a>
    </nav>

    <?php include GDYMC_BASE_PATH . '/views/tab-modules.php'; ?>
  <?php endif; ?>
</div>