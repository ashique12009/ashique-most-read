<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://https://ashique12009.blogspot.com/
 * @since             1.0.0
 * @package           Ashique_Most_Read
 *
 * @wordpress-plugin
 * Plugin Name:       Most read posts in X Days
 * Plugin URI:        https://github.com/ashique12009
 * Description:       Show most read posts depends on your settings
 * Version:           1.0.0
 * Author:            khandoker Ashique Mahamud
 * Author URI:        https://ashique12009.blogspot.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ashique-most-read
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ASHIQUE_MOST_READ_VERSION', '1.0.0' );

define( 'ASHIQUE_MOST_READ_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'ASHIQUE_MOST_READ_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'ASHIQUE_MOST_READ_POST_TEXTDOMAIN', 'ashique-most-read' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ashique-most-read-activator.php
 */
function activate_ashique_most_read() {
	require_once ASHIQUE_MOST_READ_PLUGIN_DIR . 'includes/class-ashique-most-read-activator.php';
	Ashique_Most_Read_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ashique-most-read-deactivator.php
 */
function deactivate_ashique_most_read() {
	require_once ASHIQUE_MOST_READ_PLUGIN_DIR . 'includes/class-ashique-most-read-deactivator.php';
	Ashique_Most_Read_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ashique_most_read' );
register_deactivation_hook( __FILE__, 'deactivate_ashique_most_read' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require ASHIQUE_MOST_READ_PLUGIN_DIR . 'includes/class-ashique-most-read.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ashique_most_read() {

	$plugin = new Ashique_Most_Read();
	$plugin->run();

}
run_ashique_most_read();
