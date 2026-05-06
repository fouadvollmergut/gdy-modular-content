<?php

/**
 * Handles GDYMC responsive image behavior.
 *
 * @param mixed $imageID Image id value.
 *
 * @param mixed $imageSize Image size value.
 *
 * @param mixed $linkURI Link uri value.
 *
 * @param mixed $linkTarget Link target value.
 *
 * @param mixed $videoOptions Video options value.
 */
function gdymc_responsive_image(
    $imageID,
    $imageSize = null,
    $linkURI = null,
    $linkTarget = 0,
    $videoOptions = null
) {
    do_action("gdymc_image_before", $imageID, $imageSize);

    $linkTarget = $linkTarget ? "_blank" : "_self";

    if (!empty($linkURI)) {
        echo '<a href="' . $linkURI . '" target="' . $linkTarget . '">';
    }

    if (is_numeric($imageID) and !empty($imageID)):
        $mimeType = get_post_mime_type($imageID);

        if ($mimeType && strpos($mimeType, "video/") === 0):
            // Render video instead of image. Goal: keep layout (object-fit: cover).
            $videoURL = wp_get_attachment_url($imageID);
            // Defaults when nothing is stored: no controls, autoplay, muted (loop is implied)
            $showControls = is_array($videoOptions)
                ? !empty($videoOptions["controls"])
                : false;
            $autoplay = is_array($videoOptions)
                ? !empty($videoOptions["autoplay"])
                : true;
            $muted = is_array($videoOptions)
                ? !empty($videoOptions["muted"])
                : true;

            $videoAttrs = 'class="gdymc_video"';
            if ($showControls) {
                $videoAttrs .= " controls";
            }
            if ($autoplay) {
                $videoAttrs .= " autoplay";
            }
            if ($muted) {
                $videoAttrs .= " muted";
            }
            // Autoplay typically requires muted and playsinline
            if ($autoplay) {
                $videoAttrs .= " playsinline";
            }
            // Loop when no controls, so the video keeps playing in the background
            if (!$showControls) {
                $videoAttrs .= " loop";
            }

            echo "<video " .
                $videoAttrs .
                ' preload="metadata"><source src="' .
                esc_url($videoURL) .
                '" type="' .
                esc_attr($mimeType) .
                '" /></video>';
        else:
            echo wp_get_attachment_image(
                $imageID,
                apply_filters("gdymc_imagesize", "full")
            );
        endif;
    else:
        if (
            gdymc_logged() and
            is_numeric($imageSize[0]) and
            is_numeric($imageSize[1])
        ):
            echo '<img class="gdymc_placeholder_image img" src="' .
                plugins_url("/placeholder.php", __FILE__) .
                "?w=" .
                $imageSize[0] .
                "&h=" .
                $imageSize[1] .
                '" />';
        else:
            return false;
        endif;
    endif;

    if (!empty($linkURI)) {
        echo "</a>";
    }

    do_action("gdymc_image_after", $imageID, $imageSize);
}

/**
 * Renders GDYMC contenttype image content.
 *
 * @param mixed $contentRealID Content real id value.
 *
 * @param mixed $contentOption Content option value.
 *
 * @param mixed $contentSubOption Content sub option value.
 */
