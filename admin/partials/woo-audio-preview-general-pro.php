<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://wbcomdesigns.com
 * @since      1.0.0
 *
 * @package    Woo_Audio_Preview
 * @subpackage Woo_Audio_Preview/admin/partials
 */

?>
<div class="wbcom-tab-content woo-document-pro">
	<div class="wbcom-welcome-main-wrapper">
		<a href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/woo-audio-preview-pro/' ); ?>" target="_blank">
			<small><?php esc_html_e( 'Get Pro Version', 'wc-audio-preview' ); ?></small>
			<img src="<?php echo esc_url( plugin_dir_url( dirname( __FILE__ ) ) . 'images/image_pro.png' ); ?>">
		</a>
	</div>
</div>
