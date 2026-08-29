<?php
/**
 * General (PRO) tab - Free vs Pro comparison for the Audio Preview settings screen.
 *
 * Rendered inside the shared Wbcom_Settings_Page shell using its flat compare
 * table and CTA components, so nothing is a box inside a box.
 *
 * @package woo-audio-preview
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$wcap_rows = array(
	array( __( 'Number of audio previews per product', 'woo-audio-preview' ), __( '3 (fixed)', 'woo-audio-preview' ), __( 'Unlimited', 'woo-audio-preview' ) ),
	array( __( 'Add / remove audio files dynamically', 'woo-audio-preview' ), __( 'No', 'woo-audio-preview' ), __( 'Yes', 'woo-audio-preview' ) ),
	array( __( 'Support for external URLs & CDNs', 'woo-audio-preview' ), __( 'Yes', 'woo-audio-preview' ), __( 'Yes', 'woo-audio-preview' ) ),
	array( __( 'Multi-vendor support (Dokan, WCFM, etc.)', 'woo-audio-preview' ), __( 'No', 'woo-audio-preview' ), __( 'Yes', 'woo-audio-preview' ) ),
	array( __( 'Custom audio player themes', 'woo-audio-preview' ), __( 'No', 'woo-audio-preview' ), __( 'Yes', 'woo-audio-preview' ) ),
	array( __( 'Preview duration control', 'woo-audio-preview' ), __( 'No', 'woo-audio-preview' ), __( 'Yes', 'woo-audio-preview' ) ),
	array( __( 'Watermark / voice-over protection', 'woo-audio-preview' ), __( 'No', 'woo-audio-preview' ), __( 'Yes', 'woo-audio-preview' ) ),
	array( __( 'Bulk import audio files', 'woo-audio-preview' ), __( 'No', 'woo-audio-preview' ), __( 'Yes', 'woo-audio-preview' ) ),
	array( __( 'Priority support', 'woo-audio-preview' ), __( 'Community', 'woo-audio-preview' ), __( 'Priority email', 'woo-audio-preview' ) ),
);
?>
<div class="wbcom-tab-content">

	<?php Wbcom_Settings_Page::card_open( __( 'Free vs Pro', 'woo-audio-preview' ), __( 'Everything the free plugin does, plus what Pro adds on top.', 'woo-audio-preview' ) ); ?>
		<table class="wbcom-compare">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Feature', 'woo-audio-preview' ); ?></th>
					<th class="wbcom-compare__opt"><?php esc_html_e( 'Free', 'woo-audio-preview' ); ?></th>
					<th class="wbcom-compare__opt"><?php esc_html_e( 'Pro', 'woo-audio-preview' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $wcap_rows as $wcap_row ) : ?>
					<tr>
						<td><?php echo esc_html( $wcap_row[0] ); ?></td>
						<td class="wbcom-compare__opt"><?php echo esc_html( $wcap_row[1] ); ?></td>
						<td class="wbcom-compare__opt wbcom-compare__opt--pro"><?php echo esc_html( $wcap_row[2] ); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php Wbcom_Settings_Page::card_close(); ?>

	<?php Wbcom_Settings_Page::card_open( __( 'Upgrade to Pro', 'woo-audio-preview' ) ); ?>
		<div class="wbcom-cta">
			<ul class="wbcom-cta__benefits">
				<li><?php esc_html_e( 'Perfect for music stores with large catalogs', 'woo-audio-preview' ); ?></li>
				<li><?php esc_html_e( 'Essential for multi-vendor marketplaces', 'woo-audio-preview' ); ?></li>
				<li><?php esc_html_e( 'Advanced protection for your audio content', 'woo-audio-preview' ); ?></li>
				<li><?php esc_html_e( 'Save time with bulk operations', 'woo-audio-preview' ); ?></li>
			</ul>
			<div class="wbcom-cta__action">
				<p class="wbcom-cta__price"><?php esc_html_e( 'Starting at', 'woo-audio-preview' ); ?><strong>$49</strong></p>
				<a href="https://wbcomdesigns.com/downloads/woo-audio-preview-pro/" target="_blank" rel="noopener noreferrer" class="wbcom-btn wbcom-btn--primary">
					<?php esc_html_e( 'Upgrade to Pro Now', 'woo-audio-preview' ); ?>
				</a>
				<p class="wbcom-cta__guarantee"><?php esc_html_e( '30-day money-back guarantee', 'woo-audio-preview' ); ?></p>
			</div>
		</div>
	<?php Wbcom_Settings_Page::card_close(); ?>

</div>
