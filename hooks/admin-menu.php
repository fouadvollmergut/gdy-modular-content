<?php

add_action("admin_menu", "gdymc_plugin_submenu_item");

/**
 * Handles GDYMC plugin submenu item behavior.
 */
function gdymc_plugin_submenu_item()
{
    add_submenu_page(
        "themes.php",
        __("GDY Modular Content Settings", "gdy-modular-content"),
        __("Modules", "gdy-modular-content"),
        "manage_options",
        "gdymc-plugin-settings",
        "gdymc_custom_settings_page"
    );
}

/**
 * Handles GDYMC custom settings page behavior.
 */
function gdymc_custom_settings_page()
{
    include GDYMC_BASE_PATH . "/views/gdymc-settings-page.php";
}
