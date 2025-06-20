<!-- admin/partials/woo-audio-preview-welcome-page.php -->
<?php
/**
 * This file displays the Free vs Pro comparison
 *
 * @package wc-audio-preview
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wbcom-tab-content">
	<div class="wbcom-welcome-main-wrapper">
		<div class="wbcom-welcome-head">
			<h2><?php esc_html_e( '🎵 Audio Preview for WooCommerce', 'wc-audio-preview' ); ?></h2>
			<p class="wbcom-welcome-description">
				<?php esc_html_e( 'Add professional audio previews to your WooCommerce products. Let customers listen before they buy!', 'wc-audio-preview' ); ?>
			</p>
		</div>

		<div class="wbcom-comparison-section">
			<h3><?php esc_html_e( '✨ Free vs Pro Features', 'wc-audio-preview' ); ?></h3>
			
			<table class="wbcom-comparison-table">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Feature', 'wc-audio-preview' ); ?></th>
						<th class="free-column">
							<span class="plan-badge free"><?php esc_html_e( 'FREE', 'wc-audio-preview' ); ?></span>
						</th>
						<th class="pro-column">
							<span class="plan-badge pro"><?php esc_html_e( 'PRO', 'wc-audio-preview' ); ?></span>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php esc_html_e( 'Number of Audio Previews per Product', 'wc-audio-preview' ); ?></td>
						<td class="free-column"><strong>3</strong> <?php esc_html_e( '(Fixed)', 'wc-audio-preview' ); ?></td>
						<td class="pro-column"><strong><?php esc_html_e( 'Unlimited', 'wc-audio-preview' ); ?></strong> ✨</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Add/Remove Audio Files Dynamically', 'wc-audio-preview' ); ?></td>
						<td class="free-column">❌</td>
						<td class="pro-column">✅</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Support for External URLs & CDNs', 'wc-audio-preview' ); ?></td>
						<td class="free-column">✅</td>
						<td class="pro-column">✅</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Multi-Vendor Support (Dokan, WCFM, etc.)', 'wc-audio-preview' ); ?></td>
						<td class="free-column">❌</td>
						<td class="pro-column">✅</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Custom Audio Player Themes', 'wc-audio-preview' ); ?></td>
						<td class="free-column">❌</td>
						<td class="pro-column">✅</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Preview Duration Control', 'wc-audio-preview' ); ?></td>
						<td class="free-column">❌</td>
						<td class="pro-column">✅</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Watermark/Voice-over Protection', 'wc-audio-preview' ); ?></td>
						<td class="free-column">❌</td>
						<td class="pro-column">✅</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Bulk Import Audio Files', 'wc-audio-preview' ); ?></td>
						<td class="free-column">❌</td>
						<td class="pro-column">✅</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Priority Support', 'wc-audio-preview' ); ?></td>
						<td class="free-column"><?php esc_html_e( 'Community', 'wc-audio-preview' ); ?></td>
						<td class="pro-column"><?php esc_html_e( 'Priority Email', 'wc-audio-preview' ); ?></td>
					</tr>
				</tbody>
			</table>

			<div class="wbcom-cta-section">
				<div class="pro-benefits">
					<h4><?php esc_html_e( '🚀 Why Upgrade to Pro?', 'wc-audio-preview' ); ?></h4>
					<ul>
						<li><?php esc_html_e( 'Perfect for music stores with large catalogs', 'wc-audio-preview' ); ?></li>
						<li><?php esc_html_e( 'Essential for multi-vendor marketplaces', 'wc-audio-preview' ); ?></li>
						<li><?php esc_html_e( 'Advanced protection for your audio content', 'wc-audio-preview' ); ?></li>
						<li><?php esc_html_e( 'Save time with bulk operations', 'wc-audio-preview' ); ?></li>
					</ul>
				</div>
				
				<div class="upgrade-box">
					<h4><?php esc_html_e( '💎 Get Pro Version', 'wc-audio-preview' ); ?></h4>
					<p class="price-tag"><?php esc_html_e( 'Starting at', 'wc-audio-preview' ); ?> <strong>$49</strong></p>
					<a href="https://wbcomdesigns.com/downloads/woo-audio-preview-pro/" target="_blank" class="button button-hero button-primary">
						<?php esc_html_e( 'Upgrade to Pro Now', 'wc-audio-preview' ); ?>
					</a>
					<p class="guarantee"><?php esc_html_e( '30-day money-back guarantee', 'wc-audio-preview' ); ?></p>
				</div>
			</div>
		</div>


	</div>
</div>
