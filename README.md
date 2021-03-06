# Scoop child theme project for parent theme Pojo Scoop

------

# WordPress CDKstart

A starting point for managing a WordPress installation using [Composer](https://getcomposer.org/) and Git.

* Installs WordPress into its own subdirectory.
* Uses [WPackagist](http://wpackagist.org/) to manage plugins and themes.

## Installation

To install:

1. Clone repo into your webroot.
2. Update required information in `composer.json`, including package name, description, authors and license.
3. Run following command to install packages: `composer install --no-dev`
4. Copy `wp-config-sample.php` to `wp/wp-config.php` and edit database connection details.
5. Install WordPress in your browser.

By default, this will also install some plugins that are useful in website development. To skip the installation of these plugins, use the `--no-dev` option:

```sh
$ composer install --no-dev
```

## Adding and removing plugins and themes

Simply make edits to the package list in `composer.json` and run the following command:

```sh
$ composer update --no-dev
```

See [WPackagist](http://wpackagist.org/) for more information.
