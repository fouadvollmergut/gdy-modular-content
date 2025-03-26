=== GDY Modular Content ===
Contributors: fouadvollmer, grandy
Tags: gdy modular content, gdymc, frontend editing, modular content, content editable
License: GPLv2
Requires at least: 3.6
Tested up to: 6.7.2
Requires PHP: 5.6
Stable tag: 0.9.94

Create and edit modular content from the frontend of your site.

== Description ==

This plugin allows you to create modular content and to edit it with a intuitive user interface from the frontent of your site.

Main philosophy of the plugin is it to make it easy for all of you. That means easy handling for editors, fast and flexible implementation for programmers and good control over your layouts for designers. 

The plugin should run in most enviroments because it uses pretty much the native WordPress architecture to save contents (postmeta, attachments). So far no lasting problems with any other plugins are reported.

== Changelog ==

= > 0.9.90 =
* For a detailed changelog please refer to our github repository: https://github.com/fouadvollmergut/gdy-modular-content

= 0.9.90 =
* Add PHP 8.2 Support
* Introduce new content type button group

= 0.9.88 =
* The custom renderer callback for gallery items now receive the counter variable $i as second parameter

= 0.9.87 =
* Support for other icons in admin- and modulebar
* Added gdymc_imagesize filter for filtering the default image size used in contentCreate images

= 0.9.86 =
* Updated plugin information
* Removed documentation links

= 0.9.85 =
* Same editable content wrapper classes for logged and unlogged users

= 0.9.84 =
* Removed batch editing until its adjusted for multiple object types

= 0.9.83 =
* Fixes for the `optionInput` function including attribute escaping wich allows the usage of json encoded values

= 0.9.82 =
* Bugfix for `optionGet` when receiving values of an external object

= 0.9.81 =
* Added option to add additional classes to links

= 0.9.8 =
* Fixed a bug where object contents where saved incorrectly/empty on the redirect from the paramter url to the rewritten one

= 0.9.7 =
* Database update to the new JSON format for object contents

= 0.9.6 =
* Removed a remaining `var_dump`

= 0.9.5 =
* The logout link in the gdymc admin bar now redirects to the last viewed page
* Small updates on the options API functions
* Fixed a bug with object contents (the ones outside of modules)
* Changed the plugin header information because the old format may have caused problems with the repository stats

= 0.9.4 =
* Fixed a extremely rare bug that renders an unwanted button in the plugins error windows.

= 0.9.3 =
* The plugins only sets cookies when you are logged in and as soon as you logout the plugin removes its cookies. This removes the cookie footprint for regular users completely wich is better for the stricter privacy guidlines now and in the future.

= 0.9.2 =
* Removed the `gdymc_automatic_module_area` filter wich causes problems with multiple areas on one page. This results in some cases in the main area not being rendered.

= 0.9.1 =
* Added `gdymc_current_url` function wich return the current url
* Changed the hard preview link. This should resolve some bugs especially when switching to hard preview on archives.

= 0.9.0 =
* Minor style fixes
* Fixed a bug with `contentGet`/`contentCheck`
* Term meta introduced in WordPress 4.4 is now supported. That means you can use GDYMC terms now.
* Listed module and content IDs are now saved as JSON in the database allowing strings for content IDs
* The plugin uses now its own ajax handler (wrapping jQuery.ajax) that takes care of error handling
* Better support for hidden module options
* Changed how the preview works (the gdymc_preview, gdymc_hardpreview and gdymc_softpreview functions are still working the same)
* Switched to WordPress utils to manage cookies
* GDYMC supports now admin languages introduced with WordPress 4.7. So you can have a different language for GDYMC (the admin) than your site. That includes the GDYMC areas in the frontend.

= 0.8.9 =
* Changed the delete module type batch
* Added parameter for `get_edit_term_link()`
* Added fourth parameter to `add_post_meta` on `_gdymc_module_list` to prevent duplicates (In some rare cases it happened that there were added a lot of duplicate postmeta entries that caused massive performance issues)

= 0.8.8 =
* Fixed PHP notices
* Module deletion is now processed via the module class
* The edit link in the adminbar works now also on terms
* Added a GDYMC ajax handler that takes care of any errors occuring
* Style fix for focused modules
* Fixed bug that causes contents not to show up if used outside of modules

