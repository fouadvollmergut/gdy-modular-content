<?php

/*************************** MODULE LIST ***************************/

echo '<div id="gdymc_modules" class="gdymc_inside">';

do_action("gdymc_modulelist_before");

// Get information

global $gdymc_area;
$gdymc_has_modules = gdymc_has_modules();
$gdymc_modules = gdymc_get_modules();
$module_folder = apply_filters("gdymc_theme_modules_folder", "modules");

// Show search if module folders, modules and module area exists

if ($gdymc_area and $gdymc_has_modules):
    echo '<div id="gdymc_modules_search_container">';

    do_action("gdymc_modulelist_before_search");

    echo '<input id="gdymc_modules_search" class="mousetrap" type="text" placeholder="' .
        __("Filter for Modules", "gdy-modular-content") .
        '" />';

    do_action("gdymc_modulelist_after_search");

    echo "</div>";
endif;

// Errors or module list

if ($gdymc_has_modules === false):
    // No module folder

    do_action("gdymc_error_adminbar_nomodulefolder");

    // No modules

    // No module area

    // Before modules hook

    // Output the modules

    // Module element

    // Module button

    // Module title

    // After modules hook
elseif ($gdymc_has_modules === 0):
    do_action("gdymc_error_adminbar_nomodules");
elseif (!$gdymc_area):
    do_action("gdymc_error_adminbar_noarea");
else:
    echo '<div id="gdymc_modules_list">';

    echo '<ul id="gdymc_modules_inner" class="gdymc_fix">';

    do_action("gdymc_modulelist_before_modules");

    foreach ($gdymc_modules as $module):
        if ($module->status === "INACTIVE") {
            continue;
        }

        do_action("gdymc_modulelist_before_module", $module->type);

        echo '<li class="gdymc_modules_add_container">';

        echo '<button class="gdymc_modules_add_button" style="background: url(' .
            $module->thumbURL .
            ');" data-type="' .
            $module->type .
            '"></button>';

        echo '<div class="gdymc_modules_add_label"><div class="gdymc_modules_add_label_ground">';

        echo esc_html($module->name);

        echo "</div></div>";

        echo "</li><!-- .gdymc_modules_add_container -->";

        do_action("gdymc_modulelist_after_module", $module->type);
    endforeach;

    do_action("gdymc_modulelist_after_modules");

    echo "</ul><!-- #gdymc_modules_inner -->";

    echo "</div><!-- #gdymc_modules_list -->";
endif;

do_action("gdymc_modulelist_after");

echo "</div><!-- #gdymc_modules -->";

?>
