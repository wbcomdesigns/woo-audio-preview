<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @link       http://wbcomdesigns.com
 * @since      1.0.0
 * @package    Wc_Audio_Preview
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Delete plugin options.
delete_option( 'wcap_admin_errors' );
delete_option( 'woo_audio_feedback_activation_date' );
delete_option( 'woo_audio_feedback_no_bug' );

// Delete audio preview post meta from all products.
global $wpdb;
$wpdb->delete( $wpdb->postmeta, array( 'meta_key' => 'wcap_audio' ) );
$wpdb->delete( $wpdb->postmeta, array( 'meta_key' => 'wcap_preview_attachment' ) );
