<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://juslintek.rf.gd
 * @since             1.0.0
 * @package           Grfe
 *
 * @wordpress-plugin
 * Plugin Name:       Google Remarketing Feed Export
 * Plugin URI:        http://juslintek.rf.gd
 * Description:       This plugin exports product feed as required by Google Dynamic Remarketing standards.
 * Version:           1.0.0
 * Author:            Linas Jusys
 * Author URI:        http://juslintek.rf.gd
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       grfe
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'GRFE_PATH', plugin_dir_path( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-grfe-activator.php
 */
function activate_grfe() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-grfe-activator.php';
	Grfe_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-grfe-deactivator.php
 */
function deactivate_grfe() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-grfe-deactivator.php';
	Grfe_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_grfe' );
register_deactivation_hook( __FILE__, 'deactivate_grfe' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-grfe.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_grfe() {

	$plugin = new Grfe();
	$plugin->run();

}
run_grfe();
