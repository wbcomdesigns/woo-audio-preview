<?php
/**
 * Wbcom license page template.
 *
 * @package Wc_Audio_Preview
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap">
	<div class="wbcom-bb-plugins-offer-wrapper">
		<div id="wb_admin_logo">
		</div>
	</div>
	<div class="wbcom-wrap wbcom-plugin-wrapper">
		<div class="wbcom_admin_header-wrapper">
			<div id="wb_admin_plugin_name">
				<?php esc_html_e( 'Audio Preview for WooCommerce', 'wc-audio-preview' ); ?>
				<span>
			<?php
			/* translators: %s: Plugin version number. */
			printf( esc_html__( 'Version %s', 'wc-audio-preview' ), esc_html( WCAP_TEXT_VERSION ) );
			?>
			</span>
			</div>
			<?php echo do_shortcode( '[wbcom_admin_setting_header]' ); ?>
		</div>
		<div class="wbcom-all-addons-plugins-wrap">
		<h4 class="wbcom-support-section"><?php esc_html_e( 'Plugin License', 'wc-audio-preview' ); ?></h4>
		<div class="wb-plugins-license-tables-wrap">
			<div class="wbcom-license-support-wrapp">
			<table class="form-table wb-license-form-table desktop-license-headings">
				<thead>
					<tr>
						<th class="wb-product-th"><?php esc_html_e( 'Product', 'wc-audio-preview' ); ?></th>
						<th class="wb-version-th"><?php esc_html_e( 'Version', 'wc-audio-preview' ); ?></th>
						<th class="wb-key-th"><?php esc_html_e( 'Key', 'wc-audio-preview' ); ?></th>
						<th class="wb-status-th"><?php esc_html_e( 'Status', 'wc-audio-preview' ); ?></th>
						<th class="wb-action-th"><?php esc_html_e( 'Action', 'wc-audio-preview' ); ?></th>
					</tr>
				</thead>
			</table>
			<?php do_action( 'wbcom_add_plugin_license_code' ); ?>
			<table class="form-table wb-license-form-table">
				<tfoot>
					<tr>
						<th class="wb-product-th"><?php esc_html_e( 'Product', 'wc-audio-preview' ); ?></th>
						<th class="wb-version-th"><?php esc_html_e( 'Version', 'wc-audio-preview' ); ?></th>
						<th class="wb-key-th"><?php esc_html_e( 'Key', 'wc-audio-preview' ); ?></th>
						<th class="wb-status-th"><?php esc_html_e( 'Status', 'wc-audio-preview' ); ?></th>
						<th class="wb-action-th"><?php esc_html_e( 'Action', 'wc-audio-preview' ); ?></th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
	</div>
	</div><!-- .wbcom-wrap -->
</div><!-- .wrap -->
