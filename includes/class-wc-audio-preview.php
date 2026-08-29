<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://wbcomdesigns.com
 * @since      1.0.0
 *
 * @package    Wc_Audio_Preview
 * @subpackage Wc_Audio_Preview/includes
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wc_Audio_Preview
 * @subpackage Wc_Audio_Preview/includes
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
class Wc_Audio_Preview {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wc_Audio_Preview_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Load the dependencies and set the hooks for the admin area and the
	 * public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wc_Audio_Preview_Loader. Orchestrates the hooks of the plugin.
	 * - Wc_Audio_Preview_Admin. Defines all hooks for the admin area.
	 * - Wc_Audio_Preview_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-wc-audio-preview-loader.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'admin/class-wc-audio-preview-admin.php';

		/**
		 * The class responsible for admin review.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'admin/class-admin-review.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'public/class-wc-audio-preview-public.php';

		require_once plugin_dir_path( __DIR__ ) . 'admin/wbcom/wbcom-admin-settings.php';

		$this->loader = new Wc_Audio_Preview_Loader();
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wc_Audio_Preview_Admin( 'woo-audio-preview', '1.0.0' );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'wcap_register_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'wcap_save_meta_box' );
		$this->loader->add_action( 'post_edit_form_tag', $plugin_admin, 'wcap_update_edit_form' );
		$this->loader->add_action( 'wp_ajax_wcap_delete_audio_ajax', $plugin_admin, 'wcap_delete_audio_ajax' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'wcap_init_plugin_settings' );

		/*
		 * The settings screen is the shared Wbcom_Settings_Page shell. This plugin always boots it
		 * (it is the core the Pro add-on extends, so it is the one that owns the screen); Pro adds
		 * its tabs into the same shell rather than registering a second menu. boot() runs on init:1
		 * to be in place before the shell's own admin_menu registration, and the parent WB Plugins
		 * menu is created on admin_menu:5 if no other suite plugin has beaten us to it.
		 */
		$this->loader->add_action( 'init', $plugin_admin, 'boot_settings_page', 1 );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'register_parent_menu', 5 );
		$this->loader->add_action( 'in_admin_header', $plugin_admin, 'wcap_hide_all_admin_notices_from_setting_page' );
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'wcap_display_admin_errors' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wc_Audio_Preview_Public( 'woo-audio-preview', '1.0.0' );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'woocommerce_before_add_to_cart_form', $plugin_public, 'wcap_add_preview_field', 0 );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}
}
