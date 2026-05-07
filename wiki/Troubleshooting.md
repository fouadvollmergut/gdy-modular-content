# Troubleshooting

Common issues and solutions for GDY Modular Content.

## Table of Contents

- [Installation Issues](#installation-issues)
- [Admin Bar Issues](#admin-bar-issues)
- [Module Issues](#module-issues)
- [Content Issues](#content-issues)
- [Image Issues](#image-issues)
- [Preview Issues](#preview-issues)
- [Performance Issues](#performance-issues)
- [Compatibility Issues](#compatibility-issues)

## Installation Issues

### Plugin doesn't appear after installation

**Symptoms:** Plugin doesn't show in plugins list

**Solutions:**
1. Check folder name is exactly `gdy-modular-content`
2. Verify folder is in `/wp-content/plugins/`
3. Check file permissions (folders: 755, files: 644)
4. Ensure PHP version is 5.6 or higher
5. Check for PHP errors in error log

### Warning: No modules folder

**Symptoms:** Error message about missing modules folder

**Solutions:**
1. Create a `modules` folder in your theme directory
2. Add the filter to `functions.php`:
```php
add_filter('gdymc_modules_folder', 'gdymc_folder');
function gdymc_folder($module_folders) {
    array_push($module_folders, get_template_directory() . '/modules');
    return $module_folders;
}
```
3. Check folder permissions (must be readable)
4. Verify the path is correct using absolute filesystem paths

### No modules available

**Symptoms:** "No modules" error message

**Solutions:**
1. Add at least one module folder to your modules directory
2. Each module must have an `index.php` file
3. Download example modules from [GitHub](https://github.com/fouadvollmergut/gdymc-example-modules)
4. Clear any caching plugins
5. Check module folder permissions

## Admin Bar Issues

### Admin bar doesn't appear

**Symptoms:** No GDYMC admin bar visible on frontend

**Solutions:**
1. **Check you're logged in** with a user that has `edit_pages` capability
2. **Verify module area exists** - Add `areaCreate()` to your template
3. **Check theme compatibility** - Ensure theme calls `wp_head()` and `wp_footer()`
4. **Disable conflicting plugins** - Try disabling other plugins temporarily
5. **Clear browser cache**
6. **Check preview mode** - Hard preview hides the admin bar

**Debug:**
```php
// Add to functions.php temporarily
add_action('wp_footer', function() {
    if (function_exists('gdymc_logged')) {
        echo '<!-- GDYMC Logged: ' . (gdymc_logged() ? 'Yes' : 'No') . ' -->';
    }
});
```

### Admin bar appears but is broken

**Symptoms:** Admin bar visible but buttons don't work

**Solutions:**
1. Check for JavaScript errors in browser console (F12)
2. Verify jQuery is loaded
3. Check for conflicting JavaScript from other plugins
4. Ensure WordPress scripts are properly enqueued
5. Clear browser and plugin caches

### Can't see format buttons

**Symptoms:** Text formatting buttons missing

**Solutions:**
1. Format buttons only appear when text is selected
2. Ensure you're in edit mode (not preview mode)
3. Select text within an editable field
4. Check screen width - buttons may be hidden on small screens

## Module Issues

### Can't add modules

**Symptoms:** Module button doesn't work or modules don't appear after clicking

**Solutions:**
1. **Check for unsaved content** - Save existing content before adding modules
2. **JavaScript errors** - Check browser console for errors
3. **AJAX issues** - Check network tab for failed requests
4. **Permissions** - Ensure user has `edit_pages` capability
5. **Theme compatibility** - Verify theme properly supports WordPress AJAX

### Modules disappear after page reload

**Symptoms:** Modules added but not visible after refresh

**Solutions:**
1. Check database for the module meta:
```php
// Temporary debug code
$modules = get_post_meta(get_the_ID(), '_gdymc_modulelist', true);
var_dump($modules);
```
2. Verify AJAX save is completing successfully
3. Check for database errors in WordPress debug log
4. Ensure post/page ID is correct
5. Check for conflicting plugins that modify postmeta

### Module content not saving

**Symptoms:** Content changes don't persist

**Solutions:**
1. Check browser console for AJAX errors
2. Verify nonce is valid (not expired)
3. Check WordPress debug log for errors
4. Ensure `edit_pages` capability
5. Check for conflicting plugins
6. Verify database write permissions
7. Try increasing PHP `max_input_vars` if saving large content

### Module options not showing

**Symptoms:** Can't access module settings

**Solutions:**
1. Ensure module has `optionCreate()` calls in `index.php`
2. Check that options are defined before `contentCreate()` calls
3. Verify module `index.php` syntax is correct
4. Clear any PHP opcode cache
5. Check for PHP errors in the module file

### Can't delete modules

**Symptoms:** Delete button doesn't work

**Solutions:**
1. Check browser console for JavaScript errors
2. Verify AJAX requests are completing
3. Ensure user has delete permissions
4. Try clicking confirmation dialog
5. Check for conflicting click handlers

## Content Issues

### Content not editable

**Symptoms:** Can't click or edit content fields

**Solutions:**
1. **Verify you're logged in** with appropriate permissions
2. **Check preview mode** - Exit soft/hard preview
3. **Verify `contentCreate()` syntax** is correct
4. **Check z-index issues** - Other elements may be overlapping
5. **JavaScript errors** - Check browser console

### Text formatting not working

**Symptoms:** Format buttons don't apply changes

**Solutions:**
1. Select text first, then click format button
2. Ensure you're in edit mode
3. Check for JavaScript errors
4. Try refreshing the page
5. Clear browser cache

### Content shows raw HTML

**Symptoms:** HTML tags visible instead of formatted content

**Solutions:**
1. Check that you're using `contentCreate()` not `contentGet()`
2. Verify content type is set correctly ('text', not 'html')
3. Ensure output is not being double-escaped
4. Check theme's escaping functions

## Image Issues

### Can't upload images

**Symptoms:** Upload button doesn't work or images fail to upload

**Solutions:**
1. **Check file size** - Ensure under PHP `upload_max_filesize`
2. **Check file type** - Verify file type is allowed in WordPress
3. **Verify permissions** - User needs `upload_files` capability
4. **Server permissions** - Check `/wp-content/uploads/` is writable
5. **Increase limits** in `php.ini`:
```ini
upload_max_filesize = 64M
post_max_size = 64M
max_execution_time = 300
```

### Image cropper not working

**Symptoms:** Crop tool doesn't load or save

**Solutions:**
1. Check that GD or ImageMagick is installed
2. Verify WordPress can create image thumbnails
3. Check for JavaScript errors
4. Ensure source image is large enough
5. Verify server has enough memory:
```php
// In wp-config.php
define('WP_MEMORY_LIMIT', '256M');
```

### Images not displaying

**Symptoms:** Broken image icons or no images show

**Solutions:**
1. Check image ID exists in media library
2. Verify attachment is not deleted
3. Check file actually exists in uploads folder
4. Verify correct image size is registered
5. Check `contentCreate()` parameters:
```php
contentCreate('image', 'image', array(
    'width' => 800,
    'height' => 600
));
```

### Image quality poor

**Symptoms:** Uploaded images look compressed or low quality

**Solutions:**
1. Increase WordPress JPEG quality:
```php
add_filter('jpeg_quality', function() { return 100; });
add_filter('wp_editor_set_quality', function() { return 100; });
```
2. Use PNG for images requiring high quality
3. Upload larger source images
4. Disable unnecessary image processing plugins

## Preview Issues

### Can't exit preview mode

**Symptoms:** Stuck in soft or hard preview

**Solutions:**
1. Click preview toggle button again
2. Use keyboard shortcut: `CMD+Shift+E` (Mac) / `Ctrl+Shift+E` (Windows)
3. Delete preview cookies:
```javascript
// In browser console
document.cookie = 'gdymc_softpreview=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
document.cookie = 'gdymc_hardpreview=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
```
4. Clear browser cookies and refresh

### Preview shows old content

**Symptoms:** Preview doesn't reflect recent changes

**Solutions:**
1. Ensure changes are saved (watch for save indicators)
2. Hard refresh browser: `Ctrl+Shift+R` (Windows) / `CMD+Shift+R` (Mac)
3. Clear browser cache
4. Clear WordPress cache plugins
5. Disable server-side caching temporarily

### Hard preview not working

**Symptoms:** Still see admin elements in hard preview

**Solutions:**
1. Clear all cookies
2. Check filter isn't preventing hard preview:
```php
// Remove any filters like this
// remove_filter('gdymc_hardpreview', '__return_false');
```
3. Verify JavaScript is enabled
4. Check browser console for errors

## Performance Issues

### Slow admin bar load

**Symptoms:** Page takes long time to load with GDYMC active

**Solutions:**
1. **Limit module assets** - Only load when module is placed:
```php
if (gdymc_module_is_placed('my-module')) {
    wp_enqueue_script(...);
}
```
2. **Optimize modules** - Reduce database queries in modules
3. **Use caching** - Enable object caching in WordPress
4. **Limit modules** - Reduce number of modules per page
5. **Optimize images** - Use appropriate image sizes

### Slow content saving

**Symptoms:** Long delay when saving content

**Solutions:**
1. Check server response time in browser Network tab
2. Optimize database - Run database optimization
3. Increase PHP `max_execution_time`
4. Check for slow database queries
5. Disable unnecessary plugins during editing

### Memory exhausted errors

**Symptoms:** PHP memory limit errors

**Solutions:**
1. Increase WordPress memory limit in `wp-config.php`:
```php
define('WP_MEMORY_LIMIT', '256M');
define('WP_MAX_MEMORY_LIMIT', '512M');
```
2. Optimize image processing
3. Reduce number of modules loaded simultaneously
4. Check for memory leaks in custom modules

## Compatibility Issues

### Theme conflicts

**Symptoms:** GDYMC not working with specific theme

**Solutions:**
1. **Verify theme calls** `wp_head()` and `wp_footer()`
2. **Check CSS conflicts** - Theme styles may override GDYMC
3. **Test with default theme** - Switch to Twenty Twenty-One temporarily
4. **Check JavaScript conflicts** - Look for console errors
5. **Update theme** - Ensure theme is up to date

### Plugin conflicts

**Symptoms:** Issues when other plugins are active

**Common conflicts:**

**Page builders** (Elementor, Visual Composer, etc.):
- May interfere with frontend editing
- Try adding GDYMC area outside page builder sections
- Use GDYMC on standard templates, page builder on others

**Caching plugins** (WP Super Cache, W3 Total Cache):
- Disable caching for logged-in users
- Exclude GDYMC AJAX from caching
- Clear cache after making changes

**Security plugins** (Wordfence, Sucuri):
- May block AJAX requests
- Whitelist GDYMC AJAX endpoints
- Check firewall logs for blocked requests

**Solutions:**
1. Disable plugins one by one to identify conflict
2. Check plugin settings for AJAX exclusions
3. Update all plugins to latest versions
4. Contact plugin authors about conflicts

### WordPress version issues

**Symptoms:** Issues after WordPress update

**Solutions:**
1. Ensure GDYMC is updated to latest version
2. Check WordPress compatibility in readme.txt
3. Review changelog for breaking changes
4. Test in WordPress debug mode
5. Report incompatibilities on GitHub

## Database Issues

### Module data not found

**Symptoms:** Modules exist but content is empty

**Solutions:**
1. Check postmeta table for GDYMC data:
```sql
SELECT * FROM wp_postmeta WHERE meta_key LIKE '_gdymc_%';
```
2. Verify module list meta exists:
```sql
SELECT * FROM wp_postmeta WHERE meta_key = '_gdymc_modulelist';
```
3. Check data format (should be JSON for newer versions)
4. Run database repair if needed

### Content not migrating after update

**Symptoms:** Content missing after plugin update

**Solutions:**
1. Check if migration hooks ran:
```php
// Get current plugin version
$version = get_option('gdymc_version');
```
2. Manually trigger upgrade:
```php
do_action('gdymc_plugin_upgrade', $old_version, GDYMC_PLUGIN_VERSION);
```
3. Backup and restore from previous backup if needed
4. Contact support with error details

## Debug Mode

### Enable WordPress Debug Mode

Add to `wp-config.php`:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
define('SCRIPT_DEBUG', true);
```

Then check `/wp-content/debug.log` for errors.

### GDYMC Specific Debugging

```php
// Add to functions.php temporarily
add_action('wp_footer', 'gdymc_debug_info');

function gdymc_debug_info() {
    if (!current_user_can('manage_options')) return;
    
    echo '<!-- GDYMC Debug Info';
    echo "\nLogged: " . (gdymc_logged() ? 'Yes' : 'No');
    echo "\nObject ID: " . gdymc_object_id();
    echo "\nObject Type: " . gdymc_object_type();
    echo "\nModule Folders: " . print_r($GLOBALS['gdymc_module_folders'], true);
    echo "\nPlaced Modules: " . print_r(gdymc_get_placed_modules(), true);
    echo "\n-->";
}
```

## Getting Help

If you can't resolve your issue:

1. **Check existing GitHub issues**: [GitHub Issues](https://github.com/fouadvollmergut/gdy-modular-content/issues)
2. **Create a new issue** with:
   - WordPress version
   - PHP version
   - GDYMC version
   - Active theme and plugins
   - Error messages (from console and debug log)
   - Steps to reproduce
3. **WordPress Support Forums**: For general WordPress.org plugin support
4. **Stack Overflow**: Tag questions with `gdy-modular-content`

## See Also

- [Installation Guide](Installation.md)
- [Usage Guide](Usage.md)
- [API Reference](API-Reference.md)
- [Configuration](Configuration.md)
