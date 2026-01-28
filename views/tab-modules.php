<h2 class="title"><?php echo __( 'Modules', 'gdy-modular-content' ); ?></h2>
<p><?php echo __( 'Here you can see all available modules. You can activate them to make them available in the module area.', 'gdy-modular-content' ); ?></p>

<table class="wp-list-table widefat fixed striped table-view-list pages">
  <thead>
    <tr>
      <th scope="col" id="module" class="manage-column column-module column-primary sorted desc" abbr="Module">
        <a href="#"><span><?php echo __( 'Modules', 'gdy-modular-content' ); ?></span></a>
      </th>
      <th scope="col" id="status" class="manage-column column-status sortable asc" abbr="Status" style="width: 10em;">
        <a href="#"><span><?php echo __( 'Status', 'gdy-modular-content' ); ?></span></a>
      </th>
    </tr>
  </thead>

  <tbody id="the-list">
    <?php 
      $gdymc_modules = gdymc_get_modules();
      $inactive_modules = get_option( 'gdymc_inactive_modules', array() );
    ?>

    <?php if ( empty( $gdymc_modules ) ) : ?>
      <tr>
        <td colspan="2">
          <?php esc_html_e( 'No modules found.', 'gdy-modular-content' ); ?>
        </td>
      </tr>
    <?php else : ?>
      <?php foreach ( $gdymc_modules as $module ) : ?>
        <tr>
          <td class="module column-module column-primary" data-colname="Module">
            <strong><?php echo strtoupper( esc_html( $module->title ) ); ?></strong>
            <div class="row-actions visible">
              <span class="status">
                <?php if ( in_array( $module->type, $inactive_modules ) ) : ?>
                  <a href="<?php echo esc_url( admin_url( 'admin-post.php?action=gdymc_activate_module&module=' . urlencode( $module->type ) ) ); ?>">
                    <?php echo __( 'Activate', 'gdy-modular-content' ); ?>
                  </a>
                <?php else : ?>
                  <a href="<?php echo esc_url( admin_url( 'admin-post.php?action=gdymc_deactivate_module&module=' . urlencode( $module->type ) ) ); ?>">
                    <?php echo __( 'Deactivate', 'gdy-modular-content' ); ?>
                  </a>
                <?php endif; ?>
              </span>
            </div>
          </td>
          <td class="author column-author" data-colname="Author">
            <?php if ( in_array( $module->type, $inactive_modules ) ) : ?>
              <span style="height: .8em; aspect-ratio: 1; display: inline-block; background: #a00; border-radius: 50%; vertical-align: baseline;"></span>&nbsp;<span><?php echo __( 'Inactive', 'gdy-modular-content' ); ?></span>
            <?php else : ?>
              <span style="height: .8em; aspect-ratio: 1; display: inline-block; background: #0a0; border-radius: 50%; vertical-align: baseline;"></span>&nbsp;<span><?php echo __( 'Active', 'gdy-modular-content' ); ?></span>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php endif; ?>
  </tbody>

  <tfoot>
    <tr></tr>
  </tfoot>
</table>