= 0.8.7 =
* Styling fixes
* Joined style documents (only a single file is now loaded)
* Removed style support for old module options
* Fixed a bug occuring when deleting modules on duplicated posts (This happens for example in WPML)
* Output buffering is used to prevent empty option areas (so option tabs with no content are not visible anymore)
* Removed the `gdymc_time_ago` function because it wasn't used
* Removed the `metaExists` function and replaced it with the WordPress `metadata_exists` function
* Added image editing from frontend editor and image links
* The GDYMC_MODULE class now needs the objectID as parameter (Thats because the id is not enough to identify modules, the id is stored in the meta wich can be duplicated by plugins)
* The redirect after login and logout was removed
* Bugfix where frontend styles can affect the cropper results

= 0.8.6 =
* Bugfix for content swapping
* Smaller bugfixes for PHP warnings
* Added some links to the documentation
* Fixed the action callback for the default gallery image caption
* Optimized the hooks for error messages (added several actions, changed the name for several filters)
* All module functions are always loaded on `init`. So the module functions should behave exactly like the theme function. Make sure your wrap you script and style loading function in a condition with `gdymc_module_is_placed( gdymc_module_name( __FILE__ ) )`
* Changed the hooking system of module option tabs massively
* Removed the width and height of the editable image container
* Added errors for multiple placed module areas and areas on wrong content types
* Removed the readme FAQs (If there are any questions the support are is now the right place for them)
* Responsive images are removed because WordPress has its own support for this since version 4.4. Default WordPress attachments are used now.
* Fixed a bug where the buttons of missing modules are shown for visitors
* The functions `gdymc_module_path`, `gdymc_module_relative` and `gdymc_module_url` allow now two additional parameters for better usage

= 0.8.5 =
* Changed the style reset and adjusted the styles
* Better syntax in modulebars and adminbar
* Removed the shortcuts for alignment because they causing some conficts with hard reload on some systems
* Removed the `gdymc_formatbuttons` action hook
* Changed the hooks for modulebar buttons into `gdymc_modulebarbuttons_left` and `gdymc_modulebarbuttons_right` ($module is passed as parameter ... you can get the type from this)
* Moved the logout button to the view dropdown
* Added a media shortcut in the adminbar
* Fixed a rare bug in the module options with the module type setting when no modules exists (for existing modules when switching the theme for example)
* Fixed a bug occuring with encoded characters when placing a hyperlinks
* Fixed a bug "Illegal string offset" occuring in PHP 7+
* The `gdymc_text` tag surrounding editable tags is now a `span` tag for visitors to prevent syntactical errors (e.g. if the content is placed in a h1)
* Removed the extra container around editables texts because it has no effect on the h1 bug
* Renamed the `example-theme-files` folder to `example-files`
* Added the functions `gdymc_module_path`, `gdymc_module_relative` and `gdymc_module_url`
* Changed the way modules are placed in the system. There is no support for child themes anymore.
* Changed the filter `gdymc_theme_modules_folder` into `gdymc_modules_folder` (To change the module folder it is now necessary to specify a complete file system path)
* The default module folder is now located in `./wp-content/modules` to prevent any damage when using third party themes
* Removed the `gdymc_get_all_module_types` function. Use `gdymc_get_modules` instead.
* Changed the `gdymc_get_placed_module_types` function name to `gdymc_get_placed_modules`. The return is now an object based on the output of `gdymc_get_modules`.

= 0.8.4 =
* Changed the way cropping is handled to the more robust `wp_crop_image` (the media manager image cropper should now work on every system the native WordPress cropper does)

= 0.8.3 =
* Bugfix for module list when no modules exist
* Bugfix for error messages when no modules exist
* Added information about the publish date in the link window

= 0.8.2 =
* Styling adjustments
* Changed to the WP 4.6 default fonts
* Fixed a bug were the error window appears twice on deleting a module type
* Added option to change the module type of deleted modules
* Added the functions `gdymc_has_modules` and `gdymc_get_modules`
* Added function to change the module type in the module options
* Added a extra container around editable texts to prevent some weird behaviours occuring in some tags like h1 etc.
* Changed some module options from disabled to readonly to allow text selection
* Added the `gdymc_module_attributes` filter for customizing module attributes
* Removed the `gdymc_moduleinner_class` filter (Use gdymc_module_class and the parent container instead. This container is only for making invisible modules transparent in editing mode and may be changed or removed in the future)
* Bugfix for preview modes on login

