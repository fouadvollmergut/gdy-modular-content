![Static Badge](https://img.shields.io/badge/Stable-0.9.90-green)
![Static Badge](https://img.shields.io/badge/Wordpress-6.6.2-blue)

# GDY Modular Content

### Create and edit modular content from the frontend of your wordpress site.

This plugin allows you to create modular content and to edit it with a intuitive user interface from the frontent of your site. Main philosophy of the plugin is it to make it easy for all of you. That means easy handling for editors, fast and flexible implementation for programmers and good control over your layouts for designers. The plugin should run in most enviroments because it uses pretty much the native WordPress architecture to save contents (postmeta, attachments). So far no lasting problems with any other plugins are reported.

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
