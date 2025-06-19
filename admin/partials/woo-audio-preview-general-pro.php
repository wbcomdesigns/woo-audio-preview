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

<style>
.wbcom-comparison-table {
	width: 100%;
	border-collapse: collapse;
	margin: 20px 0;
	background: #fff;
	border: 1px solid rgba(0, 0, 0, 0.08);
	border-radius: 8px;
	overflow: hidden;
}

.wbcom-comparison-table th,
.wbcom-comparison-table td {
	padding: 15px;
	text-align: left;
	border-bottom: 1px solid #e1e1e1;
}

.wbcom-comparison-table th {
	background: #f8f9fa;
	font-weight: 600;
	color: inherit;
}

.plan-badge {
	display: inline-block;
	padding: 5px 15px;
	border-radius: 12px;
	font-size: 12px;
	font-weight: bold;
	text-transform: uppercase;
	letter-spacing: 0.5px;
}

.plan-badge.free {
	background: rgba(0, 0, 0, 0.08);
	color: inherit;
	opacity: 0.7;
}

.plan-badge.pro {
	background: rgba(0, 0, 0, 0.9);
	color: white;
}

.free-column {
	text-align: center;
	color: #666;
}

.pro-column {
	text-align: center;
	color: inherit;
	font-weight: 500;
}

.wbcom-cta-section {
	display: grid;
	grid-template-columns: 1fr 1fr;
	gap: 30px;
	margin-top: 40px;
}

.pro-benefits {
	background: rgba(0, 0, 0, 0.02);
	padding: 25px;
	border-radius: 8px;
	border: 1px solid rgba(0, 0, 0, 0.08);
}

.pro-benefits ul {
	list-style: none;
	padding: 0;
}

.pro-benefits li {
	padding: 8px 0;
	padding-left: 25px;
	position: relative;
	color: inherit;
}

.pro-benefits li:before {
	content: "✓";
	position: absolute;
	left: 0;
	color: inherit;
	font-weight: bold;
	opacity: 0.7;
}

.upgrade-box {
	background: currentColor;
	color: white;
	padding: 30px;
	border-radius: 8px;
	text-align: center;
	position: relative;
}

.upgrade-box * {
	position: relative;
	z-index: 1;
}

.upgrade-box h4 {
	color: white;
	margin-top: 0;
	mix-blend-mode: difference;
}

.upgrade-box p {
	color: white;
	mix-blend-mode: difference;
}

.price-tag {
	font-size: 18px;
	margin: 15px 0;
}

.price-tag strong {
	font-size: 36px;
}

.button-hero {
	padding: 15px 30px !important;
	font-size: 16px !important;
	background: white !important;
	color: #333 !important;
	border: none !important;
	box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.button-hero:hover {
	transform: translateY(-2px);
	box-shadow: 0 4px 8px rgba(0,0,0,0.3);
}

.guarantee {
	font-size: 12px;
	opacity: 0.9;
	margin-top: 10px;
}

.support-cards {
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
	gap: 20px;
	margin-top: 20px;
}

.support-card {
	background: rgba(0, 0, 0, 0.02);
	padding: 20px;
	border-radius: 8px;
	text-align: center;
	border: 1px solid rgba(0, 0, 0, 0.08);
}

.support-card .dashicons {
	font-size: 40px;
	width: 40px;
	height: 40px;
	color: inherit;
	opacity: 0.6;
}

.support-card h4 {
	margin: 10px 0;
	color: inherit;
}

.support-card .button {
	background: transparent;
	border: 1px solid currentColor;
	color: inherit;
}

.support-card .button:hover {
	background: currentColor;
	color: white;
}

@media (max-width: 768px) {
	.wbcom-cta-section {
		grid-template-columns: 1fr;
	}
	
	.wbcom-comparison-table {
		font-size: 14px;
	}
	
	.wbcom-comparison-table th,
	.wbcom-comparison-table td {
		padding: 10px;
	}
}
</style>