= 0.8.1 =
* Styling fixes
* Changed the way `_gdymc_image_width` and `_gdymc_image_height` are saved to a much more robust way. It's now bound to the hook `gdymc_transfer_attachment_image_size` that is placed right before the frontend media manager is loaded
* Changed the `gdymc_set_all_attachment_image_sizes` function to `gdymc_transfer_attachment_image_sizes` according to the name of the new hook
* Fixed a bug in the way how the image is received for the crop dialog in the frontend media manager
* Changed `gdymc_object_id` back to the more robust `get_queried_object_id`. For this the module functions are included on different hooks depending on if it is a AJAX call or not
* Plugin cookies are now based on the WordPress variables `COOKIEPATH` and `COOKIE_DOMAIN`
* Fixed a bug where textareas in module options were submitted on enter
* Fixed a bug where HTML attributes in tables contents destroy the table field
* URLs inserted with the frontend text editor are now correctly encoded
* Fixed a bug that disabled the show all format options button
* Fixed a bug were the frontend media manager skips the first page
* Added the information how many additional images are available in the frontend media manager

= 0.8.0 =
* Some style adjustments for button states
* Added the hooks `gdymc_modulelist_before` and `gdymc_modulelist_after`
* Added the jQuery events `gdymc_disable_softpreview` and `gdymc_enable_softpreview`
* Added the `gdymc_get_module_path()` and `gdymc_get_module_url()` to return path/url in module files (child-theme ready)
* Added the hooks `gdymc_image_before` and `gdymc_image_after`
* Some hook optimizations
* The `picturefill.js` is now minified
* Added the hooks `gdymc_plugin_activation`, `gdymc_plugin_deactivation`, `gdymc_plugin_uninstall`, and `gdymc_plugin_upgrade`
* Attachment meta `_gdymc_image_width` and `_gdymc_image_height` are now also available via post meta for queries by image dimensions
* Fixed a bug where module options overlay doesn't appear after fast preview
* Added a permalink information in the module default options to link modules directly from external pages
* Option fields support now saving on enter key
* Changed the way the frontend media manager loads images
* Full module option support for input type `hidden` and `password`
* Basic module option support for the following html5 input types `number`, `date`, `color`, `range`, `month`, `week`, `time`, `email`, `search`, `tel`, `url`
* Module functions are now included at `after_setup_theme`. For this the gdymc_object_id function was changed
* On AJAX all module functions are included now (allowing to process AJAX calls from within module functions)
* New functions `gdymc_get_all_module_types` and `gdymc_get_placed_module_types`
* Added `gdymc_area` shortcode to create a module area within the regular post content
* The gdymc_text container is a div again because there were some weird behaviours
* Added the filters `gdymc_error_adminbar_nomodulefolder`, `gdymc_error_adminbar_nomodules`, `gdymc_error_adminbar_noarea` and `gdymc_error_area_nomodules`

= 0.7.9 =
* Styling fixes
* Fixed a bug with gallery placeholders where images with auto sizes caused an error
* The use images button in the gallery overlay shows now the number of selected images
* Added version parameter (the GDYMC version number) to plugin scripts and styles to avoid cache problems after plugin updates
* Updated the cookie library to cookies js v 2.1.1
* Fixed a bug with cookie paths where duplicate cookies are generated from js and php
* Placing the preview cookies (soft and hard) on unload to maintain the current state even with multiple tabs or windows are open
* Removed the soft preview when leaving the hard preview so users always come back to edit mode when they exit hard preview even if soft preview was enabled before they entered
* Better behaviour and animations für adminbar dropdowns
* Hover and focus styles for module option inputs
* Fixed a bug with the adminbar format buttons when selecting modules on smaller screens
* Changed the conditions for the plugin body classes
* The gdymc_text container is now a span (for valid placement in h and a tags) and not longer positioned
* Changed the way how module thumbs are fetched to a more compatible solution
* Some syntactic changes for better keyboard support
* Revised frontend media uploader (Uploads are now handled directly via `media_handle_upload()` allowing all filetypes that are supported by wordpress itself). Also the uploader styles are much more polished
* Fixed a bug where the frontend media manager isn't rendered completely

