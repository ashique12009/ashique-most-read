<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://https://ashique12009.blogspot.com/
 * @since      1.0.0
 *
 * @package    Ashique_Most_Read
 * @subpackage Ashique_Most_Read/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ashique_Most_Read
 * @subpackage Ashique_Most_Read/admin
 * @author     khandoker Ashique Mahamud <ashique12009@gmail.com>
 */
class Ashique_Most_Read_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

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
		 * defined in Ashique_Most_Read_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ashique_Most_Read_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, ASHIQUE_MOST_READ_PLUGIN_URL . 'admin/css/ashique-most-read-admin.css', array(), $this->version, 'all' );

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
		 * defined in Ashique_Most_Read_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ashique_Most_Read_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, ASHIQUE_MOST_READ_PLUGIN_URL . 'admin/js/ashique-most-read-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Set settings menu
	 */
	public function ashique_admin_settings_option_menu() {
		$page_title = 'Most read posts';
		$menu_title = 'Most read posts';
		$capability = 'manage_options';
		$menu_slug = 'most-read-posts-settings';
		add_options_page(
			$page_title,
			$menu_title,
			$capability,
			$menu_slug,
			[$this, 'ashique_admin_settings_page_display']
		);
	}

	/**
	 * Admin settings page display
	 */
	public function ashique_admin_settings_page_display() {
		$view = isset( $_GET['view'] ) ? $_GET['view'] : '';
		if ($view == '')
			include 'partials/ashique-most-read-admin-settings-page.php';
		else {
			if ( ! class_exists('WP_List_Table') )
				require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';

			include 'partials/ashique-most-read-admin-posts-log-page.php';
			include 'partials/ashique-most-read-admin-posts-log-sum-page.php';
		}
	}

	/**
	 * Show admin notice for settings success or error
	 */
	public function ashique_admin_notice_for_settings() {

		$screen = get_current_screen();

		if ($screen->id === 'settings_page_most-read-posts-settings') {
			$error = ( isset( $_GET['error'] ) ) ? sanitize_text_field( $_GET['error'] ) : '';
			if ($error === '1') {
				?>
				<div class="notice notice-error is-dismissible">
					<p><?php _e( 'Set at least 1 to post number!', 'ashique-most-read' ); ?></p>
				</div>
				<?php 
			}
			elseif ($error === '2') {
				?>
				<div class="notice notice-error is-dismissible">
					<p><?php _e( 'Set at least 1 to days number!', 'ashique-most-read' ); ?></p>
				</div>
				<?php 
			}
			elseif ($error === '3') {
				?>
				<div class="notice notice-success is-dismissible">
					<p><?php _e( 'Settings saved!', 'ashique-most-read' ); ?></p>
				</div>
				<?php 
			}
		}
	}

}
