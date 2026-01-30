# Changelog

Complete version history and changes for GDY Modular Content.

## Latest Version

### 0.9.984 (Current Stable)

For detailed changelog of versions 0.9.90 and later, please refer to the [GitHub repository releases](https://github.com/fouadvollmergut/gdy-modular-content/releases).

## Version History

### 0.9.90
- Add PHP 8.2 Support
- Introduce new content type: button group

### 0.9.88
- The custom renderer callback for gallery items now receives the counter variable `$i` as second parameter

### 0.9.87
- Support for other icons in admin bar and module bar
- Added `gdymc_imagesize` filter for filtering the default image size used in contentCreate images

### 0.9.86
- Updated plugin information
- Removed documentation links

### 0.9.85
- Same editable content wrapper classes for logged and unlogged users

### 0.9.84
- Removed batch editing until it's adjusted for multiple object types

### 0.9.83
- Fixes for the `optionInput` function including attribute escaping which allows the usage of JSON encoded values

### 0.9.82
- Bugfix for `optionGet` when receiving values of an external object

### 0.9.81
- Added option to add additional classes to links

### 0.9.8
- Fixed a bug where object contents were saved incorrectly/empty on the redirect from the parameter URL to the rewritten one

### 0.9.7
- Database update to the new JSON format for object contents

### 0.9.6
- Removed a remaining `var_dump`

### 0.9.5
- The logout link in the gdymc admin bar now redirects to the last viewed page
- Small updates on the options API functions
- Fixed a bug with object contents (the ones outside of modules)
- Changed the plugin header information because the old format may have caused problems with the repository stats

### 0.9.4
- Fixed an extremely rare bug that renders an unwanted button in the plugin's error windows

### 0.9.3
- The plugin only sets cookies when you are logged in and as soon as you logout the plugin removes its cookies. This removes the cookie footprint for regular users completely which is better for the stricter privacy guidelines now and in the future.

### 0.9.2
- Removed the `gdymc_automatic_module_area` filter which causes problems with multiple areas on one page. This results in some cases in the main area not being rendered.

### 0.9.1
- Added `gdymc_current_url` function which returns the current URL
- Changed the hard preview link. This should resolve some bugs especially when switching to hard preview on archives.

### 0.9.0

**Major Update**

- Minor style fixes
- Fixed a bug with `contentGet`/`contentCheck`
- Term meta introduced in WordPress 4.4 is now supported. You can use GDYMC with terms now.
- Listed module and content IDs are now saved as JSON in the database allowing strings for content IDs
- The plugin uses now its own AJAX handler (wrapping jQuery.ajax) that takes care of error handling
- Better support for hidden module options
- Changed how the preview works (the `gdymc_preview`, `gdymc_hardpreview` and `gdymc_softpreview` functions are still working the same)
- Switched to WordPress utils to manage cookies
- GDYMC supports now admin languages introduced with WordPress 4.7. So you can have a different language for GDYMC (the admin) than your site. That includes the GDYMC areas in the frontend.

### 0.8.9
- Changed the delete module type batch
- Added parameter for `get_edit_term_link()`
- Added fourth parameter to `add_post_meta` on `_gdymc_module_list` to prevent duplicates (In some rare cases it happened that there were added a lot of duplicate postmeta entries that caused massive performance issues)

### 0.8.8
- Fixed PHP notices
- Module deletion is now processed via the module class
- The edit link in the adminbar works now also on terms
- Added a GDYMC AJAX handler that takes care of any errors occurring
- Style fix for focused modules
- Fixed bug that causes contents not to show up if used outside of modules

### 0.8.7
- Styling fixes
- Joined style documents (only a single file is now loaded)
- Removed style support for old module options
- Fixed a bug occurring when deleting modules on duplicated posts (This happens for example in WPML)
- Output buffering is used to prevent empty option areas (so option tabs with no content are not visible anymore)
- Removed the `gdymc_time_ago` function because it wasn't used
- Removed the `metaExists` function and replaced it with the WordPress `metadata_exists` function
- Added image editing from frontend editor and image links
- The GDYMC_MODULE class now needs the objectID as parameter (That's because the id is not enough to identify modules, the id is stored in the meta which can be duplicated by plugins)
- The redirect after login and logout was removed
- Bugfix where frontend styles can affect the cropper results

### 0.8.6

**Important Update - Module Options Changed**

- Bugfix for content swapping
- Smaller bugfixes for PHP warnings
- Added some links to the documentation
- Fixed the action callback for the default gallery image caption
- Optimized the hooks for error messages (added several actions, changed the name for several filters)
- **All module functions are always loaded on `init`**. So the module functions should behave exactly like the theme function. Make sure you wrap your script and style loading function in a condition with `gdymc_module_is_placed( gdymc_module_name( __FILE__ ) )`
- **Changed the hooking system of module option tabs massively**
- Removed the width and height of the editable image container
- Added errors for multiple placed module areas and areas on wrong content types
- Removed the readme FAQs (If there are any questions the support area is now the right place for them)
- Responsive images are removed because WordPress has its own support for this since version 4.4. Default WordPress attachments are used now.
- Fixed a bug where the buttons of missing modules are shown for visitors
- The functions `gdymc_module_path`, `gdymc_module_relative` and `gdymc_module_url` allow now two additional parameters for better usage

### 0.8.5

**Important Update - Module Folder Changed**

- Changed the style reset and adjusted the styles
- Better syntax in modulebars and adminbar
- Removed the shortcuts for alignment because they were causing some conflicts with hard reload on some systems
- Removed the `gdymc_formatbuttons` action hook
- Changed the hooks for modulebar buttons into `gdymc_modulebarbuttons_left` and `gdymc_modulebarbuttons_right` ($module is passed as parameter)
- Moved the logout button to the view dropdown
- Added a media shortcut in the adminbar
- Fixed a rare bug in the module options with the module type setting when no modules exists
- Fixed a bug occurring with encoded characters when placing hyperlinks
- Fixed a bug "Illegal string offset" occurring in PHP 7+
- The `gdymc_text` tag surrounding editable tags is now a `span` tag for visitors to prevent syntactical errors
- Removed the extra container around editables texts because it has no effect on the h1 bug
- Renamed the `example-theme-files` folder to `example-files`
- Added the functions `gdymc_module_path`, `gdymc_module_relative` and `gdymc_module_url`
- **Changed the way modules are placed in the system. There is no support for child themes anymore.**
- **Changed the filter `gdymc_theme_modules_folder` into `gdymc_modules_folder`** (To change the module folder it is now necessary to specify a complete file system path)
- **The default module folder is now located in `./wp-content/modules`** to prevent any damage when using third party themes
- Removed the `gdymc_get_all_module_types` function. Use `gdymc_get_modules` instead.
- Changed the `gdymc_get_placed_module_types` function name to `gdymc_get_placed_modules`. The return is now an object based on the output of `gdymc_get_modules`.

### 0.8.4
- Changed the way cropping is handled to the more robust `wp_crop_image` (the media manager image cropper should now work on every system the native WordPress cropper does)

### 0.8.3
- Bugfix for module list when no modules exist
- Bugfix for error messages when no modules exist
- Added information about the publish date in the link window

### 0.8.2
- Styling adjustments
- Changed to the WP 4.6 default fonts
- Fixed a bug where the error window appears twice on deleting a module type
- Added option to change the module type of deleted modules
- Added the functions `gdymc_has_modules` and `gdymc_get_modules`
- Added function to change the module type in the module options
- Added an extra container around editable texts to prevent some weird behaviors occurring in some tags like h1 etc.
- Changed some module options from disabled to readonly to allow text selection
- Added the `gdymc_module_attributes` filter for customizing module attributes
- Removed the `gdymc_moduleinner_class` filter
- Bugfix for preview modes on login

### 0.8.1
- Styling fixes
- Changed the way `_gdymc_image_width` and `_gdymc_image_height` are saved to a much more robust way
- Changed the `gdymc_set_all_attachment_image_sizes` function to `gdymc_transfer_attachment_image_sizes`
- Fixed a bug in the way how the image is received for the crop dialog in the frontend media manager
- Changed `gdymc_object_id` back to the more robust `get_queried_object_id`
- Plugin cookies are now based on the WordPress variables `COOKIEPATH` and `COOKIE_DOMAIN`
- Fixed a bug where textareas in module options were submitted on enter
- Fixed a bug where HTML attributes in tables contents destroy the table field
- URLs inserted with the frontend text editor are now correctly encoded
- Fixed a bug that disabled the show all format options button
- Fixed a bug where the frontend media manager skips the first page
- Added the information how many additional images are available in the frontend media manager

### 0.8.0
- Some style adjustments for button states
- Added the hooks `gdymc_modulelist_before` and `gdymc_modulelist_after`
- Added the jQuery events `gdymc_disable_softpreview` and `gdymc_enable_softpreview`
- Added the `gdymc_get_module_path()` and `gdymc_get_module_url()` to return path/url in module files
- Added the hooks `gdymc_image_before` and `gdymc_image_after`
- Some hook optimizations
- The `picturefill.js` is now minified
- Added the hooks `gdymc_plugin_activation`, `gdymc_plugin_deactivation`, `gdymc_plugin_uninstall`, and `gdymc_plugin_upgrade`
- Attachment meta `_gdymc_image_width` and `_gdymc_image_height` are now also available via post meta for queries by image dimensions
- Fixed a bug where module options overlay doesn't appear after fast preview
- Added a permalink information in the module default options to link modules directly from external pages
- Option fields support now saving on enter key
- Changed the way the frontend media manager loads images
- Full module option support for input type `hidden` and `password`
- Basic module option support for HTML5 input types: `number`, `date`, `color`, `range`, `month`, `week`, `time`, `email`, `search`, `tel`, `url`
- Module functions are now included at `after_setup_theme`
- On AJAX all module functions are included now
- New functions `gdymc_get_all_module_types` and `gdymc_get_placed_module_types`
- Added `gdymc_area` shortcode to create a module area within the regular post content
- Added various error filters

## Earlier Versions

For complete changelog of versions 0.7.9 and earlier, see the [readme.txt](../readme.txt) file in the repository.

### Notable Earlier Releases

- **0.7.8** - Module hooks changed to receive only module object as parameter
- **0.7.0** - CSS style reset, translation support, example files added
- **0.6.9** - Module position improvements, placeholder images
- **0.6.8** - Prefixed postmeta keys, gallery content type added
- **0.6.6** - Support for contents outside module area, timed visibility
- **0.6.5** - Renamed from gdy_modularContent to gdymc
- **0.6.4** - Styling improvements, admin bar updates
- **0.6.3** - New module options system introduced
- **0.6.2** - Module search, generator added
- **0.6.1** - Improved content creation logic, unsaved content warnings

## Upgrade Notices

### Upgrading to 0.8.6
**Module option hooks have changed.** If you have modules with options you need to adjust them in order to work properly. All module functions are always loaded on `init`. Make sure you wrap your script and style loading functions in a condition with `gdymc_module_is_placed( gdymc_module_name( __FILE__ ) )`.

### Upgrading to 0.8.5
**The default module folder has changed** to `/wp-content/modules`. Update your `gdymc_modules_folder` filter accordingly.

### Upgrading to 0.7.8
**Module hooks now receive only the module object as parameter.** Make sure your modules are ready for that. Especially module options are going to make problems with the old structure.

### Upgrading to 0.6.4
**No backward compatibility for older module options.** The old module options will still be supported but there is a new system that replaces the old one. For the best user experience it is strongly recommended to migrate the old option system to the new one.

## Breaking Changes Summary

### Major Breaking Changes by Version

**0.8.6:**
- Module option hooking system changed
- All module functions always loaded on init

**0.8.5:**
- Default module folder location changed
- Filter name changed from `gdymc_theme_modules_folder` to `gdymc_modules_folder`
- Child theme support removed
- Function names changed for getting module types

**0.7.8:**
- Module hooks parameters changed (only receive $module object)

**0.6.5:**
- Complete rename from gdy_modularContent to gdymc (all functions, classes, variables)

**0.6.4:**
- Module options system redesigned

## Development Notes

### Database Schema Changes

**0.9.7:** Object contents now stored as JSON
**0.9.0:** Module and content IDs saved as JSON
**0.7.8:** Tables saved as JSON
**0.6.8:** Postmeta keys prefixed with underscore

### API Additions by Version

**0.9.87:** `gdymc_imagesize` filter
**0.9.1:** `gdymc_current_url()` function
**0.8.6:** Enhanced `gdymc_module_path()`, `gdymc_module_url()` with additional parameters
**0.8.5:** `gdymc_module_path()`, `gdymc_module_url()` functions added
**0.8.2:** `gdymc_has_modules()`, `gdymc_get_modules()` functions
**0.8.2:** `gdymc_module_attributes` filter
**0.8.0:** Multiple new hooks and filters for modules and images
**0.8.0:** HTML5 input type support for module options
**0.8.0:** `gdymc_area` shortcode

## See Also

- [Installation Guide](Installation.md) - Installing and upgrading
- [GitHub Releases](https://github.com/fouadvollmergut/gdy-modular-content/releases) - Latest releases
- [Contributing](Contributing.md) - How to contribute