function gdymc_contenttype_image(
    $contentRealID,
    $contentOption,
    $contentSubOption
) {
    $imageSize = empty($contentOption) ? "autoxauto" : $contentOption;
    $imageSize = explode("x", $imageSize);
    $imageWidth = is_numeric($imageSize[0])
        ? $imageSize[0] . "px"
        : $imageSize[0];
    $imageHeight = is_numeric($imageSize[1])
        ? $imageSize[1] . "px"
        : $imageSize[1];

    // This is an array that hold arrays with 3 values: image id, link url, link target
    $contentString = get_metadata(
        gdymc_object_type(),
        gdymc_object_id(),
        "_gdymc_singlecontent_" . $contentRealID,
        true
    );
    $imageObject = json_decode($contentString);

    // This converts the pre 0.7.4 system into the new one
    if (!is_array($imageObject) and !empty($contentString)):
        $imageObject = [[intval($contentString), null, null]];
    endif;

    if (gdymc_logged() and current_user_can("edit_posts", gdymc_object_id())):
        echo '<div class="gdymc_image img" data-multiple="false" data-width="' .
            $imageSize[0] .
            '" data-height="' .
            $imageSize[1] .
            '" data-id="' .
            $contentRealID .
            '" data-image=\'' .
            json_encode($imageObject) .
            '\'>';
    else:
        echo '<div class="gdymc_image img">';
    endif;

    if (
        !empty($imageObject) and
        is_array($imageObject) and
        isset($imageObject[0]) and
        isset($imageObject[0][0]) and
        is_numeric($imageObject[0][0])
    ):
        // Show image (or video) - the 4th element optionally carries video options
        $videoOptions = isset($imageObject[0][3])
            ? (array) $imageObject[0][3]
            : null;
        gdymc_responsive_image(
            $imageObject[0][0],
            $imageSize,
            $imageObject[0][1],
            $imageObject[0][2],
            $videoOptions
        );
    endif;

    if (gdymc_logged() and current_user_can("edit_posts", gdymc_object_id())):
        echo "</div>";
    else:
        echo "</div>";
    endif;
}

/**
 * Renders GDYMC contenttype gallery content.
 *
 * @param mixed $contentRealID Content real id value.
 *
 * @param mixed $contentOption Content option value.
 *
 * @param mixed $customRenderer Custom renderer value.
 */
function gdymc_contenttype_gallery(
    $contentRealID,
    $contentOption,
    $customRenderer
) {
    $imageSize = empty($contentOption) ? "autoxauto" : $contentOption;
    $imageSize = explode("x", $imageSize);
    $imageWidth = is_numeric($imageSize[0])
        ? $imageSize[0] . "px"
        : $imageSize[0];
    $imageHeight = is_numeric($imageSize[1])
        ? $imageSize[1] . "px"
        : $imageSize[1];

    $sliderContents = get_metadata(
        gdymc_object_type(),
        gdymc_object_id(),
        "_gdymc_singlecontent_" . $contentRealID,
        true
    );
    $sliderArray = explode(",", $sliderContents);
    $sliderCount = isset($sliderArray) ? count($sliderArray) : 0;

    // This is an array that hold arrays with 3 values: image id, link url, link target
    $contentString = get_metadata(
        gdymc_object_type(),
        gdymc_object_id(),
        "_gdymc_singlecontent_" . $contentRealID,
        true
    );
    $imageObject = json_decode($contentString);

    // This converts the pre 0.7.4 system into the new one
    if (!is_array($imageObject) and !empty($contentString)):
        $sliderArray = explode(",", $contentString);
        $imageObject = [];

        foreach ($sliderArray as $imageID):
            array_push($imageObject, [intval($imageID), null, null]);
        endforeach;
    endif;

    $sliderCount = isset($imageObject) ? count($imageObject) : 0;

    if (gdymc_logged() and current_user_can("edit_posts", gdymc_object_id())):
        echo '<div class="gdymc_gallery_container img" data-multiple="true" data-width="' .
            $imageSize[0] .
            '" data-height="' .
            $imageSize[1] .
            '" data-id="' .
            $contentRealID .
            '" data-image=\'' .
            json_encode($imageObject) .
            '\'>';
    else:
        echo '<div class="gdymc_gallery_container img">';
    endif;

    if (!empty($imageObject)):
        echo '<ul class="gdymc_gallery" data-images="' . $sliderCount . '">';

        $i = 0;
        foreach ($imageObject as $image):
            $i++;

            $wpImage = get_post($image[0]);

            echo '<li class="gdymc_gallery_item gdymc_gallery_item_' .
                $i .
                '" data-slide="' .
                $i .
                '" data-image-id="' .
                $image[0] .
                '">';

            do_action("gdymc_galleryimage_before", $image[0], $wpImage);

            // Check if custom renderer exists

            if (
                is_object($customRenderer) &&
                $customRenderer instanceof Closure
            ):
                $customRenderer($image, $i);
            else:
                $videoOptions = isset($image[3]) ? (array) $image[3] : null;
                gdymc_responsive_image(
                    $image[0],
                    $imageSize,
                    $image[1],
                    $image[2],
                    $videoOptions
                );
            endif;

            do_action("gdymc_galleryimage_after", $image[0], $wpImage);

            echo "</li>";
        endforeach;

        echo "</ul>";
    else:
        if (
            gdymc_logged() and
            is_numeric($imageSize[0]) and
            is_numeric($imageSize[1])
        ):
            echo '<img class="gdymc_placeholder_image" src="' .
                plugins_url("/placeholder.php", __FILE__) .
                "?w=" .
                $imageSize[0] .
                "&h=" .
                $imageSize[1] .
                '" />';
        endif;
    endif;

    if (gdymc_logged() and current_user_can("edit_posts", gdymc_object_id())):
        echo "</div>"; // .gdymc_gallery_container
    else:
        echo "</div>"; // .gdymc_gallery_container
    endif;
}

