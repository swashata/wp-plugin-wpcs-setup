# Setting up WPCS

The primary goal of this documentation article is to:

1. help you set up the linting tool [WPCS](https://github.com/WordPress/WordPress-Coding-Standards) in your plugin's codebase;
2. describe how to use the linter and understand what it's telling you;
3. integrate the listing tools in teh CLI and in your favorite code editor.


## A Brief Introduction to Code Linting

When working on a project with more than a few developers, it's important that everyone is writing code to the same style and quality standards. In addition, you should always strive to write performant and safe code. WPCS will help you in these goals.

First, some definitions.

* "Sniffing" or "Linting" is an automated process to detect
possible issues in a codebase. There are several hundred code quality checks that make up a "ruleset" and remembering them all can be overwhelming. Luckily, we don't have to: we have a set of tools that work
in the command line and in your favorite code editor to help you detect any
possible violations as you write your code.

* PHPCS (short for [**PHP_CodeSniffer**](https://github.com/squizlabs/PHP_CodeSniffer)) is the primary linting tool. Once installed, you can run PHPCS on your project to detect and report any errors and suggested fixes.

* WPCS (short for [**WordPress Coding Standards**](https://github.com/WordPress/WordPress-Coding-Standards)) is a set of PHPCS rules specific to  WordPress coding conventions.

> __PHPCS__ is the program that scans your PHP files and reports any violations. __WPCS__ is a configuration _for_ PHPCS that extends the sniffing rules with additions related to WordPress coding standards.

For more information on coding standards, please visit the [WordPress Coding Standards handbook](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/). 


## Setting up PHPCS through CLI

Before we dive into editor integration, it's useful to understand how to configure and use PHPCS on the command line interface (CLI).

There are many ways to install PHPCS along with WPCS. You can find them
[here on the official repository](https://github.com/WordPress/WordPress-Coding-Standards#installation).

In this guide, we use [composer](https://getcomposer.org/) to install both PHPCS and WPCS as development dependencies of your project. Composer is a package manager for your codebase, and has a few key advantages over manual installation, but the main one is that it means you can keep the installation specific to your plugin. In turn this means that it's straightforward to use different configurations for different projects. 

**All the commands below are run from the Plugin's directory**. 

> TIP: If you're using Windows, using [Git Bash For Windows](https://git-scm.com/downloads) is recommended.

All files and setup can be found [in this repository](https://github.com/swashata/wp-plugin-wpcs-setup).

### Installing Composer

If you already have composer set up for your project you can skip this section.

Follow the [official installation guide](https://getcomposer.org/doc/00-intro.md) to get composer installed and running for your project.

Next, initialize composer:

```sh
$ composer init
```

This will give you an interactive prompt; fill in the answers as accurately as you can, but don't worry you can edit the configuration file afterwards if you make a mistake. Here are some sample answers:

THIS NEEDS TO BE IMPROVED, THIS IS NOT CLEAR AT ALL

```

  Welcome to the Composer config generator



This command will guide you through creating your composer.json config.

Package name (<vendor>/<name>) [user-name/wpcs-doc]: 
Description []: A sample WordPress plugin with WPCS setup.
Author [John Doe <john.doe@example.com>, n to skip]:
Minimum Stability []: stable
Package Type (e.g. library, project, metapackage, composer-plugin) []: project
License []: GPL-3.0

Define your dependencies.

Would you like to define your dependencies (require) interactively [yes]? no
Would you like to define your dev dependencies (require-dev) interactively [yes]? no

{
    "name": "user-name/wpcs-doc",
    "description": "A sample WordPress plugin with WPCS setup.",
    "type": "project",
    "license": "GPL-3.0",
    "authors": [
        {
            "name": "John Doe",
            "email": "john.doe@example.com"
        }
    ],
    "minimum-stability": "stable",
    "require": {}
}

Do you confirm generation [yes]? yes
```

/THIS NEEDS TO BE IMPROVED


Once done, a file called `composer.json` will be created in your plugin directory. Now we're ready to install PHPCS and WPCS.

### Installing PHPCS and WPCS as composer dev dependency

From the plugin directory, run the following command.

```sh
$ composer require --dev squizlabs/php_codesniffer wp-coding-standards/wpcs dealerdirect/phpcodesniffer-composer-installer
```

Here's what the packages do:

1. `squizlabs/php_codesniffer` is the PHP_CodeSniffer (PHPCS) package which provides the CLI tools; [link](https://github.com/squizlabs/PHP_CodeSniffer)
2. `wp-coding-standards/wpcs` is the PHPCS configuration to enforce the WordPress coding standards; [link](https://github.com/WordPress/WordPress-Coding-Standards)
3. `dealerdirect/phpcodesniffer-composer-installer` automatically tells PHPCS about the installed WPCS configuration; [link](https://github.com/Dealerdirect/phpcodesniffer-composer-installer)

Once these tools are installed, make sure that they are ready to use by running the following command from the plugin directory:

```sh
$ ./vendor/bin/phpcs -i
```

This should give an output like this:

      The installed coding standards are PEAR, Zend, PSR2, MySource, Squiz,
      PSR1, PSR12, WordPress, WordPress-Extra, WordPress-Docs and
      WordPress-Core


Your installation is successful. Time to for the next step.

### Integrating the WPCS ruleset

Create a file `phpcs.xml` in your plugin directory. This file will instruct
PHPCS on which sniffing rules to use.

For now, use the [default sample](https://github.com/WordPress/WordPress-Coding-Standards/blob/develop/phpcs.xml.dist.sample) as provided by the WPCS repository. Copy the contents of that file and paste them into `phpcs.xml`.

To understand how this configuration works, read the [Custom Ruleset](https://github.com/WordPress/WordPress-Coding-Standards#using-a-custom-ruleset) section of WPCS repository. For now, the above will be a good starting point.

Replace `my_plugin` with the [snake-case](https://en.wikipedia.org/wiki/Snake_case) prefix you want to use for your plugin and `my-plugin` (using dashed) as the text domain of your plugin.

### Running PHPCS using the CLI

Now that everything is set up, we can run the PHPCS command. This will automatically read the `phpcs.xml` file and show errors and warnings as caught by the WPCS ruleset.

You can run the linter against a specific file or against all files in your project at the same time.

#### Running phpcs on a single file

For example, the following will test against a single file called `my-plugin.php`:

```sh
$ ./vendor/bin/phpcs my-plugin.php -s
```

The result will be a list of found issues, similar to this following

```
---------------------------------------------------------------------------------------------------------------
FOUND 4 ERRORS AND 2 WARNINGS AFFECTING 4 LINES
---------------------------------------------------------------------------------------------------------------
 30 | ERROR   | Functions declared in the global namespace by a theme/plugin should start with the
    |         | theme/plugin prefix. Found: "add_admin_notice".
    |         | (WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound)
 30 | ERROR   | You must use "/**" style comments for a function comment
    |         | (Squiz.Commenting.FunctionComment.WrongStyle)
 34 | WARNING | Processing form data without nonce verification.
    |         | (WordPress.Security.NonceVerification.Recommended)
 35 | WARNING | Processing form data without nonce verification.
    |         | (WordPress.Security.NonceVerification.Recommended)
 41 | ERROR   | All output should be run through an escaping function (see the Security sections in the
    |         | WordPress Developer Handbooks), found '$my_plugin_message'.
    |         | (WordPress.Security.EscapeOutput.OutputNotEscaped)
 41 | ERROR   | Inline comments must end in full-stops, exclamation marks, or question marks
    |         | (Squiz.Commenting.InlineComment.InvalidEndChar)
---------------------------------------------------------------------------------------------------------------
```

The first column gives the line number, second column gives the type of violation (error or warning) and the last column gives the issue and corresponding sniff rule description. You'll now have the information you need to fix the found issues.

#### Running phpcs on your whole project

Up until now, we've been running `phpcs` command only on a single file. In
reality, your plugin may have multiple files, located inside a directory. So it is useful to run `phpcs` on all the files instead of one file at a time.

Let us assume the following directory structure of our plugin.

```
inc/
├─ core/
│  ├─ class-my-plugin-install.php
│  ├─ class-my-plugin-boot.php
├─ utils/
│  ├─ class-my-plugin-a.php
│  ├─ class-my-plugin-b.php
my-plugin.php
```

We want `phpcs` to sniff the `my-plugin.php` along with all files inside the `inc` directory. The command would be

```sh
./vendor/bin/phpcs ./inc my-plugin.php -s -p --colors
```

Here we've used additional parameters `-p` which outputs progress and `--colors` which gives beautiful outputs in colors.

![CLI Output](./images/phpcs-cli-output.png 'PHPCS CLI Output')

> TIP: You can add this command in your [composer custom scripts](https://getcomposer.org/doc/articles/scripts.md#writing-custom-commands) to later use.

After running `phpcs` on your codebase for the first time, you may find many errors and warnings. This is normal. You should always work towards have a clean (meaning, no warnings or errors) `phpcs` output for your project. 

> TIP: The Wordpress plugin review team will be running these tests before any plugin you submit is approved so it will save a lot of back and forth if you have them all fixed before submission.


## Setting up PHPCS/WPCS in your text editor

[WPCS Wiki](https://github.com/WordPress/WordPress-Coding-Standards/wiki) has extensive guides on setting up `phpcs` in your favorite editor. Follow those steps and you'll be all set.

Once the sniffer is set up in your browser, you'll see any found errors highlighted for you to address.
