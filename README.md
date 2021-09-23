# Setup WPCS

Sniffing or Linting is a process, where we use an automated tool to detect
possible issues in our codebase. For example, let us consider the recommended
[WordPress Coding Standards](https://make.wordpress.org/core/handbook/best-practices/coding-standards/)
of [PHP files](https://make.wordpress.org/core/handbook/best-practices/coding-standards/php/).
It may not be possible to remember all the standards and vigilantly avoid any
issues. Luckily, we don't have to. We have a set of tools, that work
in the command line and in your favorite code editor to help you detect any
possible violations as you write your code.

Using such tools during development has many advantages.

1. You can detect many possible [security issues](https://developer.wordpress.org/plugins/security/) early in your development.
2. You can adhere to WordPress coding standards without having to manually check every time you make changes.
3. Having a fixed set of rules for code, helps in readability and also helps avoid common coding errors.

You can read more about why a coding standard is useful in the [WordPress Coding Standards handbook](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/).
The primary goal of this documentation article is to:

1. Help you setup the linting tool [WPCS](https://github.com/WordPress/WordPress-Coding-Standards) in your plugin's codebase.
2. Running the linter and understanding the output through a CLI.
3. Integrate the same tooling in your favorite code editor.

## What is PHPCS and WPCS

PHPCS (short for [**PHP_CodeSniffer**](https://github.com/squizlabs/PHP_CodeSniffer))
is the primary linting tool. When installed, you can run PHPCS on your project.
It will scan your PHP files and report any violations of specified rules.

_If you're coming from a JavaScript background, think of it like ESLint._

WPCS (short for [**WordPress Coding Standards**](https://github.com/WordPress/WordPress-Coding-Standards))
is a set of PHP_CodeSniffer (PHPCS) rules, that enforces WordPress coding conventions.

So for all intents and purposes,

- PHPCS is the program that scans your PHP files and reports any violations.
- WPCS is a configuration _for_ PHPCS that provides the sniffing rules related to WordPress coding standards.

Now we will see how to install and run PHPCS.

## Setting up PHPCS through CLI

Before we dive into editor integration, it is prudent that we understand how
PHPCS actually works and how we tell PHPCS to use the rules provided from WPCS.

There are many ways to install PHPCS along with WPCS. You can find them
[here on the official repository](https://github.com/WordPress/WordPress-Coding-Standards#installation).

In this guide, we will use [composer](https://getcomposer.org/) to install both PHPCS and WPCS as development
dependency of our Plugin. Installing this way means we can keep the installation
localized and easily use different configurations (which we will see momentarily)
for different projects. Even you're trying out PHPCS/WPCS for the first time, do
try to install and setup this way.

**All the commands below are run from the Plugin's directory**. If you're on
Windows, using [Git Bash For Windows](https://git-scm.com/downloads) is recommended.

### Installing Composer

If you've not already installed composer on your computer follow the
[official installation guide](https://getcomposer.org/doc/00-intro.md). Now
run the following command on your plugin directory.

```sh
composer init
```

This will give you an interactive prompt. Here's the sample answers.

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

Once done, it will create a `composer.json` file in your plugin directory. Now
we're ready to install PHPCS and WPCS.

### Installing PHPCS and WPCS as composer dev dependency

From the plugin directory, run the following command.

```sh
composer require --dev squizlabs/php_codesniffer wp-coding-standards/wpcs dealerdirect/phpcodesniffer-composer-installer
```

Here's what the packages do:

1. `squizlabs/php_codesniffer` - Is the PHP_CodeSniffer (PHPCS) package which provides the CLI tools.
2. `wp-coding-standards/wpcs` - Is the PHPCS configuration to enforce the WordPress coding standards.
3. `dealerdirect/phpcodesniffer-composer-installer` - This package automatically tells PHPCS about the installed WPCS configuration.

Now run the following command from the plugin directory.

```sh
./vendor/bin/phpcs -i
```

This should give an output like this:

```
The installed coding standards are PEAR, Zend, PSR2, MySource, Squiz, PSR1, PSR12, WordPress, WordPress-Extra, WordPress-Docs and WordPress-Core
```

It means your installation is successful.