/**
 * Renders GDYMC contenttype text content.
 *
 * @param mixed $contentRealID Content real id value.
 *
 * @param mixed $contentTag Content tag value.
 *
 * @param mixed $contentOption Content option value.
 *
 * @param mixed $contentSubOption Content sub option value.
 */
function gdymc_contenttype_text(
    $contentRealID,
    $contentTag,
    $contentOption,
    $contentSubOption
) {
    $length =
        (is_numeric($contentOption) and $contentOption > 0)
            ? $contentOption
            : "auto";

    if (gdymc_logged() and current_user_can("edit_posts", gdymc_object_id())):
        $classList = "gdymc_text mousetrap " . $contentSubOption;
        echo "<" .
            $contentTag .
            ' class="' .
            trim($classList) .
            '" data-id="' .
            $contentRealID .
            '" data-length="' .
            $length .
            '">';
    else:
        $classList = "gdymc_text " . $contentSubOption;
        echo "<" . $contentTag . ' class="' . trim($classList) . '">';
    endif;

    $content = get_metadata(
        gdymc_object_type(),
        gdymc_object_id(),
        "_gdymc_singlecontent_" . $contentRealID,
        true
    );
    echo apply_filters("gdymc_contentfilter", $content);

    if (gdymc_logged() and current_user_can("edit_posts", gdymc_object_id())):
        echo "</" . $contentTag . ">";
    else:
        echo "</" . $contentTag . ">";
    endif;
}

/**
 * Renders GDYMC contenttype table content.
 *
 * @param mixed $contentRealID Content real id value.
 *
 * @param mixed $contentOption Content option value.
 *
 * @param mixed $contentSubOption Content sub option value.
 */
function gdymc_contenttype_table(
    $contentRealID,
    $contentOption,
    $contentSubOption
) {
    $content = get_metadata(
        gdymc_object_type(),
        gdymc_object_id(),
        "_gdymc_singlecontent_" . $contentRealID,
        true
    );
    $contentJSON = json_decode($content);

    echo '<div class="gdymc_table_container">';

    echo '<table class="gdymc_table" data-id="' .
        $contentRealID .
        '" width="100%">';

    if (empty($content)):
        $tableSize = empty($contentOption) ? "3x2" : $contentOption;
        $tableSize = explode("x", $tableSize);

        $tableWidth =
            (is_numeric($tableSize[0]) and $tableSize[0] > 1)
                ? $tableSize[0]
                : "1";
        $tableHeight =
            (is_numeric($tableSize[1]) and $tableSize[1] > 1)
                ? $tableSize[1]
                : "1";

        for ($heightIndex = 1; $heightIndex <= $tableHeight; $heightIndex++):
            echo "<tr>";

            for ($widthIndex = 1; $widthIndex <= $tableWidth; $widthIndex++):
                echo "<td></td>";
            endfor;

            echo "</tr>";
        endfor;

        // Backward compatibility for pre 0.7.8 were tables are not saved in JSON
    elseif (empty($contentJSON)):
        echo $content;
    else:
        foreach ($contentJSON as $row):
            echo "<tr>";

            foreach ($row as $col):
                echo "<td>" . $col . "</td>";
            endforeach;

            echo "</tr>";
        endforeach;
    endif;

    echo "</table>";

    if (gdymc_logged()):
        echo '<button class="gdymc_table_addrow"></button>';
        echo '<button class="gdymc_table_addcol"></button>';
        echo '<button class="gdymc_table_removerow"></button>';
        echo '<button class="gdymc_table_removecol"></button>';
    endif;

    echo "</div>";
}

