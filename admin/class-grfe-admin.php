<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://estrategai.lt
 * @since      1.0.0
 *
 * @package    Grfe
 * @subpackage Grfe/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Grfe
 * @subpackage Grfe/admin
 * @author     Linas Jusys <linas@estrategai.lt>
 */
class Grfe_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The options name to be used in this plugin
	 *
	 * @since  	1.0.0
	 * @access 	private
	 * @var  	string 		$option_name 	Option name of this plugin
	 */
	private $option_name = 'grfe_control';

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Grfe_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Grfe_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/grfe-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Grfe_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Grfe_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/grfe-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add an options page under the Settings submenu
	 *
	 * @since  1.0.0
	 */
	public function add_options_page() {

		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'GRFE Settings', 'grfe' ),
			__( 'GRFE Settings', 'grfe' ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_options_page' )
		);

	}

	/**
	 * Render the options page for plugin
	 *
	 * @since  1.0.0
	 */
	public function display_options_page() {
		include_once 'partials/grfe-admin-display.php';
	}

	/**
	 * Register all related settings of this plugin
	 *
	 * @since  1.0.0
	 */
	public function register_setting() {
		add_settings_section(
			$this->option_name . '_section',
			__( 'General', 'grfe' ),
			array( $this, $this->option_name . '_section_cb' ),
			$this->plugin_name
		);
		add_settings_field(
			$this->option_name . '_address',
			__( 'Products Address', 'grfe' ),
			array( $this, $this->option_name . '_address_cb' ),
			$this->plugin_name,
			$this->option_name . '_section',
			array( 'label_for' => $this->option_name . '_address' )
		);
		register_setting( $this->plugin_name, $this->option_name . '_address', array( $this, $this->option_name . '_sanitize_address' ) );
	}

	/**
	 * Render the text for the general section
	 *
	 * @since  1.0.0
	 */
	public function grfe_control_section_cb() {
		echo '<p>' . __( 'Please change the settings accordingly.', 'grfe' ) . '</p>';
	}

	/**
	 * Render the treshold day input for this plugin
	 *
	 * @since  1.0.0
	 */

	public function grfe_control_address_cb() {
		$address = get_option( $this->option_name . '_address' );
		?>
		<fieldset>
			<label for="<?php echo $this->option_name . '_address' ?>"></label>
			<textarea name="<?php echo $this->option_name . '_address' ?>" id="<?php echo $this->option_name . '_address' ?>" cols="110" rows="6"><?php echo $address ?></textarea>
		</fieldset>
		<?php
	}

	/**
	 * Sanitize the text position value before being saved to database
	 *
	 * @param  string $position $_POST value
	 * @since  1.0.0
	 * @return string           Sanitized value
	 */
	public function grfe_control_sanitize_address( $address ) {
			return wp_strip_all_tags($address, true);
	}

}
