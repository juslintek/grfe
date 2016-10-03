<?php

/**
 * Fired during plugin activation
 *
 * @link       http://estrategai.lt
 * @since      1.0.0
 *
 * @package    Grfe
 * @subpackage Grfe/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Grfe
 * @subpackage Grfe/includes
 * @author     Linas Jusys <linas@estrategai.lt>
 */
class Grfe_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		// Require Woocommerce plugin
		if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) and current_user_can( 'activate_plugins' ) ) {
			// Stop activation redirect and show error
			wp_die('Sorry, but this plugin requires the Woocommerce to be installed and active. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
		}

		Grfe::rewrite_rules();
		flush_rewrite_rules();
	}

}
