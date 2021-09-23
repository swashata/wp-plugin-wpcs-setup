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
All files and setup can be found [in this repository](https://github.com/swashata/wp-plugin-wpcs-setup).

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

### Setup PHPCS Sniffing rules to integrate WPCS

Now create a file `phpcs.xml` in your plugin directory. This file will instruct
PHPCS which sniffing rules to use.

For now, we put the [default sample](https://github.com/WordPress/WordPress-Coding-Standards/blob/develop/phpcs.xml.dist.sample) as provided by the WPCS repository.

```xml
<?xml version="1.0"?>
<ruleset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" name="Example Project" xsi:noNamespaceSchemaLocation="https://raw.githubusercontent.com/squizlabs/PHP_CodeSniffer/master/phpcs.xsd">

	<description>A custom set of rules to check for a WPized WordPress project</description>

	<!-- Exclude the Composer Vendor directory. -->
	<exclude-pattern>/vendor/*</exclude-pattern>

	<!-- Exclude the Node Modules directory. -->
	<exclude-pattern>/node_modules/*</exclude-pattern>

	<!-- Exclude minified Javascript files. -->
	<exclude-pattern>*.min.js</exclude-pattern>

	<!-- Include the WordPress-Extra standard. -->
	<rule ref="WordPress-Extra">
		<!--
		We may want a middle ground though. The best way to do this is add the
		entire ruleset, then rule by rule, remove ones that don't suit a project.
		We can do this by running `phpcs` with the '-s' flag, which allows us to
		see the names of the sniffs reporting errors.
		Once we know the sniff names, we can opt to exclude sniffs which don't
		suit our project like so.

		The below two examples just show how you can exclude rules.
		They are not intended as advice about which sniffs to exclude.
		-->

		<!--
		<exclude name="WordPress.WhiteSpace.ControlStructureSpacing"/>
		<exclude name="WordPress.Security.EscapeOutput"/>
		-->
	</rule>

	<!-- Let's also check that everything is properly documented. -->
	<rule ref="WordPress-Docs"/>

	<!-- Add in some extra rules from other standards. -->
	<rule ref="Generic.CodeAnalysis.UnusedFunctionParameter"/>
	<rule ref="Generic.Commenting.Todo"/>

	<!-- Check for PHP cross-version compatibility. -->
	<!--
	To enable this, the PHPCompatibilityWP standard needs
	to be installed.
	See the readme for installation instructions:
	https://github.com/PHPCompatibility/PHPCompatibilityWP
	For more information, also see:
	https://github.com/PHPCompatibility/PHPCompatibility
	-->
	<!--
	<config name="testVersion" value="5.2-"/>
	<rule ref="PHPCompatibilityWP"/>
	-->

	<!--
	To get the optimal benefits of using WPCS, we should add a couple of
	custom properties.
	Adjust the values of these properties to fit our needs.

	For information on additional custom properties available, check out
	the wiki:
	https://github.com/WordPress/WordPress-Coding-Standards/wiki/Customizable-sniff-properties
	-->
	<config name="minimum_supported_wp_version" value="4.9"/>

	<rule ref="WordPress.WP.I18n">
		<properties>
			<property name="text_domain" type="array">
				<element value="my-plugin"/>
			</property>
		</properties>
	</rule>

	<rule ref="WordPress.NamingConventions.PrefixAllGlobals">
		<properties>
			<property name="prefixes" type="array">
				<element value="my_plugin"/>
			</property>
		</properties>
	</rule>

</ruleset>
```

To understand how this configuration works, read the [Custom Ruleset](https://github.com/WordPress/WordPress-Coding-Standards#using-a-custom-ruleset)
section of WPCS repository. For now, the above will be a good starting point.

Replace `my_plugin` with the snake-case prefix you want to use for your plugin
and `my-plugin` with the text domain of your plugin.

### Running PHPCS through CLI

Now that everything is setup, we can run the PHPCS command. This will automatically
read the `phpcs.xml` file, and shows errors and warnings as caught by the
WPCS ruleset.

Let us assume, we have a file `my-plugin.php` with the following source code.

```php
<?php
/**
 * Plugin Name
 *
 * @package           PluginPackage
 * @author            Your Name
 * @copyright         2019 Your Name or Company Name
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Plugin Name
 * Plugin URI:        https://example.com/plugin-name
 * Description:       Description of the plugin.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Your Name
 * Author URI:        https://example.com
 * Text Domain:       plugin-slug
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Update URI:        https://example.com/my-plugin/
 */

// Following just some sample code, intentionally made insecure to demonstrate
// the usability of wpcs. DO NOT USE SUCH BAD CODE IN YOUR PLUGIN.
add_action( 'admin_notices', 'add_admin_notice' );

// error here.
function add_admin_notice() {
	// get the redirection message
	// A BAD EXAMPLE OF HOW NOT TO MAKE STUFF
	// WPCS WILL CATCH THIS.
	$my_plugin_message = isset( $_GET['my_plugin_admin_msg'] ) // warning here.
		? $_GET['my_plugin_admin_msg']
		: '';
	if ( $my_plugin_message ) {
		?>
		<div class="notice notice-success">
			<p>
				<?php echo $my_plugin_message; // error here ?>
			</p>
		</div>
		<?php
	}
}

```

From your plugin directory, run the following command.

```sh
./vendor/bin/phpcs my-plugin.php -s
```

The source of the file can also be found in the [example repository](https://github.com/swashata/wp-plugin-wpcs-setup/blob/main/my-plugin.php).
The output of the above command should be like this.

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

The first column gives the line number, second column gives the type of violation
(error or warning) and the last column gives the issue and corresponding sniff
rule.

### Fixing issues reported by phpcs

Now that we know about the errors, it is time to fix it. A possible fix for
the above code would be something like this.

```diff
- add_action( 'admin_notices', 'add_admin_notice' );
+ add_action( 'admin_notices', 'my_plugin_add_admin_notice' );

// error here.
- function add_admin_notice() {
+ function my_plugin_add_admin_notice() {
	// get the redirection message
	// A BAD EXAMPLE OF HOW NOT TO MAKE STUFF
	// WPCS WILL CATCH THIS.
	$my_plugin_message = isset( $_GET['my_plugin_admin_msg'] ) // warning here.
		? $_GET['my_plugin_admin_msg']
		: '';
	if ( $my_plugin_message ) {
		?>
		<div class="notice notice-success">
			<p>
-				<?php echo $my_plugin_message; // error here ?>
+				<?php echo esc_html( $my_plugin_message ); ?>
			</p>
		</div>
		<?php
	}
}
```

Notice that we have,

1. Prefixed our function `add_admin_notice` with `my_plugin`.
2. Use the [escaping method](https://developer.wordpress.org/plugins/security/securing-output/) `esc_html` to secure output.

Now when we run the `phpcs` command again, we get the following output

```
❯ ./vendor/bin/phpcs my-plugin.php -s

---------------------------------------------------------------------------------------------------------------
FOUND 1 ERROR AND 2 WARNINGS AFFECTING 3 LINES
---------------------------------------------------------------------------------------------------------------
 30 | ERROR   | You must use "/**" style comments for a function comment
    |         | (Squiz.Commenting.FunctionComment.WrongStyle)
 34 | WARNING | Processing form data without nonce verification.
    |         | (WordPress.Security.NonceVerification.Recommended)
 35 | WARNING | Processing form data without nonce verification.
    |         | (WordPress.Security.NonceVerification.Recommended)
---------------------------------------------------------------------------------------------------------------
```

### Gradual adaptation of WPCS rules

We are still given 1 error and 2 warnings at this point. Given our code, we may
argue that the warnings are irrelevant. So we can leave them as-is.

For the error, it is related to how we should document our function. Since this
is not really related to any security vulnerability, rather readability,
we can suppress it.

```diff
+ // phpcs:ignore Squiz.Commenting.FunctionComment.WrongStyle, Squiz.Commenting.FunctionComment.Missing
function my_plugin_add_admin_notice() {
```

You can also edit the `phpcs.xml` file and disable the rules for all files.

```diff
	<!-- Let's also check that everything is properly documented. -->
-   <rule ref="WordPress-Docs"/>
+	<rule ref="WordPress-Docs">
+		<exclude name="Squiz.Commenting.FunctionComment.WrongStyle"/>
+	</rule>
```

> After running `phpcs` on your codebase for the first time, you may find many
> errors and warnings. Always fix all issues coming from `WordPress.Security`
> and NEVER disable them.

### Running phpcs on your whole project

Up until now, we've been running `phpcs` command only on a single file. In
reality, your plugin may have multiple files, located inside a directory. So
it is useful to run `phpcs` on all the files instead of one file at a time.

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

We want `phpcs` to sniff the `my-plugin.php` along with all files inside the
`inc` directory. The command would be

```sh
./vendor/bin/phpcs ./inc my-plugin.php -s -p --colors
```

Here we've used additional parameters `-p` which outputs progress and `--colors`
which gives beautiful outputs in colors.

![CLI Output](./images/phpcs-cli-output.png 'PHPCS CLI Output')

You can go ahead and add this command in your [composer custom scripts](https://getcomposer.org/doc/articles/scripts.md#writing-custom-commands).

## Setting up PHPCS/WPCS in your text editor

[WPCS Wiki](https://github.com/WordPress/WordPress-Coding-Standards/wiki) has
extensive guides on setting up phpcs in your favorite editor. We will see below
how to setup phpcs in [Visual Studio Code (VSCode)](https://code.visualstudio.com/).

First install [PHP_CodeSniffer](https://marketplace.visualstudio.com/items?itemName=obliviousharmony.vscode-php-codesniffer)
extension in your VSCode editor. This is a more updated and maintained
version of the original [phpcs](https://marketplace.visualstudio.com/items?itemName=ikappas.phpcs)
extension with many added features.

![VSCode Settings for PHP_CodeSniffer](./images/php-codesniffer-vscode-settings.png 'VSCode Settings for PHP_CodeSniffer')

Now set `"phpCodeSniffer.autoExecutable": true` and `"phpCodeSniffer.standard": "Default"`
in your user settings. This will make the extension work with the locally installed
`phpcs` along with the `phpcs.xml` we've defined.

![PHPCS Outputs in VSCode](./images/phpcs-error-in-vscode.png 'PHPCS Results shown inside VSCode')

When you open the `my-plugin.php` file, you will see the errors highlighted
in your editor.
