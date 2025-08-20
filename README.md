![Static Badge](https://img.shields.io/badge/Stable-0.9.981-green)
![Static Badge](https://img.shields.io/badge/Wordpress-6.8.2-blue)

# GDY Modular Content

### Create and edit modular content from the frontend of your wordpress site.

This plugin allows you to create modular content and to edit it with a intuitive user interface from the frontent of your site. Main philosophy of the plugin is it to make it easy for all of you. That means easy handling for editors, fast and flexible implementation for programmers and good control over your layouts for designers. The plugin should run in most enviroments because it uses pretty much the native WordPress architecture to save contents (postmeta, attachments). So far no lasting problems with any other plugins are reported.

## Usage

#### Install Plugin

Download and install the plugin or install it directly in the Plugins tab in your Wordpress Dashboard via *Add new plugin*.

#### Add modules folder

Create a folder called *modules* in the root directory of your theme and add your modules there. You can get some example modules from this repository: [GDY Modular Content – Example Modules](https://github.com/fouadvollmergut/gdymc-example-modules).

Place following snippet within your `functions.php`:

```php
// functions.php

add_filter( 'gdymc_modules_folder', 'gdymc_folder' );

function gdymc_folder($module_folders) {
  array_push($module_folders, get_template_directory() . '/modules');
  return $module_folders;
}
```

#### Create module area

Now create a *module area*, in the templates you want to contain modules.

```php
// single.php

<?php if( function_exists( 'areaCreate' ) ) areaCreate(); ?>
```

To enable modules for pages and posts by default add the snippet to your `index.php` like so:

```php
// index.php

<?php get_header(); ?>
  <?php if( have_posts() ): while( have_posts() ): the_post(); ?>
    <?php if( function_exists( 'areaCreate' ) ) areaCreate(); ?>
  <?php endwhile; endif; ?>
<?php get_footer(); ?>
```

Alternativly you can add a shortcode `[gdymc_area]` to the pages that should contain a *module area*.

#### Add modules

Now navigate to a page containing a module area and add some module from the top right of the *gdymc admin bar*.

---

## Development Workflow

The repository uses a Webpack build configuration.

*Available commands:*


Start local development server to compile scss files. Built files will be located in `_styles` folder.

```sh
  npm run start
```


Output a clean plugin folder for testing purposes. Built files will be located in `_dist` folder.

```sh
  npm run build
```


## Contribution Workflow

Fork this repository and open a pull request to contribute changes.


## Release Workflow

Use the `release.sh` script to trigger a new plugin release. Make sure to include all neccessary changes for the release into the main branch and update package.json description before running the script:

```sh
  sh ./release.sh 0.0.0 // Specify the current version.
```

Make sure to push the version changes within the mandatory plugin files to main – the changes should appear automatically after running the release script.

To publish a release to Wordpress Plugin Store, upload the built file from the Github release to the SVN Repository.

*Undo a release*

Remove the remote and local tag and also delete the release on Github

```sh
  git tag --delete 0.0.0 // Specify the tag to delete
  git push --delete origin 0.0.0 // Specify the tag to delete
```

To publish it to Wordpress import the new version in the tags folder and run following command within in the trunk to tell Wordpress which is the stable version.

```sh
  svn ci -m "Updating code to release X.X.X"
```