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
 * Plugin Name:       Woo Audio Preview
 * Plugin URI:        http://wbcomdesigns.com
 * Description:       This plugin will add an extended feature to the big name “ WooCommerce ” that will allow you to add audio preview feature in single product page.
 * Version:           1.2.0
 * Author:            Wbcom Designs <admin@wbcomdesigns.com>
 * Author URI:        http://wbcomdesigns.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wc-audio-preview
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'WCAP_TEXT_DOMAIN' ) ) {
	define( 'WCAP_TEXT_DOMAIN', 'wc-audio-preview' );
}

if ( ! defined( 'WCAP_PLUGIN_URI' ) ) {
	define( 'WCAP_PLUGIN_URI', plugin_dir_url( __FILE__ ) );
}
if ( ! defined( 'WCAP_PLUGIN_DIR' ) ) {
	define( 'WCAP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wc-audio-preview.php';

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
		unset( $_GET['activate'] );
	}
}
add_action( 'admin_init', 'wcap_check_require_plugins' );
/**
 * Required Plugin Admin Notice.
 */
function wcap_plugin_admin_notice() {
	$wcap_plugin = esc_html__( 'WooCommerce Audio Preview', 'wc-audio-preview' );
	$wc_plugin   = esc_html__( 'WooCommerce', 'wc-audio-preview' );

	echo '<div class="error"><p>'
	. sprintf( '%1$s is ineffective now as it requires %2$s to function correctly.', '<strong>' . esc_html( $wcap_plugin ) . '</strong>', '<strong>' . esc_html( $wc_plugin ) . '</strong>' )
	. '</p></div>';
	if ( isset( $_GET['activate'] ) ) {
		unset( $_GET['activate'] );
	}
}

add_action( 'activated_plugin', 'woo_audio_preview_activation_redirect_settings' );
/**
 * woo_document_preview_activation_activation_redirect_settings
 *
 * @param  string $plugin plugin.
 * @return void
 */
function woo_audio_preview_activation_redirect_settings( $plugin ) {
	if ( plugin_basename( __FILE__ ) === $plugin ) {
		wp_redirect( admin_url( 'admin.php?page=woo-audio-preview-settings' ) );
		exit;
	}
}
