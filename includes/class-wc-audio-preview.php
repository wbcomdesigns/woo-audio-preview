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
	 * - Wc_Audio_Preview_Admin. Defines all hooks for the admin area.
	 * - Wc_Audio_Preview_Public. Defines all hooks for the public side of the site.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

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

		/*
		 * The legacy Wbcom admin tree (admin/wbcom/) was removed on 2026-08-29.
		 *
		 * It registered the WB Plugins hub plus "Our Plugins" and "Support" pages, a legacy
		 * stylesheet, an admin-ajax handler and a CDN font-awesome request. The shared settings
		 * library (lib/wbcom-settings/) owns the hub now, and it already filtered those helper
		 * pages out of its listing - it treated them as superseded. Every plugin shipped its own
		 * copy of the tree guarded by class_exists(), so the copies were a maintenance fork
		 * nobody could see: only the first one loaded ever ran. Pro dropped its copy on
		 * 2026-08-10; this mirrors that removal.
		 */
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

		add_action( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_scripts' ) );
		add_action( 'add_meta_boxes', array( $plugin_admin, 'wcap_register_meta_boxes' ) );
		add_action( 'save_post', array( $plugin_admin, 'wcap_save_meta_box' ) );
		add_action( 'post_edit_form_tag', array( $plugin_admin, 'wcap_update_edit_form' ) );
		add_action( 'wp_ajax_wcap_delete_audio_ajax', array( $plugin_admin, 'wcap_delete_audio_ajax' ) );
		add_action( 'admin_init', array( $plugin_admin, 'wcap_init_plugin_settings' ) );

		/*
		 * The settings screen is the shared Wbcom_Settings_Page shell. This plugin always boots it
		 * (it is the core the Pro add-on extends, so it is the one that owns the screen); Pro adds
		 * its tabs into the same shell rather than registering a second menu. boot() runs on init:1
		 * to be in place before the shell's own admin_menu registration, and the parent WB Plugins
		 * menu is created on admin_menu:5 if no other suite plugin has beaten us to it.
		 */
		add_action( 'init', array( $plugin_admin, 'boot_settings_page' ), 1 );
		add_action( 'admin_menu', array( $plugin_admin, 'register_parent_menu' ), 5 );
		add_action( 'in_admin_header', array( $plugin_admin, 'wcap_hide_all_admin_notices_from_setting_page' ) );
		add_action( 'admin_notices', array( $plugin_admin, 'wcap_display_admin_errors' ) );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		/*
		 * Pro answers this filter with its own subclass instance so there is one renderer and
		 * one set of enqueues, its premium behaviour overriding free's. Without the filter Pro's
		 * player, assets and settings never reach the front end.
		 */
		$plugin_public = apply_filters(
			'wcap_public_instance',
			new Wc_Audio_Preview_Public( 'woo-audio-preview', WCAP_TEXT_VERSION )
		);

		add_action( 'wp_enqueue_scripts', array( $plugin_public, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $plugin_public, 'enqueue_scripts' ) );

		/*
		 * Where the preview renders is a Pro setting, so the hook is filterable. Free defaults to
		 * before the add-to-cart form; Pro returns the position the owner chose.
		 */
		$hook = apply_filters( 'wcap_preview_hook', 'woocommerce_before_add_to_cart_form' );
		add_action( $hook, array( $plugin_public, 'wcap_add_preview_field' ), 0 );
	}
}