= 0.7.8 =
* Styling fixes
* Categories have now an own tab in the link window
* The media manager now dissapears when new uploads are dropped
* Added the js function `gdymc.editor.addclass( classname, attributes )` for custom editor buttons
* In hard preview user capabilities are cropped and the WordPress `is-logged` class is removed
* The hard preview button is now a real link (the unsaved content warning now appears and you can open it as new tab)
* On fresh user login the hard and soft preview are deactivated
* The preview button now indicates on top level if fast preview is active of not
* Fixed a bug with the naming of the superscript and subscript buttons
* The customizer button is back in the 'to backend' submenu
* Deleted the profile button in the 'to backend' submenu
* Fixed a bug with the enter preview error (when you click on format buttons but the fast preview is active)
* Removed a undefined index warning caused by the hard preview
* Optimized the priority of the adminbar and modulebar button hooks
* Enabled and optimized batch editing for modules
* Updated delete module function to support deletion of multiple modules at once
* Fixed a bug where some fields are saved unintentionally as module options
* It is now possible to copy and move modules between posts via the batch window
* Added more hooks
* Module hooks get now only the module object (`$module`) as parameter (This may cause errors with old modules … Check the module functions!)
* Changed necessary capability to `edit pages`
* Added a `gdymc_timed` class to check if the module has a active timer
* Added filters for the classes of the module containers: `gdymc_module_class`, `gdymc_moduleinner_class`
* Added the `gdymc_area_class` filter which is filtering the module area classes
* Removed `$gdymc_active_area`. Check `$gdymc_module` instead
* Added `GDYMC_BASE_PATH` pointing to the plugin directory
* Added `gdymc_support` filter (replacing $gdymc_support)
* Added support for child themes
* Fixed a bug wich allows to open image overlays from softpreview
* Fixed a bug where soft preview stays active after adding a module
* Fixed a bug that occurs when removing the last image from selection
* Editable table bugfixes: Buttons, Preview
* Added the shortcut `CMD+Shift+E` to enable hard preview
* Tables are now saved as JSON (old contents are converted)

= 0.7.7 =
* Styling hotfixes
* Plural support for german language file
* Better keyboard support
* Added a shortcut for the format unlink button
* Added a hard preview mode (where nearly the whole system behaves like you are a visitor)
* Fixed a tooltip bug with tooltips on the right side rendered wrong
* Optimized source area in the insert link window
* Added message for empty module search
* Support popup doesn't show up autmatically now
* Added progress cursor for several ajax actions for better visual feedback

= 0.7.6 =
* Fixed a saving bug where empty images are saved as null and not a empty string

= 0.7.5 =
* Gallery hotfix

= 0.7.4 =
* Added a popup asking for user support that is showed once per user 6 months after the plugin installation
* Changed the `gdymc_time_ago` function for better translation
* Changed and unified how image and gallery editables are saved in the database
* Removed the noarea hooks and changed the place of the error message
* Added loading animation for the crop window
* Added error message for images that are too small
* Fixed the module option label style for text options
* Removed the editable option due unfinished integration
* Fixed some remaining hash reload errors

= 0.7.3 =
* Added i18n support for GlotPress on wordpress.org 
* Added a `module option example` plugin to the `example theme files` folder

= 0.7.2 =
* Better adminbar behavior for different screen sizes
* Better format button order in the adminbar
* Added the JS functions `gdymc.selection.parent()` and `gdymc.selection.inside()`
* Format buttons no always try to restore saved selections
* Some adjuments for the link window to support the format buttons overlay
* New module option tab "defaults" that shows IDs and module type
* Scroll gradient for module list
* It is now possible to open links in edit mode with metakey+click
* Better styles for not existing module actions
* Fixed style for module list with no modules
* Added support for updates from 0.6.3 so that all existing versions should be updateable without destroying the contents

= 0.7.1 =
* Styling Hotfixes

= 0.7.0 =
* CSS style reset for plugin elements with `.gdymc_inside * { all: initial; }`
* CSS improvements so that the plugin is displayed correctly in more themes
* Added example theme files
* Added POT file to the language folder
* Removed select module buttons till the batch actions are finished

