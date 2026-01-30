# API Reference

Complete reference documentation for GDY Modular Content functions, filters, and hooks.

## Table of Contents

- [Content Functions](#content-functions)
- [Option Functions](#option-functions)
- [Area Functions](#area-functions)
- [Module Functions](#module-functions)
- [Utility Functions](#utility-functions)
- [Module Class](#module-class)
- [Filters](#filters)
- [Actions](#actions)

## Content Functions

### contentCreate()

Create editable content area.

**Syntax:**
```php
contentCreate( $id, $type, $options = array() )
```

**Parameters:**
- `$id` (string|int) - Unique content identifier
- `$type` (string) - Content type: 'text', 'image', 'gallery', 'table'
- `$options` (array) - Optional configuration parameters

**Content Types:**

**Text:**
```php
contentCreate('title', 'text', array(
    'maxlength' => 100,      // Maximum characters
    'multiline' => true,     // Allow line breaks
    'placeholder' => 'Text'  // Placeholder text
));
```

**Image:**
```php
contentCreate('hero', 'image', array(
    'width' => 1200,         // Image width in pixels
    'height' => 600,         // Image height in pixels
    'renderer' => callable   // Custom rendering function
));
```

**Gallery:**
```php
contentCreate('photos', 'gallery', array(
    'renderer' => callable   // Custom rendering function
));
```

**Table:**
```php
contentCreate('data', 'table', array(
    'rows' => 5,             // Initial row count
    'cols' => 3              // Initial column count
));
```

**Returns:** void (outputs HTML)

---

### contentGet()

Retrieve content value without rendering.

**Syntax:**
```php
contentGet( $id, $object_id = null )
```

**Parameters:**
- `$id` (string|int) - Content identifier
- `$object_id` (int) - Optional object ID (defaults to current post)

**Returns:** (mixed) Content value or null

**Example:**
```php
$title = contentGet('title');
$image_id = contentGet('hero_image');
$table_data = contentGet('pricing_table'); // Returns array
```

---

### contentCheck()

Check if content exists and has a value.

**Syntax:**
```php
contentCheck( $id, $object_id = null )
```

**Parameters:**
- `$id` (string|int|array) - Content identifier or array of identifiers
- `$object_id` (int) - Optional object ID

**Returns:** (bool) True if content exists

**Example:**
```php
// Check single content
if (contentCheck('hero')) {
    // Content exists
}

// Check multiple (all must exist)
if (contentCheck(array('title', 'subtitle', 'image'))) {
    // All content exists
}
```

---

## Option Functions

### optionCreate()

Create a module option field.

**Syntax:**
```php
optionCreate( $id, $type, $options = array() )
```

**Parameters:**
- `$id` (string) - Option identifier
- `$type` (string) - Input type
- `$options` (array) - Configuration

**Input Types:**
- `text` - Single line text input
- `textarea` - Multi-line text input
- `select` - Dropdown select
- `checkbox` - Checkbox (boolean)
- `radio` - Radio button group
- `hidden` - Hidden field
- `password` - Password field
- `number` - Number input (HTML5)
- `date` - Date picker (HTML5)
- `color` - Color picker (HTML5)
- `range` - Range slider (HTML5)
- `email` - Email input (HTML5)
- `url` - URL input (HTML5)
- `tel` - Telephone input (HTML5)
- `search` - Search input (HTML5)

**Common Options:**
```php
array(
    'label' => 'Option Label',
    'default' => 'default value',
    'description' => 'Help text',
    'options' => array() // For select/radio
)
```

**Example:**
```php
optionCreate('layout', 'select', array(
    'label' => 'Layout Type',
    'options' => array(
        'grid' => 'Grid Layout',
        'list' => 'List Layout',
        'masonry' => 'Masonry Layout'
    ),
    'default' => 'grid'
));
```

---

### optionGet()

Retrieve option value.

**Syntax:**
```php
optionGet( $id, $default = null, $object_id = null )
```

**Parameters:**
- `$id` (string) - Option identifier
- `$default` (mixed) - Default value if not set
- `$object_id` (int) - Optional object ID

**Returns:** (mixed) Option value

**Example:**
```php
$layout = optionGet('layout', 'grid');
$color = optionGet('background_color', '#ffffff');
```

---

### optionShow()

Output option value (HTML escaped).

**Syntax:**
```php
optionShow( $id, $default = '' )
```

**Parameters:**
- `$id` (string) - Option identifier
- `$default` (mixed) - Default value

**Returns:** void (outputs escaped HTML)

**Example:**
```php
<div style="color: <?php optionShow('text_color'); ?>">
    Content
</div>
```

---

### optionExists()

Check if option exists and has a value.

**Syntax:**
```php
optionExists( $id, $object_id = null )
```

**Parameters:**
- `$id` (string) - Option identifier
- `$object_id` (int) - Optional object ID

**Returns:** (bool) True if option exists

---

### optionInput()

Create a custom option input field (advanced).

**Syntax:**
```php
optionInput( $id, $type, $label, $value = '', $extras = '' )
```

---

### optionSave()

Manually save option value (advanced).

**Syntax:**
```php
optionSave( $id, $value )
```

---

### optionError()

Display option error message (advanced).

**Syntax:**
```php
optionError( $message )
```

---

## Area Functions

### areaCreate()

Create a module area.

**Syntax:**
```php
areaCreate()
```

**Parameters:** None

**Returns:** void (outputs module area HTML)

**Example:**
```php
<?php if (function_exists('areaCreate')) areaCreate(); ?>
```

**Note:** Can also be used via shortcode: `[gdymc_area]`

---

## Module Functions

### gdymc_module_name()

Get module name from file path.

**Syntax:**
```php
gdymc_module_name( $file )
```

**Parameters:**
- `$file` (string) - File path (use `__FILE__`)

**Returns:** (string) Module name

**Example:**
```php
$module_name = gdymc_module_name(__FILE__);
```

**Note:** Deprecated, use `gdymc_module_type()` instead.

---

### gdymc_module_type()

Get module type from file path.

**Syntax:**
```php
gdymc_module_type( $location )
```

**Parameters:**
- `$location` (string) - File path

**Returns:** (string) Module type

---

### gdymc_module_path()

Get absolute filesystem path to module directory.

**Syntax:**
```php
gdymc_module_path( $location, $path = '' )
```

**Parameters:**
- `$location` (string) - File path (use `__FILE__`)
- `$path` (string) - Optional sub-path

**Returns:** (string) Absolute path

**Example:**
```php
$module_path = gdymc_module_path(__FILE__);
require_once $module_path . '/includes/helper.php';
```

---

### gdymc_module_url()

Get URL to module directory.

**Syntax:**
```php
gdymc_module_url( $location, $path = '' )
```

**Parameters:**
- `$location` (string) - File path (use `__FILE__`)
- `$path` (string) - Optional sub-path

**Returns:** (string) URL

**Example:**
```php
$icon_url = gdymc_module_url(__FILE__) . '/images/icon.png';
echo '<img src="' . esc_url($icon_url) . '">';
```

---

### gdymc_module_is_placed()

Check if a module is placed on current or specified page.

**Syntax:**
```php
gdymc_module_is_placed( $module, $object_id = null )
```

**Parameters:**
- `$module` (string) - Module type name
- `$object_id` (int) - Optional object ID (defaults to current)

**Returns:** (bool) True if module is placed

**Example:**
```php
if (gdymc_module_is_placed('hero-section')) {
    wp_enqueue_style('hero-style');
}
```

---

### gdymc_get_modules()

Get all available module types.

**Syntax:**
```php
gdymc_get_modules()
```

**Parameters:** None

**Returns:** (array) Array of module information

---

### gdymc_get_placed_modules()

Get modules placed on an object.

**Syntax:**
```php
gdymc_get_placed_modules( $object_id = null )
```

**Parameters:**
- `$object_id` (int) - Optional object ID

**Returns:** (array) Array of placed modules

---

### gdymc_has_modules()

Check if any modules exist.

**Syntax:**
```php
gdymc_has_modules()
```

**Parameters:** None

**Returns:** (bool) True if modules exist

---

## Utility Functions

### gdymc_logged()

Check if current user can edit GDYMC content.

**Syntax:**
```php
gdymc_logged()
```

**Parameters:** None

**Returns:** (bool) True if user has edit permissions

**Example:**
```php
if (gdymc_logged()) {
    // Show admin controls
}
```

---

### gdymc_object_id()

Get current object ID (post, page, term).

**Syntax:**
```php
gdymc_object_id()
```

**Parameters:** None

**Returns:** (int) Object ID

---

### gdymc_object_type()

Get current object type.

**Syntax:**
```php
gdymc_object_type()
```

**Parameters:** None

**Returns:** (string) 'post', 'term', or false

---

### gdymc_current_url()

Get current page URL.

**Syntax:**
```php
gdymc_current_url()
```

**Parameters:** None

**Returns:** (string) Current URL

---

### gdymc_set_cookie()

Set a GDYMC cookie.

**Syntax:**
```php
gdymc_set_cookie( $key, $value )
```

**Parameters:**
- `$key` (string) - Cookie name
- `$value` (string) - Cookie value

---

### gdymc_remove_cookie()

Remove a GDYMC cookie.

**Syntax:**
```php
gdymc_remove_cookie( $key )
```

**Parameters:**
- `$key` (string) - Cookie name

---

### gdymc_preview()

Check if preview mode is active.

**Syntax:**
```php
gdymc_preview()
```

**Parameters:** None

**Returns:** (bool) True if in preview mode

---

### gdymc_softpreview()

Check if soft preview mode is active.

**Syntax:**
```php
gdymc_softpreview()
```

**Parameters:** None

**Returns:** (bool) True if in soft preview

---

### gdymc_hardpreview()

Check if hard preview mode is active.

**Syntax:**
```php
gdymc_hardpreview()
```

**Parameters:** None

**Returns:** (bool) True if in hard preview

---

## Module Class

### GDYMC_MODULE

The module object contains information about a module instance.

**Properties:**

```php
$module->id           // Module ID
$module->type         // Module type name
$module->object_id    // Parent object ID
$module->object_type  // Parent object type ('post' or 'term')
$module->position     // Position in module list
$module->visible      // Visibility status (boolean)
```

**Usage:**

```php
global $gdymc_module;

if ($gdymc_module) {
    echo 'Module Type: ' . $gdymc_module->type;
    echo 'Module ID: ' . $gdymc_module->id;
}
```

---

## Filters

### gdymc_modules_folder

Modify module folder locations.

**Parameters:**
- `$folders` (array) - Array of folder paths

**Example:**
```php
add_filter('gdymc_modules_folder', 'custom_module_folders');

function custom_module_folders($folders) {
    $folders[] = get_template_directory() . '/modules';
    $folders[] = get_stylesheet_directory() . '/child-modules';
    return $folders;
}
```

---

### gdymc_module_class

Filter module container classes.

**Parameters:**
- `$classes` (array) - Array of CSS classes
- `$module` (object) - Module object

**Example:**
```php
add_filter('gdymc_module_class', 'custom_module_class', 10, 2);

function custom_module_class($classes, $module) {
    $classes[] = 'module-type-' . $module->type;
    if (!$module->visible) {
        $classes[] = 'module-hidden';
    }
    return $classes;
}
```

---

### gdymc_module_attributes

Filter module container attributes.

**Parameters:**
- `$attributes` (array) - Associative array of attributes
- `$module` (object) - Module object

**Example:**
```php
add_filter('gdymc_module_attributes', 'custom_module_attrs', 10, 2);

function custom_module_attrs($attrs, $module) {
    $attrs['data-module-id'] = $module->id;
    $attrs['data-module-type'] = $module->type;
    return $attrs;
}
```

---

### gdymc_area_class

Filter area container classes.

**Parameters:**
- `$classes` (array) - Array of CSS classes

**Example:**
```php
add_filter('gdymc_area_class', 'custom_area_class');

function custom_area_class($classes) {
    $classes[] = 'custom-module-area';
    return $classes;
}
```

---

### gdymc_support

Enable/disable GDYMC features.

**Parameters:**
- `$support` (bool) - Support status

**Example:**
```php
add_filter('gdymc_support', '__return_true');
```

---

### gdymc_imagesize

Filter default image size for content images.

**Parameters:**
- `$size` (string) - Image size name

---

### gdymc_loginout_redirect

Disable login/logout redirects.

**Parameters:**
- `$redirect` (bool) - Redirect status

**Example:**
```php
add_filter('gdymc_loginout_redirect', '__return_false');
```

---

## Actions

### gdymc_module_before

Fires before a module is rendered.

**Parameters:**
- `$module` (object) - Module object

**Example:**
```php
add_action('gdymc_module_before', 'before_module_output', 10, 1);

function before_module_output($module) {
    if ($module->type === 'hero') {
        echo '<div class="hero-container">';
    }
}
```

---

### gdymc_module_after

Fires after a module is rendered.

**Parameters:**
- `$module` (object) - Module object

**Example:**
```php
add_action('gdymc_module_after', 'after_module_output', 10, 1);

function after_module_output($module) {
    if ($module->type === 'hero') {
        echo '</div>';
    }
}
```

---

### gdymc_modulelist_before

Fires before the module list is rendered.

**Example:**
```php
add_action('gdymc_modulelist_before', 'before_modules');

function before_modules() {
    echo '<div class="modules-wrapper">';
}
```

---

### gdymc_modulelist_after

Fires after the module list is rendered.

**Example:**
```php
add_action('gdymc_modulelist_after', 'after_modules');

function after_modules() {
    echo '</div>';
}
```

---

### gdymc_image_before

Fires before an image content is rendered.

---

### gdymc_image_after

Fires after an image content is rendered.

---

### gdymc_adminbarbuttons_left

Add buttons to the left side of the admin bar.

**Example:**
```php
add_action('gdymc_adminbarbuttons_left', 'add_custom_button');

function add_custom_button() {
    echo '<button class="custom-button">Custom</button>';
}
```

---

### gdymc_adminbarbuttons_right

Add buttons to the right side of the admin bar.

---

### gdymc_modulebarbuttons_left

Add buttons to the left side of module bar.

**Parameters:**
- `$module` (object) - Module object

---

### gdymc_modulebarbuttons_right

Add buttons to the right side of module bar.

**Parameters:**
- `$module` (object) - Module object

---

### gdymc_plugin_activation

Fires when plugin is activated.

---

### gdymc_plugin_deactivation

Fires when plugin is deactivated.

---

### gdymc_plugin_uninstall

Fires when plugin is uninstalled.

---

### gdymc_plugin_upgrade

Fires when plugin is upgraded.

**Parameters:**
- `$old_version` (string) - Previous version
- `$new_version` (string) - New version

---

## Constants

### GDYMC_PLUGIN_VERSION

Plugin version number.

```php
echo GDYMC_PLUGIN_VERSION; // e.g., '0.9.984'
```

---

### GDYMC_BASE_PATH

Absolute path to plugin directory.

```php
require_once GDYMC_BASE_PATH . 'includes/functions.php';
```

---

## Global Variables

### $gdymc_module

Current module object (available in module templates).

```php
global $gdymc_module;
if ($gdymc_module) {
    echo $gdymc_module->type;
}
```

---

### $gdymc_module_folders

Array of module folder paths.

---

### $gdymc_modules

Array of available modules.

---

### $gdymc_area

Boolean indicating if in module area.

---

### $gdymc_object_id

Current object ID.

---

## JavaScript API

### gdymc.ajax()

Custom AJAX handler with error handling.

### gdymc.editor.addclass()

Add custom editor format buttons.

**Syntax:**
```javascript
gdymc.editor.addclass(className, attributes);
```

### gdymc.selection.parent()

Get parent element of current selection.

### gdymc.selection.inside()

Check if selection is inside an element.

---

## jQuery Events

### gdymc_disable_softpreview

Triggered when soft preview is disabled.

```javascript
jQuery(document).on('gdymc_disable_softpreview', function() {
    // Handle event
});
```

### gdymc_enable_softpreview

Triggered when soft preview is enabled.

```javascript
jQuery(document).on('gdymc_enable_softpreview', function() {
    // Handle event
});
```

---

## See Also

- [Module Development Guide](Module-Development.md)
- [Usage Guide](Usage.md)
- [Configuration Guide](Configuration.md)
