# Getting Started

This guide will help you get started with GDY Modular Content after installation.

## Prerequisites

Before you begin, make sure you have:

- [Installed GDY Modular Content](Installation.md)
- Created a modules folder in your theme
- Added at least one module to the modules folder
- Created a module area in your template

## Your First Module Area

### Understanding Module Areas

A **module area** is a section on your page where you can add, remove, and arrange modules. Think of it as a container for your modular content.

### Adding a Module Area

Add this code to your theme template where you want modules to appear:

```php
<?php if( function_exists( 'areaCreate' ) ) areaCreate(); ?>
```

Common places to add module areas:
- `single.php` - For individual blog posts
- `page.php` - For static pages
- `index.php` - For the main blog index
- `archive.php` - For archive pages
- Custom page templates

## Adding Your First Module

1. **Navigate to a page** with a module area (while logged in)
2. **Look for the GDYMC admin bar** at the top of the page
3. **Click the modules button** (usually in the top right corner)
4. **Select a module type** from the dropdown list
5. **Click to add** the module to your page

The module will appear on your page immediately!

## Editing Content

GDY Modular Content provides frontend editing, meaning you can edit content directly on the page.

### Editing Text

1. **Click on any text field** in your module
2. **Type to edit** the content
3. **Use the formatting toolbar** that appears for styling options:
   - Bold, italic, underline
   - Headings (H1-H6)
   - Lists (ordered and unordered)
   - Links
   - Alignment
4. **Click outside** or press Escape to finish editing

### Editing Images

1. **Click on an image** in your module
2. **Select an image** from the media library or upload a new one
3. **Crop if needed** using the built-in cropper
4. **Set alt text and caption** for accessibility
5. **Click "Use Image"** to apply

### Editing Galleries

1. **Click on the gallery area**
2. **Select multiple images** from the media library
3. **Arrange images** by dragging them in the desired order
4. **Add captions** to individual images
5. **Click "Use Images"** to apply

## Module Options

Each module can have custom options. To access them:

1. **Hover over a module** to see the module bar
2. **Click the settings icon** (usually a gear or cog)
3. **Edit options** in the overlay that appears:
   - **Content tab**: Module-specific settings
   - **Visibility tab**: Show/hide module, timing controls
   - **Defaults tab**: Module ID and type information

## Module Management

### Reordering Modules

1. **Hover over a module** to see the module bar
2. **Click and drag** the move handle (usually parallel lines)
3. **Drop the module** in the desired position

### Duplicating Modules

1. **Open module options** (click the settings icon)
2. **Navigate to appropriate tab** with duplicate option
3. **Click "Duplicate"** to create a copy

### Deleting Modules

1. **Hover over a module** to see the module bar
2. **Click the delete button** (usually an X or trash icon)
3. **Confirm deletion** in the dialog that appears

## Preview Modes

GDY Modular Content offers two preview modes:

### Soft Preview

- Shows how content appears to visitors
- Keeps the admin bar visible
- You can still access module options
- **Toggle**: Click the eye icon in the admin bar

### Hard Preview

- Shows exactly what visitors see
- Hides all admin elements
- Removes edit capabilities
- **Toggle**: Click the hard preview button or use `CMD+Shift+E` (Mac) / `Ctrl+Shift+E` (Windows)

## Saving Your Work

GDY Modular Content uses **auto-save**:

- Changes are saved automatically as you make them
- No need to click a "Save" button
- You'll see a warning if you try to leave with unsaved changes

## Content Types

GDY Modular Content supports various content types:

### Text
- Simple text editing with formatting
- Support for HTML elements
- Maximum length controls

### Image
- Single image with cropping support
- Alt text and captions
- Responsive image support

### Gallery
- Multiple images with sorting
- Individual image captions
- Custom rendering options

### Table
- Editable table cells
- Row and column management
- Saved as JSON data

### Options
- Custom module settings
- Various input types (text, textarea, select, checkbox, etc.)
- Persistent storage

### Button Group
- Grouped button inputs (since v0.9.90)

## Next Steps

Now that you know the basics, explore:

- [Usage Guide](Usage.md) - Learn advanced features
- [Module Development](Module-Development.md) - Create custom modules
- [API Reference](API-Reference.md) - Developer documentation
- [Configuration](Configuration.md) - Customize behavior

## Quick Tips

1. **Check your user permissions** - You need `edit_pages` capability to use GDYMC
2. **Use preview modes** to see how your content looks to visitors
3. **Create reusable modules** for content you use frequently
4. **Leverage module options** to make modules configurable
5. **Test on different devices** - The plugin is responsive but check your modules too

## Common Questions

**Q: Why don't I see the admin bar?**  
A: Make sure you're logged in with sufficient permissions and there's a module area on the page.

**Q: Can I use modules on custom post types?**  
A: Yes! Just add `areaCreate()` to the appropriate template.

**Q: How do I add my own custom modules?**  
A: See the [Module Development](Module-Development.md) guide.

**Q: Are changes saved immediately?**  
A: Yes, changes are auto-saved as you make them.

**Q: Can I undo changes?**  
A: The plugin saves immediately, so use preview modes to check before finalizing. Consider using WordPress revisions for post-level undo.

For more questions, see the [Troubleshooting](Troubleshooting.md) guide.
