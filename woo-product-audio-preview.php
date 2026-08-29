<?php
/**
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://wbcomdesigns.com
 * @since             1.0.0
 * @package           Wc_Audio_Preview
 *
 * @wordpress-plugin
 * Plugin Name:       Audio Preview for WooCommerce
 * Plugin URI:        https://wbcomdesigns.com
 * Description:       Add audio previews to WooCommerce products. Let customers listen before they buy with major audio formats and CDN support.
 * Version:           1.5.1
 * Author:            Wbcom Designs <admin@wbcomdesigns.com>
 * Author URI:        http://wbcomdesigns.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woo-audio-preview
 * Requires Plugins:  woocommerce
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'WCAP_TEXT_VERSION' ) ) {
	define( 'WCAP_TEXT_VERSION', '1.5.1' );
}

if ( ! defined( 'WCAP_TEXT_DOMAIN' ) ) {
	define( 'WCAP_TEXT_DOMAIN', 'woo-audio-preview' );
}

if ( ! defined( 'WCAP_PLUGIN_URI' ) ) {
	define( 'WCAP_PLUGIN_URI', plugin_dir_url( __FILE__ ) );
}
if ( ! defined( 'WCAP_PLUGIN_DIR' ) ) {
	define( 'WCAP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'WCAP_PLUGIN_FILE' ) ) {
	define( 'WCAP_PLUGIN_FILE', __FILE__ );
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wc-audio-preview.php';

/*
 * The audio-data reader is DEFINED at include time for the same reason as the public
 * class below: the Pro add-on reads stored audio through WCAP_Audio::get() during its
 * own boot (plugins_loaded priority 4), which runs before this plugin boots. Defining
 * the class here - a side-effect-free definition during the plugin-include phase -
 * guarantees it exists when Pro (or this plugin's own renderer) calls it.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-wcap-audio.php';

/*
 * The public class is DEFINED at include time, separately from booting.
 *
 * The Pro add-on subclasses Wc_Audio_Preview_Public, and WordPress orders plugins by directory
 * path: "woo-audio-preview-pro/" sorts BEFORE "woo-audio-preview/" (a hyphen sorts before a
 * slash), so Pro's file is included first and Pro boots on plugins_loaded (priority 4) before
 * this plugin boots (priority 10). If the parent class were only defined during this plugin's
 * boot, Pro would fatally extend a class that does not exist yet.
 *
 * A class definition has no side effects, so defining it here - during the plugin-include phase,
 * before any plugins_loaded hook fires - costs nothing and removes the ordering problem. The
 * core class also require_once's this file during its own boot, so this does not load it twice.
 */
require_once plugin_dir_path( __FILE__ ) . 'public/class-wc-audio-preview-public.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wc_audio_preview() {
	$plugin = new Wc_Audio_Preview();
	$plugin->run();
}

/**
 * Check plugin requirement on plugins loaded
 * this plugin requires WooCommerce to be installed and active
 */
function wcap_plugin_init() {
	run_wc_audio_preview();
}
add_action( 'plugins_loaded', 'wcap_plugin_init' );
/**
 * Check plugin requirement on plugins loaded
 * this plugin requires WooCommerce to be installed and active
 */
function wcap_check_require_plugins() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		add_action( 'admin_notices', 'wcap_plugin_admin_notice' );
		deactivate_plugins( plugin_basename( __FILE__ ) );
		if ( isset( $_GET['activate'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			unset( $_GET['activate'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}
		return false;
	}
	return true;
}
add_action( 'admin_init', 'wcap_check_require_plugins' );
/**
 * Required Plugin Admin Notice.
 */
function wcap_plugin_admin_notice() {
	$wcap_plugin = esc_html__( 'Audio Preview for WooCommerce', 'woo-audio-preview' );
	$wc_plugin   = esc_html__( 'WooCommerce', 'woo-audio-preview' );

	echo '<div class="error"><p>'
	. sprintf( '%1$s cannot function without %2$s . Please install and activate WooCommerce', '<strong>' . esc_html( $wcap_plugin ) . '</strong>', '<strong>' . esc_html( $wc_plugin ) . '</strong>' )
	. '</p></div>';
	if ( null !== filter_input( INPUT_GET, 'activate' ) ) {
		$activate = filter_input( INPUT_GET, 'activate' );
		unset( $activate );
	}
}

add_action( 'activated_plugin', 'wcap_activation_redirect_settings' );
/**
 * Actions performed to check the depedency of the plugin.
 *
 * @since  1.0.0
 *
 * @param string $plugin Path to the plugin file relative to the plugins directory.
 */
function wcap_activation_redirect_settings( $plugin ) {
	$plugins_all = get_option( 'active_plugins' );
	if ( plugin_basename( __FILE__ ) === $plugin && in_array( 'woocommerce/woocommerce.php', $plugins_all, true ) ) {
		if ( isset( $_REQUEST['action'] ) && 'activate' === sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ) && isset( $_REQUEST['plugin'] ) && sanitize_text_field( wp_unslash( $_REQUEST['plugin'] ) ) === $plugin ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			wp_safe_redirect( admin_url( 'admin.php?page=woo-audio-preview-settings' ) );
			exit;
		}
	}
	if ( isset( $_REQUEST['plugin'] ) && $plugin === sanitize_text_field( wp_unslash( $_REQUEST['plugin'] ) ) && class_exists( 'Buddypress' ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_REQUEST['action'] ) && 'activate-plugin' === sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ) && isset( $_REQUEST['plugin'] ) && sanitize_text_field( wp_unslash( $_REQUEST['plugin'] ) ) === $plugin ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			set_transient( '_woo_audio_preview_is_new_install', true, 30 );
		}
	}
}
/**
 * Actions performed to redirection on the activation.
 *
 * @return void
 */
function wcap_do_activation_redirect() {
	if ( get_transient( '_woo_audio_preview_is_new_install' ) ) {
		delete_transient( '_woo_audio_preview_is_new_install' );
		wp_safe_redirect( admin_url( 'admin.php?page=woo-audio-preview-settings' ) );
	}
}
add_action( 'admin_init', 'wcap_do_activation_redirect' );
