# Installation Guide

This guide will walk you through installing and setting up GDY Modular Content on your WordPress site.

## Requirements

Before installing, ensure your environment meets these requirements:

- WordPress 3.6 or higher (tested up to 6.7.2)
- PHP 5.6 or higher
- User capability: `edit_pages`
- Write permissions for the modules folder

## Installation Methods

### Method 1: WordPress Plugin Directory

1. Log in to your WordPress admin dashboard
2. Navigate to **Plugins** → **Add New**
3. Search for "GDY Modular Content"
4. Click **Install Now**
5. After installation, click **Activate**

### Method 2: Manual Upload via Dashboard

1. Download the plugin ZIP file from the [WordPress plugin directory](https://wordpress.org/plugins/) or [GitHub releases](https://github.com/fouadvollmergut/gdy-modular-content/releases)
2. Log in to your WordPress admin dashboard
3. Navigate to **Plugins** → **Add New**
4. Click **Upload Plugin** at the top of the page
5. Choose the downloaded ZIP file
6. Click **Install Now**
7. After installation, click **Activate**

### Method 3: Manual FTP Installation

1. Download the plugin ZIP file
2. Extract the ZIP file on your computer
3. Upload the `gdy-modular-content` folder to `/wp-content/plugins/` directory via FTP
4. Log in to your WordPress admin dashboard
5. Navigate to **Plugins**
6. Find "GDY Modular Content" in the list and click **Activate**

## Post-Installation Setup

### 1. Create Modules Folder

After activating the plugin, you need to create a modules folder in your theme:

```bash
# In your theme directory
mkdir modules
```

By default, the plugin looks for modules in `./wp-content/modules`. To use a theme-specific modules folder, add this to your `functions.php`:

```php
add_filter( 'gdymc_modules_folder', 'gdymc_folder' );

function gdymc_folder($module_folders) {
    array_push($module_folders, get_template_directory() . '/modules');
    return $module_folders;
}
```

### 2. Add Example Modules (Optional)

To get started quickly, you can download example modules:

1. Visit [GDY Modular Content – Example Modules](https://github.com/fouadvollmergut/gdymc-example-modules)
2. Download or clone the example modules
3. Place them in your `modules` folder

### 3. Create Module Areas

Add module areas to your theme templates where you want modular content to appear.

#### Option A: Template Function

Add this code to your template files (e.g., `single.php`, `page.php`, `index.php`):

```php
<?php if( function_exists( 'areaCreate' ) ) areaCreate(); ?>
```

Example in `index.php`:

```php
<?php get_header(); ?>
    <?php if( have_posts() ): while( have_posts() ): the_post(); ?>
        <?php if( function_exists( 'areaCreate' ) ) areaCreate(); ?>
    <?php endwhile; endif; ?>
<?php get_footer(); ?>
```

#### Option B: Shortcode

Alternatively, you can use the shortcode in the WordPress editor:

```
[gdymc_area]
```

Add this shortcode to any page or post content where you want a module area.

## Verification

After installation, verify everything is working:

1. Navigate to the frontend of a page with a module area
2. If you're logged in with sufficient permissions, you should see the GDYMC admin bar at the top
3. Click on the modules button in the admin bar to add your first module

## Troubleshooting Installation

### Plugin doesn't appear in the plugins list

- Ensure the folder is named `gdy-modular-content` in `/wp-content/plugins/`
- Check file permissions (folders should be 755, files 644)
- Verify your PHP version meets the minimum requirement (5.6)

### No modules folder warning

- Make sure you've created the modules folder
- Verify the path in your `functions.php` filter is correct
- Check folder permissions (should be writable)

### Admin bar doesn't appear

- Ensure you're logged in with a user account that has `edit_pages` capability
- Check if you've added a module area using `areaCreate()` or the shortcode
- Clear your browser cache and WordPress cache

## Next Steps

Once installation is complete, proceed to:

- [Getting Started](Getting-Started.md) - Learn the basics
- [Module Development](Module-Development.md) - Create your first module
- [Usage Guide](Usage.md) - Learn all the features

## Upgrading

### From Previous Versions

The plugin automatically handles database migrations when upgrading. However:

- **From 0.8.6**: Module option hooks have changed. Update your custom modules accordingly.
- **From 0.8.5**: Default module folder location has changed to `/wp-content/modules`
- **From 0.7.8**: Module hooks now receive only the module object as a parameter
- **From 0.6.4**: Old module option system is deprecated. Migrate to the new system for best experience.

Always backup your database before upgrading!

## Uninstallation

To completely remove the plugin:

1. Navigate to **Plugins** in WordPress admin
2. Deactivate GDY Modular Content
3. Click **Delete**
4. Manually remove the modules folder if desired
5. Remove the `gdymc_modules_folder` filter from `functions.php`

Note: Module data is stored in WordPress postmeta. If you want to remove all plugin data, you'll need to manually clean up the database.
