<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://iamsabbir.dev
 * @since             1.0.0
 * @package           Easy_Coupons
 *
 * @wordpress-plugin
 * Plugin Name:       Easy Coupons
 * Plugin URI:        #
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.1
 * Author:            Sabbir Hasan
 * Author URI:        https://iamsabbir.dev
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       easy-coupons
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
define( 'EASY_COUPONS_VERSION', '1.0.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-easy-coupons-activator.php
 */
function activate_easy_coupons() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-easy-coupons-activator.php';
	Easy_Coupons_Activator::activate();
	Easy_Coupons_Activator::seed_database();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-easy-coupons-deactivator.php
 */
function deactivate_easy_coupons() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-easy-coupons-deactivator.php';
	Easy_Coupons_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_easy_coupons' );
register_deactivation_hook( __FILE__, 'deactivate_easy_coupons' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-easy-coupons.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_easy_coupons() {

	$plugin = new Easy_Coupons();
	$plugin->run();

}
run_easy_coupons();
