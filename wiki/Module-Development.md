# Module Development

This guide covers everything you need to know to create custom modules for GDY Modular Content.

## Table of Contents

- [Module Basics](#module-basics)
- [Module Structure](#module-structure)
- [Content Functions](#content-functions)
- [Module Options](#module-options)
- [Module Functions](#module-functions)
- [Advanced Topics](#advanced-topics)
- [Best Practices](#best-practices)

## Module Basics

### What is a Module?

A module is a reusable content component that can be added to module areas. Each module consists of:

- A folder in the modules directory
- An `index.php` file containing the module template
- Optional `thumb.jpg` for the module thumbnail
- Optional `functions.php` for module-specific logic
- Optional `style.css` for module-specific styles
- Optional `script.js` for module-specific JavaScript

### Module Folder Structure

```
modules/
└── my-module/
    ├── index.php       (required - module template)
    ├── thumb.jpg       (optional - 200x200px thumbnail)
    ├── functions.php   (optional - module logic)
    ├── style.css       (optional - module styles)
    └── script.js       (optional - module scripts)
```

## Module Structure

### Basic Module Template

Create a folder in your modules directory and add an `index.php`:

```php
<?php
/**
 * Module Name: My Custom Module
 * Description: A simple example module
 */

// Check if GDYMC is active
if (!function_exists('contentCreate')) return;
?>

<div class="my-module">
    <h2><?php contentCreate('title', 'text'); ?></h2>
    <p><?php contentCreate('description', 'text', array('multiline' => true)); ?></p>
    <?php contentCreate('image', 'image', array('width' => 800, 'height' => 600)); ?>
</div>
```

### Module Header

The module header (in comments) provides metadata:

```php
<?php
/**
 * Module Name: Hero Section
 * Description: Large hero section with background image and text
 * Version: 1.0
 * Author: Your Name
 */
?>
```

### Module Thumbnail

Add a `thumb.jpg` (200x200px) to your module folder. This image appears in the module selection dropdown.

If no thumbnail is provided, GDYMC will use a default placeholder.

## Content Functions

### contentCreate()

The main function for creating editable content.

**Syntax:**
```php
contentCreate($id, $type, $options = array());
```

**Parameters:**
- `$id` (string|int): Unique identifier for the content
- `$type` (string): Content type ('text', 'image', 'gallery', 'table')
- `$options` (array): Optional configuration

**Text Content:**

```php
// Simple text
contentCreate('title', 'text');

// With options
contentCreate('title', 'text', array(
    'maxlength' => 100,          // Character limit
    'multiline' => true,         // Allow line breaks
    'placeholder' => 'Enter title' // Placeholder text
));
```

**Image Content:**

```php
contentCreate('hero', 'image', array(
    'width' => 1200,    // Image width
    'height' => 600,    // Image height
    'renderer' => function($image_id, $content) {
        // Custom rendering
        return wp_get_attachment_image($image_id, 'full');
    }
));
```

**Gallery Content:**

```php
contentCreate('gallery', 'gallery', array(
    'renderer' => function($images, $i) {
        // Custom gallery rendering
        $html = '<div class="gallery">';
        foreach ($images as $index => $image) {
            $html .= '<div class="gallery-item">';
            $html .= wp_get_attachment_image($image['id'], 'medium');
            if (!empty($image['caption'])) {
                $html .= '<p>' . esc_html($image['caption']) . '</p>';
            }
            $html .= '</div>';
        }
        $html .= '</div>';
        return $html;
    }
));
```

**Table Content:**

```php
contentCreate('pricing', 'table', array(
    'rows' => 5,
    'cols' => 3
));
```

### contentGet()

Retrieve content value without rendering.

```php
$title = contentGet('title');
$image_id = contentGet('hero');

// For other objects
$title = contentGet('title', get_the_ID());
```

### contentCheck()

Check if content exists.

```php
// Single content
if (contentCheck('hero')) {
    // Content exists
}

// Multiple content IDs
if (contentCheck(array('title', 'description'))) {
    // All specified content exists
}
```

## Module Options

Module options allow you to add settings to your modules.

### optionCreate()

Create a module option.

**Syntax:**
```php
optionCreate($id, $type, $options = array());
```

**Text Option:**

```php
optionCreate('background_color', 'text', array(
    'label' => 'Background Color',
    'default' => '#ffffff',
    'description' => 'Enter a hex color code'
));
```

**Textarea Option:**

```php
optionCreate('custom_css', 'textarea', array(
    'label' => 'Custom CSS',
    'default' => '',
    'rows' => 10
));
```

**Select Option:**

```php
optionCreate('layout', 'select', array(
    'label' => 'Layout',
    'options' => array(
        'left' => 'Image Left',
        'right' => 'Image Right',
        'center' => 'Centered'
    ),
    'default' => 'left'
));
```

**Checkbox Option:**

```php
optionCreate('show_caption', 'checkbox', array(
    'label' => 'Show Caption',
    'default' => true
));
```

**Color Picker:**

```php
optionCreate('text_color', 'color', array(
    'label' => 'Text Color',
    'default' => '#000000'
));
```

**Number Input:**

```php
optionCreate('columns', 'number', array(
    'label' => 'Number of Columns',
    'default' => 3,
    'min' => 1,
    'max' => 12
));
```

**Date Input:**

```php
optionCreate('publish_date', 'date', array(
    'label' => 'Publish Date',
    'default' => date('Y-m-d')
));
```

### optionGet()

Retrieve option value.

```php
$bg_color = optionGet('background_color');
$layout = optionGet('layout');
$show_caption = optionGet('show_caption');

// With default fallback
$columns = optionGet('columns', 3);
```

### optionShow()

Display option value (escaped).

```php
<div style="background-color: <?php optionShow('background_color'); ?>">
    Content
</div>
```

### optionExists()

Check if option exists.

```php
if (optionExists('background_color')) {
    // Option is set
}
```

### Using Options in Templates

```php
<?php
$layout = optionGet('layout', 'left');
$bg_color = optionGet('background_color', '#ffffff');
?>

<div class="module-container layout-<?php echo esc_attr($layout); ?>" 
     style="background-color: <?php echo esc_attr($bg_color); ?>">
    <?php contentCreate('title', 'text'); ?>
    <?php contentCreate('image', 'image'); ?>
</div>
```

## Module Functions

### functions.php

Create a `functions.php` in your module folder for custom logic.

**Basic Structure:**

```php
<?php
// functions.php

// Don't load if GDYMC isn't active
if (!function_exists('gdymc_module_is_placed')) return;

// Only load scripts/styles if module is placed
add_action('wp_enqueue_scripts', 'my_module_assets');

function my_module_assets() {
    // Check if this module is placed on the current page
    if (!gdymc_module_is_placed(gdymc_module_name(__FILE__))) {
        return;
    }
    
    // Enqueue styles
    wp_enqueue_style(
        'my-module-style',
        gdymc_module_url(__FILE__) . '/style.css',
        array(),
        '1.0'
    );
    
    // Enqueue scripts
    wp_enqueue_script(
        'my-module-script',
        gdymc_module_url(__FILE__) . '/script.js',
        array('jquery'),
        '1.0',
        true
    );
}
```

### Module Hooks

**Before Module:**
```php
add_action('gdymc_module_before', 'my_before_module', 10, 1);

function my_before_module($module) {
    if ($module->type === 'my-module') {
        echo '<div class="module-wrapper">';
    }
}
```

**After Module:**
```php
add_action('gdymc_module_after', 'my_after_module', 10, 1);

function my_after_module($module) {
    if ($module->type === 'my-module') {
        echo '</div>';
    }
}
```

### Helper Functions

**gdymc_module_name($file)**
Get module name from file path.

```php
$module_name = gdymc_module_name(__FILE__);
```

**gdymc_module_path($file)**
Get absolute path to module folder.

```php
$module_path = gdymc_module_path(__FILE__);
require_once $module_path . '/includes/helper.php';
```

**gdymc_module_url($file)**
Get URL to module folder.

```php
$module_url = gdymc_module_url(__FILE__);
echo '<img src="' . $module_url . '/images/icon.png">';
```

**gdymc_module_is_placed($module_name)**
Check if module is placed on current page.

```php
if (gdymc_module_is_placed('my-module')) {
    // Module is on this page
}
```

## Advanced Topics

### Custom Content Rendering

**Image with Custom HTML:**

```php
contentCreate('hero', 'image', array(
    'width' => 1920,
    'height' => 1080,
    'renderer' => function($image_id, $content) {
        if (empty($image_id)) return '';
        
        $image = wp_get_attachment_image_src($image_id, 'full');
        $alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
        
        return sprintf(
            '<div class="hero-image" style="background-image: url(%s)" role="img" aria-label="%s"></div>',
            esc_url($image[0]),
            esc_attr($alt)
        );
    }
));
```

**Gallery with Lightbox:**

```php
contentCreate('gallery', 'gallery', array(
    'renderer' => function($images, $i) {
        $html = '<div class="lightbox-gallery">';
        foreach ($images as $index => $image) {
            $full = wp_get_attachment_image_src($image['id'], 'full');
            $thumb = wp_get_attachment_image_src($image['id'], 'thumbnail');
            
            $html .= sprintf(
                '<a href="%s" data-lightbox="gallery">
                    <img src="%s" alt="%s">
                </a>',
                esc_url($full[0]),
                esc_url($thumb[0]),
                esc_attr($image['caption'])
            );
        }
        $html .= '</div>';
        return $html;
    }
));
```

### Working with Module Object

Access module data via the global `$gdymc_module`:

```php
global $gdymc_module;

if ($gdymc_module) {
    echo 'Module ID: ' . $gdymc_module->id;
    echo 'Module Type: ' . $gdymc_module->type;
    echo 'Object ID: ' . $gdymc_module->object_id;
}
```

### Module Option Tabs

Create custom option tabs:

```php
// In functions.php

add_action('gdymc_moduleoptions_tabs', 'add_custom_tab');
add_action('gdymc_moduleoptions_content', 'add_custom_tab_content');

function add_custom_tab($module) {
    if ($module->type === 'my-module') {
        echo '<li data-tab="custom">Custom Settings</li>';
    }
}

function add_custom_tab_content($module) {
    if ($module->type === 'my-module') {
        echo '<div class="gdymc_options_tab" data-tab="custom">';
        // Custom option fields
        echo '</div>';
    }
}
```

### Responsive Images

Use WordPress responsive images:

```php
contentCreate('hero', 'image', array(
    'width' => 1920,
    'height' => 1080,
    'renderer' => function($image_id, $content) {
        return wp_get_attachment_image($image_id, 'full', false, array(
            'sizes' => '(max-width: 600px) 100vw, (max-width: 1200px) 50vw, 1920px'
        ));
    }
));
```

## Best Practices

### 1. Always Check GDYMC Exists

```php
if (!function_exists('contentCreate')) return;
```

### 2. Use Unique Content IDs

```php
// Good
contentCreate('hero_title', 'text');
contentCreate('hero_subtitle', 'text');

// Bad (might conflict)
contentCreate('title', 'text');
contentCreate('title2', 'text');
```

### 3. Provide Defaults

```php
optionCreate('columns', 'number', array(
    'label' => 'Columns',
    'default' => 3  // Always provide sensible defaults
));
```

### 4. Conditional Asset Loading

```php
if (gdymc_module_is_placed(gdymc_module_name(__FILE__))) {
    wp_enqueue_style(...);
}
```

### 5. Escape Output

```php
$title = optionGet('title');
echo '<h2>' . esc_html($title) . '</h2>';

$url = optionGet('link');
echo '<a href="' . esc_url($url) . '">Link</a>';
```

### 6. Use Semantic HTML

```php
<article class="my-module">
    <header>
        <h2><?php contentCreate('title', 'text'); ?></h2>
    </header>
    <div class="content">
        <?php contentCreate('body', 'text', array('multiline' => true)); ?>
    </div>
</article>
```

### 7. Mobile-First Approach

Design modules to be responsive from the start.

### 8. Accessibility

```php
// Use alt text for images
contentCreate('image', 'image', array(
    'width' => 800,
    'height' => 600
));

// Provide ARIA labels where needed
<div role="region" aria-label="<?php optionShow('section_label'); ?>">
```

### 9. Document Your Modules

Add clear comments explaining module purpose and usage:

```php
<?php
/**
 * Module Name: Feature Grid
 * Description: Display features in a responsive grid layout
 * 
 * Options:
 * - columns: Number of columns (1-4)
 * - show_icons: Display feature icons
 * 
 * Content:
 * - feature_1_title, feature_2_title, etc.
 * - feature_1_icon, feature_2_icon, etc.
 */
?>
```

### 10. Version Your Modules

Keep track of module versions for easier maintenance:

```php
/**
 * Module Name: My Module
 * Version: 1.2.0
 */
```

## Example: Complete Module

Here's a complete example of a well-structured module:

```php
<?php
/**
 * Module Name: Feature Box
 * Description: Displays a feature with icon, title, and description
 * Version: 1.0.0
 * Author: Your Name
 */

if (!function_exists('contentCreate')) return;

// Get options
$bg_color = optionGet('background_color', '#f5f5f5');
$text_color = optionGet('text_color', '#333333');
$icon_position = optionGet('icon_position', 'top');
?>

<div class="feature-box icon-<?php echo esc_attr($icon_position); ?>" 
     style="background-color: <?php echo esc_attr($bg_color); ?>; color: <?php echo esc_attr($text_color); ?>;">
    
    <?php if (contentCheck('icon')): ?>
        <div class="feature-icon">
            <?php contentCreate('icon', 'image', array(
                'width' => 100,
                'height' => 100
            )); ?>
        </div>
    <?php endif; ?>
    
    <div class="feature-content">
        <h3 class="feature-title">
            <?php contentCreate('title', 'text', array(
                'maxlength' => 100
            )); ?>
        </h3>
        
        <div class="feature-description">
            <?php contentCreate('description', 'text', array(
                'multiline' => true,
                'maxlength' => 500
            )); ?>
        </div>
    </div>
</div>

<?php
// Module options
optionCreate('background_color', 'color', array(
    'label' => 'Background Color',
    'default' => '#f5f5f5'
));

optionCreate('text_color', 'color', array(
    'label' => 'Text Color',
    'default' => '#333333'
));

optionCreate('icon_position', 'select', array(
    'label' => 'Icon Position',
    'options' => array(
        'top' => 'Top',
        'left' => 'Left',
        'right' => 'Right'
    ),
    'default' => 'top'
));
?>
```

## Next Steps

- Review [API Reference](API-Reference.md) for complete function documentation
- Check [Configuration](Configuration.md) for available filters and hooks
- See [Usage Guide](Usage.md) for managing modules

## Resources

- [Example Modules Repository](https://github.com/fouadvollmergut/gdymc-example-modules)
- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)
- [WordPress Theme Handbook](https://developer.wordpress.org/themes/)
