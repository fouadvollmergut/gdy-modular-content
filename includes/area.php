<?php

/**
 * Handles area create behavior.
 */
function areaCreate()
{
    // Global variables
    global $gdymc_module;
    global $gdymc_area;

    // Only create area if it doesn't exist already
    if ($gdymc_area):
        if (WP_DEBUG):
            trigger_error("areaCreate was already called");
        endif;

        // Area exists

        // Current object information

        // Get placed modules for this object

        // Check-Loop

        // Area container: start

        // Has modules

        // No modules

        // Iterate through module array

        // Open module

        // If module blueprint ist inactive, skip it

        // If module is visible

        // Hook: Before module

        // Module container start

        // Module settings (if logged)

        // Get option areas (tabs and tab content)

        // Check areas for content (empty ones are not visible)

        // Build tabs
        // .gdymc_tabs_navigation

        // Build areas
        // .gdymc_tabs_content
        // .gdymc_overlayInner
        // .gdymc_overlayContent

        // Module bar
        // .gdymc_modulebarbuttons_left
        // .gdymc_modulebarbuttons_right
        // .gdymc_modulebar

        // Module inner start

        // Hook: Before module content
        /* DEPRECATED */

        // Include module if possible

        // Missing folder

        // Missing index.php

        // Include the module file

        // Hook: Before after content
        /* DEPRECATED */

        // Module inner end
        // .gdymc_moduleinner

        // Close module container
        // .gdymc_module

        // Hook: After module
        /* DEPRECATED */

        // Save module contents

        // Close module

        // Save module list

        // Area inner: end
        // .gdymc_area

        // Area container: end
    else:
        if (!gdymc_object_type()):
            if (WP_DEBUG):
                trigger_error(
                    "areaCreate ist not supported on this object type"
                );
            endif;
        else:
            $gdymc_area = true;

            $gdymc_object_id = gdymc_object_id();
            $gdymc_object_type = gdymc_object_type();

            $moduleArray = gdymc_module_array(
                $gdymc_object_id,
                $gdymc_object_type
            );

            if (count($moduleArray) > 0):
                foreach ($moduleArray as $key => $value):
                    if (
                        !metadata_exists(
                            $gdymc_object_type,
                            $gdymc_object_id,
                            "_gdymc_" . $value . "_type"
                        )
                    ):
                        if (
                            ($key = array_search($value, $moduleArray)) !==
                            false
                        ):
                            unset($moduleArray[$key]);
                        endif;
                    endif;
                endforeach;
            endif;

            do_action("gdymc_area_before", $moduleArray);

            $class = apply_filters("gdymc_area_class", ["gdymc_area"]);
            echo '<div class="' . implode(" ", $class) . '">';

            do_action("gdymc_areainner_before", $moduleArray);

            if (count($moduleArray) == 0):
                do_action("gdymc_error_area_nomodules");
            else:
                $moduleCount = 0;

                foreach ($moduleArray as $key => $id):
                    $moduleCount++;

                    $gdymc_module = new GDYMC_MODULE(
                        $id,
                        $gdymc_object_id,
                        $gdymc_object_type
                    );

                    if (
                        gdymc_get_module($gdymc_module->type)->status ===
                        "INACTIVE"
                    ) {
                        continue;
                    }

                    if ($gdymc_module->is_visible() or gdymc_logged()):
                        do_action("gdymc_module_before", $gdymc_module);

                        echo "<div " . $gdymc_module->get_attributes() . ">";

                        if (
                            gdymc_logged() and
                            current_user_can("edit_posts", gdymc_object_id())
                        ):
                            echo '<div class="gdymc_overlay_module gdymc_overlay_window gdymc_inside gdymc_tabs_container" style="display: none;">';

                            echo '<div class="gdymc_overlay_head"><div class="gdymc_overlay_head_inner">';

                            echo '<button class="gdymc_overlay_close"></button>';

                            echo '<div class="gdymc_overlay_title">' .
                                __("Module options", "gdy-modular-content") .
                                "</div>";

                            $option_tabs = apply_filters(
                                "gdymc_module_options",
                                [
                                    "defaults" => __(
                                        "Defaults",
                                        "gdy-modular-content"
                                    ),
                                    "visibility" => __(
                                        "Visibility",
                                        "gdy-modular-content"
                                    ),
                                    "settings" => __(
                                        "Settings",
                                        "gdy-modular-content"
                                    ),
                                ],
                                $gdymc_module
                            );

                            $bufferedAreas = [];

                            foreach ($option_tabs as $key => $value):
                                ob_start();

                                do_action(
                                    "gdymc_module_options_" . $key,
                                    $gdymc_module
                                );

                                $handler = ob_get_clean();

                                if (!empty($handler)) {
                                    $bufferedAreas[$key] = $handler;
                                }
                            endforeach;

                            echo '<div class="gdymc_tabs_navigation">';

                            $i = 0;
                            foreach ($option_tabs as $key => $value):
                                if (array_key_exists($key, $bufferedAreas)):
                                    $class =
                                        ++$i == 1
                                            ? "gdymc_tabs_button gdymc_active"
                                            : "gdymc_tabs_button";

                                    echo '<button class="' .
                                        $class .
                                        '" data-tab="' .
                                        $key .
                                        '">' .
                                        $value .
                                        "</button>";
                                endif;
                            endforeach;

                            echo "</div>";

                            echo "</div></div>";

                            echo '<div class="gdymc_overlay_content">';
                            echo '<div class="gdymc_overlay_content_inner">';

                            $i = 0;
                            foreach ($bufferedAreas as $key => $value):
                                $class =
                                    ++$i == 1
                                        ? "gdymc_tabs_content gdymc_active"
                                        : "gdymc_tabs_content";

                                echo '<div class="' .
                                    $class .
                                    '" data-tab="' .
                                    $key .
                                    '">';

                                echo $value;

                                echo "</div>";
                            endforeach;

                            echo "</div>";
                            echo "</div>";

                            echo '<div class="gdymc_overlay_foot">';
                            echo '<div class="gdymc_overlay_foot_inner gdymc_fix">';

                            echo '<div class="gdymc_left">';
                            echo '<button class="gdymc_save gdymc_button">' .
                                __("Save", "gdy-modular-content") .
                                "</button>";
                            echo "</div>";

                            echo '<div class="gdymc_right">';
                            echo '<button class="gdymc_button_delete gdymc_delete_module gdymc_delete_link">' .
                                __(
                                    "Delete Permanently",
                                    "gdy-modular-content"
                                ) .
                                "</button>";
                            echo "</div>";

                            echo "</div>";
                            echo "</div>";

                            echo "</div>";

                            do_action("gdymc_modulebar_before", $gdymc_module);

                            echo '<div class="gdymc_inside gdymc_modulebar gdymc_fix ">';

                            echo '<ul class="gdymc_modulebarbuttons_left gdymc_left gdymc_fix">';

                            do_action(
                                "gdymc_modulebarbuttons_left",
                                $gdymc_module
                            );

                            echo "</ul>";

                            echo '<ul class="gdymc_modulebarbuttons_right gdymc_right gdymc_fix">';

                            do_action(
                                "gdymc_modulebarbuttons_right",
                                $gdymc_module
                            );

                            echo "</ul>";

                            echo "</div>";

                            do_action("gdymc_modulebar_after", $gdymc_module);
                        endif;

                        echo '<div class="gdymc_moduleinner">';

                        do_action("gdymc_module_before_content", $gdymc_module);
                        do_action(
                            "gdymc_module_" .
                                $gdymc_module->type .
                                "_before_content",
                            $gdymc_module
                        );

                        if (
                            !file_exists($gdymc_module->path) or
                            empty($gdymc_module->path)
                        ):
                            do_action(
                                "gdymc_error_module_missing",
                                $gdymc_module
                            );
                        elseif (
                            !file_exists($gdymc_module->file) or
                            empty($gdymc_module->file)
                        ):
                            do_action(
                                "gdymc_error_module_incomplete",
                                $gdymc_module
                            );
                        else:
                            include $gdymc_module->file;
                        endif;

                        do_action("gdymc_module_after_content", $gdymc_module);
                        do_action(
                            "gdymc_module_" .
                                $gdymc_module->type .
                                "_after_content",
                            $gdymc_module
                        );

                        echo "</div>";

                        echo "</div>";

                        do_action("gdymc_module_after", $gdymc_module);
                        do_action(
                            "gdymc_module_" . $gdymc_module->type . "_after",
                            $gdymc_module
                        );
                    endif;

                    update_metadata(
                        $gdymc_object_type,
                        $gdymc_object_id,
                        "_gdymc_" . $gdymc_module->id . "_content",
                        $gdymc_module->content_string()
                    );

                    $gdymc_module = false;
                endforeach;

                update_metadata(
                    $gdymc_object_type,
                    $gdymc_object_id,
                    "_gdymc_modulelist",
                    json_encode($moduleArray)
                );
            endif;

            do_action("gdymc_areainner_after", $moduleArray);

            echo "</div>";

            do_action("gdymc_area_after", $moduleArray);
        endif;
    endif;
}

?>
