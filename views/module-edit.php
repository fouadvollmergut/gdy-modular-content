<?php 
  $module_type = isset($_GET['gdymc_module_type']) ? urldecode($_GET['gdymc_module_type']) : '';
  $module = gdymc_get_module( $module_type );
?>

<hr>

<form action="<?php echo esc_url( admin_url( 'admin-post.php?action=gdymc_save_module' ) ); ?>" method="post" id="gdymc_module_edit">
  <input type="hidden" name="gdymc_module_type" value="<?php echo esc_attr( $module_type ); ?>">
  <div id="poststuff">
    <div id="post-body" class="metabox-holder columns-2">
      <div id="post-body-content">
        <div id="titlediv">
          <input type="text" name="gdymc_module_name" size="30" id="title" value="<?php echo esc_attr( $module->name ); ?>" />
          <div class="inside" style="margin-top: 5px; padding: 0px 10px;"><strong><?php echo __( 'Module Type: ', 'gdy-modular-content' ); ?></strong><?php echo esc_html( $module_type ); ?></div>
        </div>
      </div>
      <div id="postbox-container-1" class="postbox-container">
        <div id="side-sortables" class="meta-box-sortables ui-sortable">
          <div id="modulethumbdiv" class="postbox" style="padding: 10px;">
            <img src="<?php echo esc_url( $module->thumbURL ); ?>" alt="<?php echo esc_attr( $module->name ); ?>" style="width: 258px; height: auto;">
          </div>

          <div id="submitdiv" class="postbox">
            <div class="postbox-header">
              <h2 class="hndle ui-sortable-handle">
                <span><?php echo __( 'Save', 'gdy-modular-content' ); ?></span>
              </h2>
            </div>

            <div class="inside">
              <div id="submitpost" class="submitbox">
                <div id="minor-publishing">
                  <div id="misc-publishing-actions">
                    <div id="visibility" class="misc-pub-section misc-pub-visibility">
                      <span id="post-visibility-display">
                        <?php if ( $module->status === 'INACTIVE' ) : ?>
                          <?php echo __( 'Inactive', 'gdy-modular-content' ); ?> | 
                          <a href="<?php echo esc_url( admin_url( 'admin-post.php?action=gdymc_activate_module&redirect=' . urlencode( admin_url( 'themes.php?page=gdymc-plugin-settings&gdymc_module_type=' . $module->type ) ) . '&gdymc_module_type=' . urlencode( $module->type ) ) ); ?>">
                            <?php echo __( 'Activate', 'gdy-modular-content' ); ?>
                          </a>
                        <?php else : ?>
                          <?php echo __( 'Active', 'gdy-modular-content' ); ?> | 
                          <a href="<?php echo esc_url( admin_url( 'admin-post.php?action=gdymc_deactivate_module&redirect=' . urlencode( admin_url( 'themes.php?page=gdymc-plugin-settings&gdymc_module_type=' . $module->type ) ) . '&gdymc_module_type=' . urlencode( $module->type ) ) ); ?>">
                            <?php echo __( 'Deactivate', 'gdy-modular-content' ); ?>
                          </a>
                        <?php endif; ?>
                      </span>
                    </div>
                  </div>
                </div>
                <div id="major-publishing-actions">
                  <div id="publishing-action">
                    <input type="submit" name="publish" id="publish" class="button button-primary button-large" value="<?php echo __( 'Save Module', 'gdy-modular-content' ); ?>" />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>