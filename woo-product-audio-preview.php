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
 * Plugin URI:        http://wbcomdesigns.com
 * Description:       Audio Preview for WooCommerce lets you showcase audio tracks in a secure sample mode, allowing users to listen to a short preview on the product page while preventing unauthorized downloads of the original audio.
 * Version:           1.4.5
 * Author:            Wbcom Designs <admin@wbcomdesigns.com>
 * Author URI:        http://wbcomdesigns.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wc-audio-preview
 * Requires Plugins:  woocommerce
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'WCAP_TEXT_VERSION' ) ) {
	define( 'WCAP_TEXT_VERSION', '1.4.5' );
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
	if (!class_exists('WooCommerce')) {
        add_action('admin_notices', 'wcap_plugin_admin_notice');
        deactivate_plugins(plugin_basename(__FILE__));
        if (isset($_GET['activate'])) { //phpcs:ignore
            unset($_GET['activate']);
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
	$wcap_plugin = esc_html__( 'Audio Preview for WooCommerce', 'wc-audio-preview' );
	$wc_plugin   = esc_html__( 'WooCommerce', 'wc-audio-preview' );

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
	if ( plugin_basename( __FILE__ ) === $plugin && in_array( 'woocommerce/woocommerce.php', $plugins_all ) ) {
		if ( isset( $_REQUEST['action'] ) && $_REQUEST['action']  == 'activate' && isset( $_REQUEST['plugin'] ) && $_REQUEST['plugin'] == $plugin) {//phpcs:ignore
			wp_safe_redirect( admin_url( 'admin.php?page=woo-audio-preview-settings' ) );
			exit;
		}
	}
	if ( $plugin == $_REQUEST['plugin'] && class_exists( 'Buddypress' ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_REQUEST['action'] ) && $_REQUEST['action']  == 'activate-plugin' && isset( $_REQUEST['plugin'] ) && $_REQUEST['plugin'] == $plugin) { //phpcs:ignore		
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