= 0.6.9 =
* Style improvements
* Removed module position tab
* Added options for not existing modules: delete this module, delete this module type
* Added the filter `gdymc_loginout_redirect` to disable login or logout redirects
* Fixed a bug with editable image size rendering in admin mode
* Fixed a bug with reload actions when a hash is applied
* Changed the not existing module options so that they are only showed for admins
* Added placeholders for empty editable images so that they are showed in the correct size
* Fixed a bug were placeholder images are shown in preview-mode
* Added actions for galleries. Changed the caption as hook
* Removed translation div. Translations are now added via `wp_localize_script()`
* Gallery container is now also available in unlogged mode
* Galleries no support a custom rendering function as third parameter
* The `li` tag in galleries has now the image id in the `data-image` attribute
* The image info labels are no only triggered in edit mode
* Removed buggy placeholder images for image auto sizes (autoxauto, 100xauto, autox100)
* Adjusted scripts for module labels so that first and last is showed completely and no kinetic problems appear
* The `.gdymc_text` container is now also added in visitor mode

= 0.6.8 =
* Prefixed postmeta keys with `_` that they no longer show up on the post edit page
* Changed postmeta key `gdymc_Content_` to `gdymc_singlecontent_`
* Changed postmeta key `_gdymc_object_content` to `_gdymc_object_contents`
* Changed postmeta `_gdymc_list` to `_gdymc_modulelist`
* Styling fix for swap contents button
* Added content type gallery
* Removed double click to insert in media manager
* Added thumb for active images besides the insert image button
* Changes in image handling for gallery
* Added captions to gallery
* Small styling bugfixes
* Changed how url are set in adminbar.php and builder.php now some module thumb problems should disappear
* Changed names of javascripts
* Additional error messages when not in edit mode
* Styling updates for media uploader
* Changed the way thumbs of selected images were created in the media manager
* Better cropping for media manager
* Added jqueryui sorting for slider elements
* The Plugin adds now admin bar classes on body
* Changed a bug in `gdymc_logged()` with user roles
* Added link to directly jump to customizer
* Improved gallery classes
* Added picture tags for responsive images
* Added public javascript picturefill.js for browser-support of picture tags
* Fixed a bug with `gdymc_responsive_image()` when invalid image ID is submitted
* Fixed saving bug
* Added sorting for responsive image sizes so that it behaves correctly
* Added filters to disable image sizes and change rendering type (responsive or not)
* Fixed a sizing bug with regular images
* Cleanup
* Added filter to change module names
* Added filter to change themes modules folder name
* Style now supports (and displays) long module names
* Visual feedback for the AJAX when adding modules

