<?php

/**
 * Fired during plugin activation
 *
 * @link       http://wbcomdesigns.com
 * @since      1.0.0
 *
 * @package    Wc_Audio_Preview
 * @subpackage Wc_Audio_Preview/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wc_Audio_Preview
 * @subpackage Wc_Audio_Preview/includes
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
class Wc_Audio_Preview_Activator {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate() {
        if (!is_plugin_active('woocommerce/woocommerce.php') and current_user_can('activate_plugins')) {
            // Stop activation redirect and show error
            wp_die('Sorry, but this plugin requires the WooCommerce Plugin to be installed and active. <br><a href="' . admin_url('plugins.php') . '">&laquo; Return to Plugins</a>');
        } else {
            $cfup_upload = wp_upload_dir();
            $cfup_upload_dir = $cfup_upload['basedir'];
            $cfup_upload_dir = $cfup_upload_dir . '/wcap_files/';
            if (!file_exists($cfup_upload_dir)) {
                mkdir($cfup_upload_dir, 0755, true);
            }
        }
    }

}
