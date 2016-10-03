<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://estrategai.lt
 * @since      1.0.0
 *
 * @package    Grfe
 * @subpackage Grfe/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Grfe
 * @subpackage Grfe/includes
 * @author     Linas Jusys <linas@estrategai.lt>
 */
class Grfe_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		flush_rewrite_rules();
	}

}
