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