/**
 * Renders GDYMC contenttype buttongroup content.
 *
 * @param mixed $contentRealID Content real id value.
 *
 * @param mixed $contentOption Content option value.
 *
 * @param mixed $contentSubOption Content sub option value.
 */
function gdymc_contenttype_buttongroup(
    $contentRealID,
    $contentOption,
    $contentSubOption
) {
    $content = get_metadata(
        gdymc_object_type(),
        gdymc_object_id(),
        "_gdymc_singlecontent_" . $contentRealID,
        true
    );

    if (!is_array(json_decode($content, true))):
        echo '<div class="gdymc_button-group_container">';
        echo '<div class="gdymc_button-group" data-id="' .
            $contentRealID .
            '">';

        echo $content;

        echo "</div>";

        if (gdymc_logged() && !gdymc_preview()):
            echo '<button class="gdymc_button gdymc_inside_button gdymc_button_addbutton" style="display: none;">' .
                __("Add Button", "gdy-modular-content") .
                "</button>";
        endif;

        echo "</div>";
    else:
        $content = json_decode($content, true);

        echo '<div class="gdymc_button-group_container">';

        echo '<div class="gdymc_button-group" data-id="' .
            $contentRealID .
            '" data-buttons-json=\'' .
            json_encode($content) .
            '\'>';

        foreach ($content as $button):
            $type = $button["type"] ? "button-primary" : "";
            $target = $button["target"] ? "_blank" : "_self";

            echo '<div class="gdymc_button_container">';

            echo '<a href="' .
                esc_url($button["url"]) .
                '" class="button ' .
                esc_attr($type) .
                '" target="' .
                esc_attr($target) .
                '" aria-label="' .
                esc_attr($button["text"]) .
                '">' .
                esc_html($button["text"]) .
                "</a>";

            if (gdymc_logged() && !gdymc_preview()):
                echo '<button class="gdymc_button gdymc_inside_button gdymc_button_editbutton" aria-label="Edit button" style="display: none;" title="' .
                    __("Edit Button", "gdy-modular-content") .
                    '"><span class="dashicons dashicons-edit"></span></button>';
                echo '<button class="gdymc_button_delete gdymc_inside_button gdymc_button_removebutton" aria-label="Remove button" style="display: none;" title="' .
                    __("Remove Button", "gdy-modular-content") .
                    '"><span class="dashicons dashicons-trash"></span></button>';
            endif;

            echo "</div>";
        endforeach;

        echo "</div>";

        if (gdymc_logged() && !gdymc_preview()):
            echo '<button class="gdymc_button gdymc_inside_button gdymc_button_addbutton" style="display: none;">' .
                __("Button hinzufügen", "gdy-modular-content") .
                "</button>";
        endif;

        echo "</div>";
    endif;
}

/**
 * Handles GDYMC content content id behavior.
 *
 * @param mixed $contentKey Content key value.
 */
