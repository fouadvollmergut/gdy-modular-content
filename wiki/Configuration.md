# Configuration

This guide covers configuration options, filters, and hooks available in GDY Modular Content.

## Table of Contents

- [Module Folders](#module-folders)
- [Plugin Support](#plugin-support)
- [User Permissions](#user-permissions)
- [Preview Configuration](#preview-configuration)
- [Image Configuration](#image-configuration)
- [Module Display](#module-display)
- [Error Messages](#error-messages)
- [Advanced Configuration](#advanced-configuration)

## Module Folders

### Setting Custom Module Folders

By default, GDYMC looks for modules in `/wp-content/modules`. You can add custom module folder locations:

```php
add_filter('gdymc_modules_folder', 'custom_module_folders');

function custom_module_folders($folders) {
    // Add theme modules folder
    $folders[] = get_template_directory() . '/modules';
    
    // Add child theme modules folder
    if (is_child_theme()) {
        $folders[] = get_stylesheet_directory() . '/modules';
    }
    
    // Add custom location
    $folders[] = WP_CONTENT_DIR . '/custom-modules';
    
    return $folders;
}
```

**Note:** Paths must be absolute filesystem paths, not URLs.

### Module Priority

Modules are loaded in the order folders are specified. If modules with the same name exist in multiple folders, the first one found is used.

## Plugin Support

### Enable/Disable GDYMC

Control whether GDYMC is active:

```php
// Disable GDYMC
add_filter('gdymc_support', '__return_false');

// Enable only for specific post types
add_filter('gdymc_support', 'limit_gdymc_support');

function limit_gdymc_support($support) {
    if (!is_singular(array('page', 'post'))) {
        return false;
    }
    return $support;
}

// Enable only for administrators
add_filter('gdymc_support', 'admin_only_gdymc');

function admin_only_gdymc($support) {
    return current_user_can('manage_options');
}
```

## User Permissions

### Required Capability

By default, users need the `edit_pages` capability to use GDYMC. This typically includes:
- Administrators
- Editors

To change the required capability:

```php
add_filter('gdymc_capability', 'custom_gdymc_capability');

function custom_gdymc_capability($capability) {
    return 'edit_posts'; // Allow authors and above
    // or
    return 'manage_options'; // Administrators only
}
```

### User Role Support

GDYMC checks for these capabilities:
- `edit_pages` - Edit capability
- `upload_files` - Upload media

Ensure your custom roles have these capabilities if needed.

## Preview Configuration

### Disable Login/Logout Redirects

By default, GDYMC redirects users after login/logout. To disable:

```php
add_filter('gdymc_loginout_redirect', '__return_false');
```

### Custom Preview Behavior

Control preview modes programmatically:

```php
// Check if in preview mode
if (gdymc_preview()) {
    // In either soft or hard preview
}

if (gdymc_softpreview()) {
    // In soft preview only
}

if (gdymc_hardpreview()) {
    // In hard preview only
}
```

## Image Configuration

### Default Image Size

Set the default image size for content images:

```php
add_filter('gdymc_imagesize', 'custom_default_image_size');

function custom_default_image_size($size) {
    return 'large'; // WordPress image size name
}
```

### Custom Image Sizes

Register custom image sizes for use with GDYMC:

```php
// In functions.php
add_image_size('hero-image', 1920, 1080, true);
add_image_size('card-image', 600, 400, true);

// Use in module
contentCreate('hero', 'image', array(
    'width' => 1920,
    'height' => 1080
));
```

### Responsive Images

GDYMC supports WordPress responsive images. Use custom renderer:

```php
contentCreate('hero', 'image', array(
    'width' => 1920,
    'height' => 1080,
    'renderer' => function($image_id, $content) {
        return wp_get_attachment_image($image_id, 'full', false, array(
            'sizes' => '(max-width: 768px) 100vw, 50vw'
        ));
    }
));
```

## Module Display

### Module Container Classes

Add custom classes to module containers:

```php
add_filter('gdymc_module_class', 'custom_module_classes', 10, 2);

function custom_module_classes($classes, $module) {
    // Add module type as class
    $classes[] = 'module-' . sanitize_html_class($module->type);
    
    // Add custom class based on module visibility
    if (!$module->visible) {
        $classes[] = 'module-hidden-admin';
    }
    
    // Add position-based class
    if ($module->position === 0) {
        $classes[] = 'first-module';
    }
    
    return $classes;
}
```

### Module Container Attributes

Add custom attributes to module containers:

```php
add_filter('gdymc_module_attributes', 'custom_module_attributes', 10, 2);

function custom_module_attributes($attrs, $module) {
    // Add data attributes
    $attrs['data-module-id'] = $module->id;
    $attrs['data-module-type'] = $module->type;
    $attrs['data-position'] = $module->position;
    
    // Add ARIA attributes
    $attrs['role'] = 'article';
    $attrs['aria-label'] = 'Module: ' . $module->type;
    
    return $attrs;
}
```

### Area Container Classes

Customize module area classes:

```php
add_filter('gdymc_area_class', 'custom_area_classes');

function custom_area_classes($classes) {
    $classes[] = 'custom-module-area';
    
    // Add page-specific class
    if (is_front_page()) {
        $classes[] = 'homepage-modules';
    }
    
    return $classes;
}
```

### Module Wrapping

Add HTML before/after modules:

```php
// Before module
add_action('gdymc_module_before', 'wrap_module_before', 10, 1);

function wrap_module_before($module) {
    if ($module->type === 'hero-section') {
        echo '<section class="hero-wrapper">';
    }
}

// After module
add_action('gdymc_module_after', 'wrap_module_after', 10, 1);

function wrap_module_after($module) {
    if ($module->type === 'hero-section') {
        echo '</section>';
    }
}
```

### Module List Wrapping

Add HTML before/after module list:

```php
add_action('gdymc_modulelist_before', 'modules_container_open');
add_action('gdymc_modulelist_after', 'modules_container_close');

function modules_container_open() {
    echo '<div class="modules-container">';
}

function modules_container_close() {
    echo '</div>';
}
```

## Error Messages

### Custom Error Messages

Customize error messages shown in the admin bar:

```php
// No module folder error
add_filter('gdymc_error_adminbar_nomodulefolder', 'custom_no_folder_message');

function custom_no_folder_message($message) {
    return 'Please create a modules folder in your theme!';
}

// No modules error
add_filter('gdymc_error_adminbar_nomodules', 'custom_no_modules_message');

function custom_no_modules_message($message) {
    return 'No modules found. Add modules to your modules folder.';
}

// No area error
add_filter('gdymc_error_adminbar_noarea', 'custom_no_area_message');

function custom_no_area_message($message) {
    return 'Add areaCreate() to your template to enable modules.';
}

// Area with no modules
add_filter('gdymc_error_area_nomodules', 'custom_area_no_modules_message');

function custom_area_no_modules_message($message) {
    return 'Click the modules button to add content.';
}
```

## Advanced Configuration

### Admin Bar Buttons

Add custom buttons to the admin bar:

```php
// Left side
add_action('gdymc_adminbarbuttons_left', 'add_custom_admin_button');

function add_custom_admin_button() {
    ?>
    <button class="gdymc_button custom-action" onclick="myCustomAction()">
        <span class="gdymc_icon">⚙</span>
        Custom
    </button>
    <?php
}

// Right side
add_action('gdymc_adminbarbuttons_right', 'add_custom_format_button');

function add_custom_format_button() {
    ?>
    <button class="gdymc_button gdymc_formatbutton" 
            data-command="insertHTML" 
            data-value="<mark>">
        Highlight
    </button>
    <?php
}
```

### Module Bar Buttons

Add custom buttons to module bars:

```php
add_action('gdymc_modulebarbuttons_right', 'add_module_action', 10, 1);

function add_module_action($module) {
    if ($module->type === 'my-module') {
        ?>
        <button class="gdymc_button" onclick="customModuleAction(<?php echo $module->id; ?>)">
            Custom Action
        </button>
        <?php
    }
}
```

### Module Option Tabs

Add custom option tabs:

```php
// Add tab button
add_action('gdymc_moduleoptions_tabs', 'add_custom_options_tab', 10, 1);

function add_custom_options_tab($module) {
    if ($module->type === 'my-module') {
        echo '<li data-tab="custom-tab">Custom Settings</li>';
    }
}

// Add tab content
add_action('gdymc_moduleoptions_content', 'add_custom_options_content', 10, 1);

function add_custom_options_content($module) {
    if ($module->type === 'my-module') {
        ?>
        <div class="gdymc_options_tab" data-tab="custom-tab">
            <h3>Custom Settings</h3>
            <!-- Custom options HTML -->
        </div>
        <?php
    }
}
```

### Plugin Lifecycle Hooks

Execute code during plugin lifecycle events:

```php
// On activation
add_action('gdymc_plugin_activation', 'gdymc_custom_activation');

function gdymc_custom_activation() {
    // Set default options
    update_option('gdymc_custom_setting', 'default');
}

// On deactivation
add_action('gdymc_plugin_deactivation', 'gdymc_custom_deactivation');

function gdymc_custom_deactivation() {
    // Cleanup temporary data
}

// On upgrade
add_action('gdymc_plugin_upgrade', 'gdymc_custom_upgrade', 10, 2);

function gdymc_custom_upgrade($old_version, $new_version) {
    // Handle version-specific migrations
    if (version_compare($old_version, '1.0.0', '<')) {
        // Upgrade from pre-1.0 versions
    }
}
```

### AJAX Configuration

GDYMC uses WordPress AJAX. You can add custom AJAX handlers:

```php
// For logged-in users
add_action('wp_ajax_custom_gdymc_action', 'handle_custom_action');

function handle_custom_action() {
    // Verify nonce
    check_ajax_referer('gdymc-nonce', 'nonce');
    
    // Check permissions
    if (!current_user_can('edit_pages')) {
        wp_send_json_error('Insufficient permissions');
    }
    
    // Handle action
    $result = do_something();
    
    wp_send_json_success($result);
}
```

### Cookie Configuration

GDYMC uses cookies for preview modes. Cookies respect WordPress settings:

```php
// Cookies are set with these WordPress constants:
// - COOKIEPATH
// - COOKIE_DOMAIN
```

To customize cookie behavior, modify these constants in `wp-config.php`.

### Body Classes

GDYMC adds body classes for styling:

- `gdymc_logged` - User is logged in with edit permissions
- `gdymc_preview` - In preview mode
- `gdymc_softpreview` - In soft preview mode
- `gdymc_hardpreview` - In hard preview mode

Use these for conditional styling:

```css
.gdymc_logged .admin-only {
    display: block;
}

.gdymc_preview .edit-controls {
    display: none;
}
```

### Translation and Localization

GDYMC is translation-ready with text domain `gdy-modular-content`.

**Load custom translations:**

```php
add_action('init', 'load_custom_gdymc_translations');

function load_custom_gdymc_translations() {
    load_textdomain('gdy-modular-content', 
        get_template_directory() . '/languages/gdy-modular-content-' . get_locale() . '.mo'
    );
}
```

**Admin language support:**

GDYMC supports WordPress 4.7+ admin language feature, allowing different languages for admin and frontend.

### Performance Optimization

**Lazy load modules:**

```php
// In functions.php - conditionally load module assets
add_action('wp_enqueue_scripts', 'optimized_module_assets');

function optimized_module_assets() {
    if (!gdymc_module_is_placed('heavy-module')) {
        return; // Don't load if module isn't used
    }
    
    wp_enqueue_style('heavy-module-style', ...);
    wp_enqueue_script('heavy-module-script', ...);
}
```

**Disable features:**

```php
// Disable batch operations
add_filter('gdymc_enable_batch_operations', '__return_false');
```

## Environment-Specific Configuration

### Development Mode

```php
// In wp-config.php or functions.php
if (defined('WP_DEBUG') && WP_DEBUG) {
    // Show verbose error messages
    add_filter('gdymc_error_verbose', '__return_true');
    
    // Log AJAX errors
    add_action('gdymc_ajax_error', 'log_gdymc_errors', 10, 2);
}

function log_gdymc_errors($error, $context) {
    error_log('GDYMC Error: ' . print_r($error, true));
    error_log('Context: ' . print_r($context, true));
}
```

### Production Mode

```php
if (!defined('WP_DEBUG') || !WP_DEBUG) {
    // Disable GDYMC for non-authenticated users
    add_filter('gdymc_support', function($support) {
        return is_user_logged_in() && current_user_can('edit_pages');
    });
    
    // Minify output
    add_filter('gdymc_minify_output', '__return_true');
}
```

## WordPress Multisite

GDYMC works with WordPress Multisite. Each site maintains its own modules and content.

**Network-wide module folder:**

```php
add_filter('gdymc_modules_folder', 'network_module_folder');

function network_module_folder($folders) {
    // Add network-wide modules
    $folders[] = WP_CONTENT_DIR . '/network-modules';
    return $folders;
}
```

## See Also

- [API Reference](API-Reference.md) - Complete API documentation
- [Module Development](Module-Development.md) - Creating custom modules
- [Usage Guide](Usage.md) - Using GDYMC features
