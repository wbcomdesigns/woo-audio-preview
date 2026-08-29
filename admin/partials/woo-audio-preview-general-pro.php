<?php
/**
 * General (PRO) tab - Free vs Pro comparison for the Audio Preview settings screen.
 *
 * Rendered inside the shared Wbcom_Settings_Page shell.
 *
 * @package woo-audio-preview
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wbcom-tab-content">
	<div class="wbcom-welcome-main-wrapper">

		<?php Wbcom_Settings_Page::card_open( __( 'Audio Preview for WooCommerce', 'woo-audio-preview' ), __( 'Add professional audio previews to your WooCommerce products. Let customers listen before they buy!', 'woo-audio-preview' ) ); ?>

			<div class="wbcom-comparison-section">
				<h3><?php esc_html_e( 'Free vs Pro Features', 'woo-audio-preview' ); ?></h3>

				<table class="wbcom-comparison-table">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Feature', 'woo-audio-preview' ); ?></th>
							<th class="free-column">
								<span class="plan-badge free"><?php esc_html_e( 'FREE', 'woo-audio-preview' ); ?></span>
							</th>
							<th class="pro-column">
								<span class="plan-badge pro"><?php esc_html_e( 'PRO', 'woo-audio-preview' ); ?></span>
							</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?php esc_html_e( 'Number of Audio Previews per Product', 'woo-audio-preview' ); ?></td>
							<td class="free-column"><strong>3</strong> <?php esc_html_e( '(Fixed)', 'woo-audio-preview' ); ?></td>
							<td class="pro-column"><strong><?php esc_html_e( 'Unlimited', 'woo-audio-preview' ); ?></strong></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'Add/Remove Audio Files Dynamically', 'woo-audio-preview' ); ?></td>
							<td class="free-column"><?php esc_html_e( 'No', 'woo-audio-preview' ); ?></td>
							<td class="pro-column"><?php esc_html_e( 'Yes', 'woo-audio-preview' ); ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'Support for External URLs & CDNs', 'woo-audio-preview' ); ?></td>
							<td class="free-column"><?php esc_html_e( 'Yes', 'woo-audio-preview' ); ?></td>
							<td class="pro-column"><?php esc_html_e( 'Yes', 'woo-audio-preview' ); ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'Multi-Vendor Support (Dokan, WCFM, etc.)', 'woo-audio-preview' ); ?></td>
							<td class="free-column"><?php esc_html_e( 'No', 'woo-audio-preview' ); ?></td>
							<td class="pro-column"><?php esc_html_e( 'Yes', 'woo-audio-preview' ); ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'Custom Audio Player Themes', 'woo-audio-preview' ); ?></td>
							<td class="free-column"><?php esc_html_e( 'No', 'woo-audio-preview' ); ?></td>
							<td class="pro-column"><?php esc_html_e( 'Yes', 'woo-audio-preview' ); ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'Preview Duration Control', 'woo-audio-preview' ); ?></td>
							<td class="free-column"><?php esc_html_e( 'No', 'woo-audio-preview' ); ?></td>
							<td class="pro-column"><?php esc_html_e( 'Yes', 'woo-audio-preview' ); ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'Watermark/Voice-over Protection', 'woo-audio-preview' ); ?></td>
							<td class="free-column"><?php esc_html_e( 'No', 'woo-audio-preview' ); ?></td>
							<td class="pro-column"><?php esc_html_e( 'Yes', 'woo-audio-preview' ); ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'Bulk Import Audio Files', 'woo-audio-preview' ); ?></td>
							<td class="free-column"><?php esc_html_e( 'No', 'woo-audio-preview' ); ?></td>
							<td class="pro-column"><?php esc_html_e( 'Yes', 'woo-audio-preview' ); ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'Priority Support', 'woo-audio-preview' ); ?></td>
							<td class="free-column"><?php esc_html_e( 'Community', 'woo-audio-preview' ); ?></td>
							<td class="pro-column"><?php esc_html_e( 'Priority Email', 'woo-audio-preview' ); ?></td>
						</tr>
					</tbody>
				</table>

				<div class="wbcom-cta-section">
					<div class="pro-benefits">
						<h4><?php esc_html_e( 'Why Upgrade to Pro?', 'woo-audio-preview' ); ?></h4>
						<ul>
							<li><?php esc_html_e( 'Perfect for music stores with large catalogs', 'woo-audio-preview' ); ?></li>
							<li><?php esc_html_e( 'Essential for multi-vendor marketplaces', 'woo-audio-preview' ); ?></li>
							<li><?php esc_html_e( 'Advanced protection for your audio content', 'woo-audio-preview' ); ?></li>
							<li><?php esc_html_e( 'Save time with bulk operations', 'woo-audio-preview' ); ?></li>
						</ul>
					</div>

					<div class="upgrade-box">
						<h4><?php esc_html_e( 'Get Pro Version', 'woo-audio-preview' ); ?></h4>
						<p class="price-tag"><?php esc_html_e( 'Starting at', 'woo-audio-preview' ); ?> <strong>$49</strong></p>
						<a href="https://wbcomdesigns.com/downloads/woo-audio-preview-pro/" target="_blank" class="button button-hero button-primary">
							<?php esc_html_e( 'Upgrade to Pro Now', 'woo-audio-preview' ); ?>
						</a>
						<p class="guarantee"><?php esc_html_e( '30-day money-back guarantee', 'woo-audio-preview' ); ?></p>
					</div>
				</div>
			</div>

		<?php Wbcom_Settings_Page::card_close(); ?>

	</div>
</div>
