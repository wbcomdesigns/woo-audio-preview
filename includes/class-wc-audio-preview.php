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
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'woo-audio-preview';
		$this->version     = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wc_Audio_Preview_Loader. Orchestrates the hooks of the plugin.
	 * - Wc_Audio_Preview_I18n. Defines internationalization functionality.
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
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-wc-audio-preview-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		/*
		 * The settings screen is a bundled library, not code owned by this plugin. Every Wbcom
		 * plugin ships a copy and the newest one on the site is the only one loaded, so a fix
		 * shipped here reaches every other plugin's screen too. Registered in the bootstrap, at
		 * include time, because the loader has to see every copy before any plugin boots.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'admin/class-wcap-settings-tabs.php';

		require_once plugin_dir_path( __DIR__ ) . 'admin/class-wc-audio-preview-admin.php';

		/**
		 * The class responsible for admin review.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'admin/class-admin-review.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		/**
		 * Shape-tolerant reader for a product's audio. Loaded before the public class, which
		 * depends on it.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-wcap-audio.php';

		require_once plugin_dir_path( __DIR__ ) . 'public/class-wc-audio-preview-public.php';

		/*
		 * The legacy Wbcom admin tree (admin/wbcom/) was removed on 2026-08-10.
		 *
		 * It registered the WB Plugins hub plus "Our Plugins", "Our Themes" and "Support"
		 * pages, a legacy stylesheet, an admin-ajax handler and a CDN font-awesome request.
		 * The shared settings library owns the hub now, and it already filtered those helper
		 * pages out of its listing - it treated them as superseded. Every plugin shipped its
		 * own copy of the tree guarded by class_exists(), so the copies were a maintenance
		 * fork nobody could see: only the first one loaded ever ran.
		 */

		$this->loader = new Wc_Audio_Preview_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wc_Audio_Preview_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wc_Audio_Preview_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wc_Audio_Preview_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'wcap_register_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'wcap_save_meta_box' );
		$this->loader->add_action( 'post_edit_form_tag', $plugin_admin, 'wcap_update_edit_form' );
		$this->loader->add_action( 'wp_ajax_wcap_delete_audio_ajax', $plugin_admin, 'wcap_delete_audio_ajax' );
		$this->loader->add_action( 'wp_ajax_nopriv_wcap_delete_audio_ajax', $plugin_admin, 'wcap_delete_audio_ajax' );

		/*
		 * This used to stand down entirely when the Pro plugin was active - no menu, no settings
		 * page - on the assumption that Pro replaced it. It does not. This plugin is the core:
		 * it owns the settings screen, the shared admin assets and the base player, and Pro adds
		 * its premium tabs on top through the seams below. Standing down left Pro with nothing to
		 * extend, which is why the two ended up carrying a copy of the same screen each.
		 *
		 * The only thing that changes when Pro is present is that the locked upgrade tabs step
		 * aside for the real ones - handled inside WCAP_Settings_Tabs, not here.
		 */
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'wcap_views_add_admin_settings' );

		/*
		 * Deferred to init because the labels below are translated, and this runs on
		 * plugins_loaded - before translations are available. WordPress 6.7 reports that as
		 * "Translation loading was triggered too early" on EVERY request, storefront included.
		 *
		 * Nothing here needs to run earlier: boot() only registers admin_menu,
		 * admin_enqueue_scripts and in_admin_header callbacks, all of which fire long after
		 * init. The shared library's own highest-version-wins loader still runs at
		 * plugins_loaded -999 and is unaffected.
		 */
		add_action(
			'init',
			function () {
				WCAP_Settings_Tabs::init();
					Wbcom_Settings_Page::boot(
						array(
							'prefix'       => 'wcap',
							'slug'         => 'woo-audio-preview-settings',
							'icon'         => 'audio-lines',
							'assets_url'   => plugin_dir_url( __DIR__ ),
							'version'      => WCAP_TEXT_VERSION,
							'legacy_slugs' => array( 'wcap-pro-settings', 'wbcom-license-page' ),
							'labels'       => array(
								'menu_title' => __( 'Audio Preview', 'woo-audio-preview' ),
								'brand'      => __( 'Audio Preview', 'woo-audio-preview' ),
								'subtitle'   => __( 'Settings', 'woo-audio-preview' ),
								'nav_label'  => __( 'Settings sections', 'woo-audio-preview' ),
								'pro_badge'  => __( 'Pro', 'woo-audio-preview' ),
							),
						)
					);
			}
		);

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

		$plugin_public = new Wc_Audio_Preview_Public( $this->get_plugin_name(), $this->get_version() );

		/**
		 * Filter the object that renders audio previews on the front end.
		 *
		 * This is the seam the Pro plugin uses to take over rendering: it returns a subclass of
		 * Wc_Audio_Preview_Public with the premium behaviour overridden. Everything below then
		 * registers Pro's methods on ONE object, so there is a single renderer rather than two
		 * plugins both hooking the product page and drawing a player each.
		 *
		 * @since 1.5.3
		 * @param Wc_Audio_Preview_Public $plugin_public The renderer.
		 */
		$filtered = apply_filters( 'wcap_public_instance', $plugin_public );

		/*
		 * Anything that is not a Wc_Audio_Preview_Public is ignored rather than trusted. A filter
		 * returning the wrong type would otherwise take the whole product page down with a fatal
		 * on the first method call.
		 */
		if ( $filtered instanceof Wc_Audio_Preview_Public ) {
			$plugin_public = $filtered;
		}

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		/*
		 * Priority 5: shared third-party libraries are registered before anything tries to enqueue
		 * them. This callback is not overridden by Pro, so the handles exist whichever renderer is
		 * running.
		 */
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'register_shared_assets', 5 );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		/**
		 * Filter where the preview is rendered on the product page.
		 *
		 * The free plugin renders before the add-to-cart form. Pro lets the owner choose, and sets
		 * that choice here rather than registering a second render on a different hook - which is
		 * what produced two players on the page when both plugins were active.
		 *
		 * @since 1.5.3
		 * @param string $hook WooCommerce action the preview renders on.
		 */
		$wcap_preview_hook = apply_filters( 'wcap_preview_hook', 'woocommerce_before_add_to_cart_form' );

		$this->loader->add_action( $wcap_preview_hook, $plugin_public, 'wcap_add_preview_field', 0 );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wc_Audio_Preview_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
