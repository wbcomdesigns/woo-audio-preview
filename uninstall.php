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

// Delete the per-site error queue.
delete_option( 'wcap_admin_errors' );

// The review-nag keys are stored with add_site_option()/get_site_option(), so they must be
// removed with delete_site_option() - delete_option() leaves them orphaned on multisite.
// On single site delete_site_option() falls back to delete_option(), so this is correct on both.
delete_site_option( 'woo_audio_feedback_activation_date' );
delete_site_option( 'woo_audio_feedback_no_bug' );

// Delete audio preview post meta from all products.
global $wpdb;
$wpdb->delete( $wpdb->postmeta, array( 'meta_key' => 'wcap_audio' ) );
