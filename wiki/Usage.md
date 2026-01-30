# Usage Guide

This comprehensive guide covers all features and functionality of GDY Modular Content.

## Table of Contents

- [Admin Bar](#admin-bar)
- [Module Bar](#module-bar)
- [Content Editing](#content-editing)
- [Module Management](#module-management)
- [Preview Modes](#preview-modes)
- [Batch Operations](#batch-operations)
- [Advanced Features](#advanced-features)

## Admin Bar

The GDYMC admin bar appears at the top of pages when you're logged in with editing permissions.

### Admin Bar Buttons

**Left Side:**
- **Modules dropdown**: Add new modules to the page
- **Media**: Quick access to WordPress media library
- **View dropdown**: Preview mode controls
  - Soft preview toggle
  - Hard preview toggle
  - Logout option

**Right Side:**
- **Format buttons**: Text formatting options (when text is selected)
  - Bold, italic, underline
  - Strikethrough, superscript, subscript
  - Headings (H1-H6)
  - Alignment options
  - Lists
  - Links
  - Clear formatting

### Keyboard Shortcuts

- `CMD+Shift+E` (Mac) / `Ctrl+Shift+E` (Windows): Toggle hard preview
- Standard text shortcuts work in editable fields (CMD+B for bold, etc.)

## Module Bar

Each module has its own module bar that appears when you hover over it.

### Module Bar Buttons

**Left Side:**
- **Move handle**: Drag to reorder modules
- **Module type indicator**: Shows the module name

**Right Side:**
- **Settings**: Open module options
- **Delete**: Remove the module
- Additional buttons can be added via hooks

## Content Editing

### Text Content

**Basic Editing:**
```php
// In your module template
<?php contentCreate('title', 'text'); ?>
```

**With Options:**
```php
// Maximum length
<?php contentCreate('title', 'text', array('maxlength' => 100)); ?>

// Multiline text
<?php contentCreate('description', 'text', array('multiline' => true)); ?>
```

**Features:**
- Click to edit
- Real-time formatting toolbar
- Auto-save
- Character limit warnings
- Support for HTML tags

### Images

**Single Image:**
```php
<?php contentCreate('hero', 'image', array(
    'width' => 1200,
    'height' => 600
)); ?>
```

**With Custom Rendering:**
```php
<?php 
contentCreate('hero', 'image', array(
    'width' => 1200,
    'height' => 600,
    'renderer' => function($image_id, $content) {
        // Custom rendering logic
        return wp_get_attachment_image($image_id, 'large');
    }
)); 
?>
```

**Features:**
- Upload or select from media library
- Built-in cropping tool
- Alt text and captions
- Responsive image support
- Multiple size definitions

### Galleries

**Basic Gallery:**
```php
<?php contentCreate('photos', 'gallery'); ?>
```

**With Custom Rendering:**
```php
<?php 
contentCreate('photos', 'gallery', array(
    'renderer' => function($images) {
        // Custom gallery HTML
        $html = '<div class="my-gallery">';
        foreach($images as $i => $image) {
            $html .= '<div class="gallery-item">';
            $html .= wp_get_attachment_image($image['id'], 'thumbnail');
            $html .= '</div>';
        }
        $html .= '</div>';
        return $html;
    }
)); 
?>
```

**Features:**
- Multiple image selection
- Drag-to-reorder
- Individual image captions
- Search functionality
- Infinite scroll in media manager

### Tables

**Editable Table:**
```php
<?php contentCreate('pricing', 'table', array(
    'rows' => 5,
    'cols' => 3
)); ?>
```

**Features:**
- Click cells to edit
- Add/remove rows and columns
- HTML content support
- Saved as JSON
- Preview mode

### Content Outside Modules

You can also use content outside of module areas:

```php
<?php 
// In any template file
contentCreate('page_title', 'text', array('object_id' => get_the_ID())); 
?>
```

### Checking Content Existence

Use `contentCheck()` to conditionally display elements:

```php
<?php if (contentCheck('hero')): ?>
    <div class="hero-wrapper">
        <?php contentCreate('hero', 'image'); ?>
    </div>
<?php endif; ?>
```

Multiple content IDs:
```php
<?php if (contentCheck(array('title', 'description'))): ?>
    <!-- Only show if both exist -->
<?php endif; ?>
```

## Module Management

### Adding Modules

1. Click the **Modules** button in the admin bar
2. Select a module type from the dropdown
3. The module appears at the bottom of the module area
4. Drag to reorder if needed

### Moving Modules

**Within the Same Page:**
- Hover over module to show module bar
- Click and drag the move handle
- Drop in desired position

**Between Pages:**
1. Open module settings
2. Navigate to batch operations
3. Select "Move to another page"
4. Choose destination page
5. Confirm action

### Copying Modules

1. Open module settings
2. Navigate to batch operations
3. Select "Copy to another page"
4. Choose destination page
5. Module is duplicated to the new location

### Deleting Modules

**Single Module:**
1. Hover over module
2. Click delete button (X)
3. Confirm deletion

**All Modules of a Type:**
1. Access module options for any module of that type
2. Navigate to "Defaults" tab
3. Click "Delete all modules of this type"
4. Confirm deletion

### Module Visibility

Control when modules are visible:

1. Open module settings
2. Go to **Visibility** tab
3. Options:
   - **Visible/Hidden**: Toggle module visibility
   - **Timed Visibility**: Set start/end dates
   - Module gets `gdymc_timed` class when timers are active

## Preview Modes

### Soft Preview

**Purpose**: See content as visitors see it, but keep editing controls.

**Features:**
- Admin bar remains visible
- Module bars hidden
- Content not editable
- Can still access module options
- Good for quick checks

**Activate:**
- Click eye icon in admin bar View menu
- Or use the preview dropdown

### Hard Preview

**Purpose**: See exactly what visitors see.

**Features:**
- All admin elements hidden
- Edit capabilities removed
- User capabilities adjusted
- `is-logged` class removed from body
- Perfect for final review

**Activate:**
- Click hard preview button in View menu
- Use keyboard shortcut: `CMD+Shift+E`
- Can be opened in new tab

**Note**: Unsaved content warning appears when activating.

## Batch Operations

Batch operations allow you to manage multiple modules at once.

### Available Batch Actions

1. **Move modules to another post**
   - Select multiple modules
   - Choose destination
   - All selected modules moved

2. **Copy modules to another post**
   - Select multiple modules
   - Choose destination
   - Modules duplicated to new location

3. **Delete module type**
   - Remove all modules of a specific type
   - Option to convert to another type
   - Useful when removing a module from theme

### Using Batch Operations

1. Open any module's settings
2. Look for batch operation options
3. Select desired action
4. Choose target modules/pages
5. Confirm operation

## Advanced Features

### Module Options

Create custom settings for your modules:

```php
// In module's index.php
optionCreate('background_color', 'text', array(
    'label' => 'Background Color',
    'default' => '#ffffff'
));

// Retrieve option value
$bg_color = optionGet('background_color');
```

**Available Option Types:**
- `text` - Single line text
- `textarea` - Multi-line text
- `select` - Dropdown
- `checkbox` - Boolean
- `radio` - Radio buttons
- `hidden` - Hidden field
- `password` - Password field
- HTML5 types: `number`, `date`, `color`, `range`, `email`, `url`, etc.

### Content Swapping

Swap content between two content areas:

1. Click the swap button in the content editor
2. Select source and target content IDs
3. Content is exchanged

### Image Editing

**Frontend Image Cropping:**
1. Click on an image
2. Select "Edit" or choose a new image
3. Use the cropper tool to adjust
4. Set crop area
5. Save changes

**Features:**
- Visual cropping interface
- Maintains aspect ratio
- Preview while cropping
- Crop position saved per image

### Link Management

**Adding Links:**
1. Select text
2. Click link button in format toolbar
3. Choose link type:
   - **URL**: External link
   - **Post/Page**: Internal content
   - **Category**: Taxonomy link
4. Set options:
   - Open in new tab
   - Additional CSS classes
5. Insert link

**Link Window Features:**
- Search posts and pages
- Filter by post type
- Date information
- Category selection
- URL encoding

### Custom Module Attributes

Filter module container attributes:

```php
add_filter('gdymc_module_attributes', 'custom_module_attrs', 10, 2);

function custom_module_attrs($attrs, $module) {
    $attrs['data-module-id'] = $module->id;
    return $attrs;
}
```

### Module Classes

Filter module container classes:

```php
add_filter('gdymc_module_class', 'custom_module_class', 10, 2);

function custom_module_class($classes, $module) {
    $classes[] = 'module-' . $module->type;
    return $classes;
}
```

### Area Classes

Filter area container classes:

```php
add_filter('gdymc_area_class', 'custom_area_class');

function custom_area_class($classes) {
    $classes[] = 'my-custom-area';
    return $classes;
}
```

## Tips and Best Practices

1. **Use Preview Modes**: Always check both soft and hard preview before publishing
2. **Set Maximum Lengths**: Use `maxlength` option to prevent overly long content
3. **Check Content Existence**: Use `contentCheck()` to avoid empty containers
4. **Leverage Module Options**: Make modules configurable instead of hard-coding values
5. **Test Responsiveness**: Check modules on different screen sizes
6. **Use Proper Image Sizes**: Define appropriate width/height for images
7. **Name Content IDs Clearly**: Use descriptive names like 'hero_image' not 'img1'
8. **Organize Modules**: Use logical ordering and grouping
9. **Clean Up Unused Modules**: Delete modules you're not using
10. **Backup Before Batch Operations**: Batch actions can affect multiple modules

## Troubleshooting

For common issues and solutions, see the [Troubleshooting Guide](Troubleshooting.md).

For developer documentation, see the [API Reference](API-Reference.md).