= 0.6.7 =
* Fixed a undefined variable
* Styling for warning if module folder doesn't exist
* Warning if there are no modules (aka folders in module folder)
* Fixed a initialisation problem with jcrop when images take too long to load
* Added build number for better version control
* Added soft user role support for gdymc (edit_pages, upload_files)
* Added visual feedback for uploads
* Small bugfixes for the uploader
* Changed upload response (cause wordpress can't generate thumbnails of very big files and so the upload can be incomplete)
* Changed frontend media manager loop to a more stable version (same problem as above)
* Added support for new option types: textarea, editable
* Fixed bug with undefined variables in link window
* Better styling for module options text editor
* New hooks for modulebars
* Better identification for module option fields
* Added versioning system for trigger actions on older systems
* Changed `wp_postmeta` to `$wpdb->postmeta` in WPDB query

= 0.6.6 =
* Better styling for editable table UI
* Image overlay is now infinite scrolling
* Smaller image thumbs
* Better ajax loading feedback
* Integrated a search function for images
* Fixed bug with wrong editable style in preview mode
* Fixed bug with SVG files causing problems in image window
* The plugin now supports contents outside the module area
* `contentCheck()` supports now an array of IDs as parameter
* Contents are now swappable via button
* Fixed some IE bugs
* Visual feedback for image search
* Support for timed visibility switch
* Fixed CSS heights for option inputs
* Moved javascripts into page footer for faster loading
* Fixed batch window formatting
* Added check if object exists on moving modules
* Changed the way the plugin gets the post/page id so it works with uncommon url rewrites
* Changed the hook for gathering module functions because of the changed get_object_id
* New terminology: `$gdymc_PageID` is now $gdymc_object_id
* New terminology: `gdymcObjectID()` is now `gdymc_object_id()`
* New terminology: `gdymc_timeAgo()` is now `gdymc_time_ago()`
* New terminology: `gdymc_moduleArray()` is now `<?php gdymc_module_array()`
* Added readme.txt and removed changelog.txt
* Added screenshots

= 0.6.5 =
* New styles
* Changed gdy_modularContent into gdymc (classes, functions, variables)
* Changed some classnames
* Removed module generator
* Added type attribute to module container
* Added module bars
* New visibility tab in module options overlay
* Changed gdymc_barbuttonsleft to gdymc_adminbarbuttons_left
* Changed gdymc_barbuttonsright to gdymc_adminbarbuttons_right
* Removed and renamed several functions
* Delete module is now an AJAX action
* Add module is now an AJAX action
* Removed all nopriv ajax action hooks
* Included rangy for working with text selections (especially for setting links)
* Changed format buttons to onmousedown to preserve focus correctly
* Imagelist now reads image size from attachment meta and not via getimagesize()
* Fixed bug with saving and deleting modules
* Module information are now accessible via $module in the modules index.php
* Changed the way module moving works
* Some adjustments with cropping
* Changed ajax variable
* Changed javascript script.js to gdymc_core.js
* Added error windows to replace browser alerts
* Implemented a new way for handling translated strings in javascript
* After saving and deleting modules the scroll position is restored
* Lazy way of using option defaults
* Fixed focus problem with link shortcut and bug with setting _blank links
* Styled error if unsaved contents exists when adding modules
* Modulbar buttons have now own actions
* Added batch actions for modules
* Better error windows for unsaved contents
* Removed content type option (now options are available via optionCreate() etc.)
* Added support for new content type table

= 0.6.4 =
* Styling fixes & adjustments
* Added object id parameter for gdymc option functions
* Changed class prefix to gdymc_ instead of gdy_modularContent_
* Fixed tab behaviour
* New styles for buttons
* Added post edit link in wpmenu

= 0.6.3 =
* Styling fixes
* The dashboard button has now a submenu
* Logout now directly redirects back to frontpage
* Added several hooks
* Changed `metaExist()` to `metaExists()`
* Changed hooks
* Heavily changed the way how module options work
* Added `optionInput()`, `optionSave()`, `optionShow()`, `optionGet()`, `optionExists()` and `optionError()`
* Added postmeta for visibility (on upgrade all modules are invisible)
* Better moving of modules
* Fixed bug with loading module functions.php
* Fixed bug with repost after saving what causes to add modules multiple times
* Changed deletion routine of modules
* Fixed bug with `metaExists()`
* Changed saving process

= 0.6.2 =
* Styling fixes
* Stability fixes
* Visual feedback for texts that are too long
* Actual feedback for long texts
* Implementation for module search
* Added the Generator
* Fixed bug with info modal positions
* Changed modulebars from static to absolute to prevent an offset change when switching to edit and back
* Added support for module functions.php
* Fixed z-index bug with editable images

= 0.6.1 =
* Styling fixes
* Modals on text and image elements
* Cleaner file structure
* Option to set maximum text length
* Better content creation logic allow a unordered use of the `contentCreate()` function
* The `contentCreate()` function allows numeric IDs 
* Textual post and page creation dates in the link window
* Updated admin link in the bar
* Added a "this content is unsaved" warning
* New `gdymc_logged()` function to check if users are logged
* Security fix that removed the option to add and delete modules whithout being logged
* Added a `contentCheck(); ?>` function to check if there is content for unlogged users (to hide certain elements e.g. empty containers)

== Upgrade Notice ==

= 0.8.6 =
* The module options hooks have changed. If you have modules with options you need to adjust them in order to work properly. All module functions are always loaded on `init`. Make sure you wrap your script and style loading functions in a condition with `gdymc_module_is_placed( gdymc_module_name( __FILE__ ) )`.

= 0.8.5 =
* The default module folder has changed

= 0.7.8 =
* Module hooks get now only the module object as parameter. Make sure your modules are ready for that. Especially module options are going to make problems with that new structure.

= 0.6.4 =
* There is no backward compatibility for older module options. The old module options will be still supported but there is a new system that replaces the old one. For the best user experience it is strongly recommended to migrate the old option system to the new one.