function contentID($contentKey)
{
    global $gdymc_module;
    global $gdymc_object_id;
    global $gdymc_object_contents;

    $contentKey = sanitize_title($contentKey);

    if ($gdymc_module):
        if ($gdymc_module->content_exists($contentKey)):
            return $gdymc_module->content[$contentKey];

            // Clean the array
        else:
            $contentRealID = uniqid();
            update_metadata(
                gdymc_object_type(),
                $gdymc_object_id,
                "_gdymc_singlecontent_" . $contentRealID,
                ""
            );
            $gdymc_module->content[$contentKey] = $contentRealID;

            $max = max(array_keys($gdymc_module->content));
            if ($max != 0):
                $gdymc_module->content =
                    $gdymc_module->content + array_fill(0, intval($max), "");
                ksort($gdymc_module->content);
            endif;

            return $contentRealID;
        endif;

        // Setup page contents

        // Get object content

        // Clean the array
    else:
        if (!is_array($gdymc_object_contents)):
            $content = get_metadata(
                gdymc_object_type(),
                gdymc_object_id(),
                "_gdymc_object_contents",
                true
            );
            $content = json_decode($content, true);
            $gdymc_object_contents = is_array($content) ? $content : [];
        endif;

        if (
            isset($gdymc_object_contents[$contentKey]) and
            !empty($gdymc_object_contents[$contentKey])
        ):
            return $gdymc_object_contents[$contentKey];
        else:
            $contentRealID = uniqid();
            update_metadata(
                gdymc_object_type(),
                $gdymc_object_id,
                "_gdymc_singlecontent_" . $contentRealID,
                ""
            );
            $gdymc_object_contents[$contentKey] = $contentRealID;

            $max = max(array_keys($gdymc_object_contents));
            if ($max != 0):
                $gdymc_object_contents =
                    $gdymc_object_contents + array_fill(0, intval($max), "");
                ksort($gdymc_object_contents);
            endif;

            return $contentRealID;
        endif;
    endif;
}

/**
 * Handles GDYMC content content get behavior.
 *
 * @param mixed $contentKey Content key value.
 */
function contentGet($contentKey)
{
    $contentID = contentID($contentKey);

    return get_metadata(
        gdymc_object_type(),
        gdymc_object_id(),
        "_gdymc_singlecontent_" . $contentID,
        true
    );
}

/**
 * Handles GDYMC content content show behavior.
 *
 * @param mixed $contentKey Content key value.
 */
function contentShow($contentKey)
{
    echo contentGet($contentKey);
}

/**
 * Handles GDYMC content content check behavior.
 *
 * @param mixed $contentKey Content key value.
 */
function contentCheck($contentKey)
{
    foreach (func_get_args() as $contentKey):
        $content = contentGet($contentKey);
        $content = str_replace(" ", "", strip_tags($content));

        if (!empty($content) or gdymc_logged()) {
            return true;
        }
    endforeach;

    return false;
}

/**************************** CREATES A EDITABLE CONTENT ****************************/

/**
 * Options with wp_parse_args.
 *
 * @param mixed $contentKey Content key value.
 *
 * @param mixed $contentType Content type value.
 *
 * @param mixed $contentOption Content option value.
 *
 * @param mixed $contentSubOption Content sub option value.
 */
function contentCreate(
    $contentKey,
    $contentType = "div/text",
    $contentOption = "",
    $contentSubOption = ""
) {
    if (!gdymc_object_type()):
        if (WP_DEBUG):
            trigger_error(
                "contentCreate ist not supported on this object type"
            );
        endif;

        // Option is image size e.g. 300x500 or 250xauto. Default is autoxauto.

        // Option is table field size for start e.g. 3x5. Default is 3x1.

        // Option is image size e.g. 300x500 or 250xauto. Default is autoxauto.

        // Option is maximum character length. Default is auto (infinite). Suboption are additional calsses for container
    else:
        $contentID = contentID($contentKey);

        if ($contentType == "image"):
            gdymc_contenttype_image(
                $contentID,
                $contentOption,
                $contentSubOption
            );
        elseif ($contentType == "table"):
            gdymc_contenttype_table(
                $contentID,
                $contentOption,
                $contentSubOption
            );
        elseif ($contentType == "buttongroup"):
            gdymc_contenttype_buttongroup(
                $contentID,
                $contentOption,
                $contentSubOption
            );
        elseif ($contentType == "gallery"):
            gdymc_contenttype_gallery(
                $contentID,
                $contentOption,
                $contentSubOption
            );
        else:
            $contentTag = explode("/", $contentType);

            gdymc_contenttype_text(
                $contentID,
                $contentTag[0] == "text" ? "div" : $contentTag[0],
                $contentOption,
                $contentSubOption
            );
        endif;
    endif;
}

?>
