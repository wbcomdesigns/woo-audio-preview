<?php
/**
 * Updated FAQ for Audio Preview plugin
 *
 * @package    wc-audio-preview
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wbcom-tab-content">      
<div class="wbcom-faq-adming-setting">
	<div class="wbcom-admin-title-section">
		<h3><?php esc_html_e( 'Frequently Asked Questions', 'wc-audio-preview' ); ?></h3>
	</div>
	<div class="wbcom-faq-admin-settings-block">
		<div id="wbcom-faq-settings-section" class="wbcom-faq-table">
			
			<div class="wbcom-faq-section-row">
				<div class="wbcom-faq-admin-row">
					<button class="wbcom-faq-accordion">
						<?php esc_html_e( '🚀 What is the difference between Free and Pro versions?', 'wc-audio-preview' ); ?>
					</button>
					<div class="wbcom-faq-panel">
						<p><strong><?php esc_html_e( 'Free Version:', 'wc-audio-preview' ); ?></strong></p>
						<ul>
							<li><?php esc_html_e( '• Up to 3 audio previews per product (fixed fields)', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( '• Support for all major audio formats', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( '• CDN and streaming service support', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( '• Basic audio player', 'wc-audio-preview' ); ?></li>
						</ul>
						<p><strong><?php esc_html_e( 'Pro Version:', 'wc-audio-preview' ); ?></strong></p>
						<ul>
							<li><?php esc_html_e( '• Unlimited audio previews per product', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( '• Dynamic add/remove audio files', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( '• Multi-vendor marketplace support (Dokan, WCFM, WC Vendors)', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( '• Custom player themes and colors', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( '• Preview duration control', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( '• Audio watermarking', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( '• Bulk import features', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( '• Priority support', 'wc-audio-preview' ); ?></li>
						</ul>
					</div>
				</div>
			</div>

			<div class="wbcom-faq-section-row">
				<div class="wbcom-faq-admin-row">
					<button class="wbcom-faq-accordion">
						<?php esc_html_e( '📋 How do I add audio previews to my products?', 'wc-audio-preview' ); ?>
					</button>
					<div class="wbcom-faq-panel">
						<p><?php esc_html_e( 'Adding audio previews is simple:', 'wc-audio-preview' ); ?></p>
						<ol>
							<li><?php esc_html_e( 'Edit your WooCommerce product', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( 'Scroll to the "Audio Preview Items" section', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( 'Add up to 3 audio files:', 'wc-audio-preview' ); ?>
								<ul>
									<li><?php esc_html_e( 'Enter a name for each audio preview', 'wc-audio-preview' ); ?></li>
									<li><?php esc_html_e( 'Either upload from Media Library or paste a URL', 'wc-audio-preview' ); ?></li>
									<li><?php esc_html_e( 'You can use CDN links (Google Drive, Dropbox, etc.)', 'wc-audio-preview' ); ?></li>
								</ul>
							</li>
							<li><?php esc_html_e( 'Save your product', 'wc-audio-preview' ); ?></li>
						</ol>
						<p><em><?php esc_html_e( 'Pro tip: Leave fields empty if you need fewer than 3 previews.', 'wc-audio-preview' ); ?></em></p>
					</div>
				</div>
			</div>

			<div class="wbcom-faq-section-row">
				<div class="wbcom-faq-admin-row">
					<button class="wbcom-faq-accordion">
						<?php esc_html_e( '🎵 What audio formats are supported?', 'wc-audio-preview' ); ?>
					</button>
					<div class="wbcom-faq-panel">
						<p><?php esc_html_e( 'The plugin supports all major audio formats:', 'wc-audio-preview' ); ?></p>
						<ul>
							<li><strong>MP3</strong> - <?php esc_html_e( 'Universal compatibility (recommended)', 'wc-audio-preview' ); ?></li>
							<li><strong>WAV</strong> - <?php esc_html_e( 'High quality, larger files', 'wc-audio-preview' ); ?></li>
							<li><strong>OGG</strong> - <?php esc_html_e( 'Open format, good compression (not supported on iOS)', 'wc-audio-preview' ); ?></li>
							<li><strong>M4A</strong> - <?php esc_html_e( 'Apple\'s format, good quality', 'wc-audio-preview' ); ?></li>
							<li><strong>AAC</strong> - <?php esc_html_e( 'Advanced audio coding', 'wc-audio-preview' ); ?></li>
							<li><strong>FLAC</strong> - <?php esc_html_e( 'Lossless compression', 'wc-audio-preview' ); ?></li>
							<li><strong>WMA</strong> - <?php esc_html_e( 'Windows Media Audio', 'wc-audio-preview' ); ?></li>
							<li><strong>WEBM</strong> - <?php esc_html_e( 'Web-optimized format', 'wc-audio-preview' ); ?></li>
						</ul>
						<p><strong><?php esc_html_e( '☁️ CDN & Streaming Services:', 'wc-audio-preview' ); ?></strong></p>
						<ul>
							<li><?php esc_html_e( 'Google Drive', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( 'Dropbox', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( 'SoundCloud', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( 'Amazon S3', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( 'CloudFront', 'wc-audio-preview' ); ?></li>
						</ul>
					</div>
				</div>
			</div>

			<div class="wbcom-faq-section-row">
				<div class="wbcom-faq-admin-row">
					<button class="wbcom-faq-accordion">
						<?php esc_html_e( '🛍️ Does this work with multi-vendor marketplaces?', 'wc-audio-preview' ); ?>
					</button>
					<div class="wbcom-faq-panel">
						<p><?php esc_html_e( 'The Pro version includes full multi-vendor support for:', 'wc-audio-preview' ); ?></p>
						<ul>
							<li><?php esc_html_e( '• Dokan Multivendor Marketplace', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( '• WCFM Marketplace', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( '• WC Vendors', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( '• WC Marketplace', 'wc-audio-preview' ); ?></li>
						</ul>
						<p><?php esc_html_e( 'Vendors can manage their own audio previews from their dashboard, making it perfect for music marketplaces, audiobook stores, and digital audio platforms.', 'wc-audio-preview' ); ?></p>
						<p>
							<a href="https://wbcomdesigns.com/downloads/woo-audio-preview-pro/" target="_blank" class="button button-primary">
								<?php esc_html_e( 'Get Multi-vendor Support', 'wc-audio-preview' ); ?>
							</a>
						</p>
					</div>
				</div>
			</div>

			<div class="wbcom-faq-section-row">
				<div class="wbcom-faq-admin-row">
					<button class="wbcom-faq-accordion">
						<?php esc_html_e( '❓ Why can I only add 3 audio previews?', 'wc-audio-preview' ); ?>
					</button>
					<div class="wbcom-faq-panel">
						<p><?php esc_html_e( 'The free version is limited to 3 audio previews per product to cover most common use cases:', 'wc-audio-preview' ); ?></p>
						<ul>
							<li><?php esc_html_e( 'Main track preview', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( 'Intro/verse preview', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( 'Chorus/highlight preview', 'wc-audio-preview' ); ?></li>
						</ul>
						<p><?php esc_html_e( 'If you need more previews (for albums, audiobooks, or sound packs), the Pro version offers:', 'wc-audio-preview' ); ?></p>
						<ul>
							<li><?php esc_html_e( '✅ Unlimited audio previews', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( '✅ Dynamic add/remove buttons', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( '✅ Bulk import options', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( '✅ Organized playlist display', 'wc-audio-preview' ); ?></li>
						</ul>
					</div>
				</div>
			</div>

			<div class="wbcom-faq-section-row">
				<div class="wbcom-faq-admin-row">
					<button class="wbcom-faq-accordion">
						<?php esc_html_e( '🔒 How can I protect my audio files from downloading?', 'wc-audio-preview' ); ?>
					</button>
					<div class="wbcom-faq-panel">
						<p><strong><?php esc_html_e( 'Free Version Protection:', 'wc-audio-preview' ); ?></strong></p>
						<ul>
							<li><?php esc_html_e( 'Use short preview clips (30-60 seconds)', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( 'Upload lower quality versions for preview', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( 'Use streaming services like SoundCloud', 'wc-audio-preview' ); ?></li>
						</ul>
						<p><strong><?php esc_html_e( 'Pro Version Advanced Protection:', 'wc-audio-preview' ); ?></strong></p>
						<ul>
							<li><?php esc_html_e( '🛡️ Audio watermarking', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( '🛡️ Voice-over protection', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( '🛡️ Time-limited previews', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( '🛡️ Secure streaming options', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( '🛡️ Right-click protection', 'wc-audio-preview' ); ?></li>
						</ul>
					</div>
				</div>
			</div>

			<div class="wbcom-faq-section-row">
				<div class="wbcom-faq-admin-row">
					<button class="wbcom-faq-accordion">
						<?php esc_html_e( '📱 Is the audio player mobile-friendly?', 'wc-audio-preview' ); ?>
					</button>
					<div class="wbcom-faq-panel">
						<p><?php esc_html_e( 'Yes! The audio player is fully responsive and works great on:', 'wc-audio-preview' ); ?></p>
						<ul>
							<li><?php esc_html_e( '✓ iOS devices (iPhone, iPad)', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( '✓ Android phones and tablets', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( '✓ All modern browsers', 'wc-audio-preview' ); ?></li>
						</ul>
						<p><strong><?php esc_html_e( 'Note:', 'wc-audio-preview' ); ?></strong> <?php esc_html_e( 'OGG format is not supported on iOS devices. We recommend using MP3 for maximum compatibility.', 'wc-audio-preview' ); ?></p>
					</div>
				</div>
			</div>

			<div class="wbcom-faq-section-row">
				<div class="wbcom-faq-admin-row">
					<button class="wbcom-faq-accordion">
						<?php esc_html_e( '🎨 Can I customize the player appearance?', 'wc-audio-preview' ); ?>
					</button>
					<div class="wbcom-faq-panel">
						<p><strong><?php esc_html_e( 'Free Version:', 'wc-audio-preview' ); ?></strong></p>
						<p><?php esc_html_e( 'The player uses a neutral design that adapts to your theme colors automatically.', 'wc-audio-preview' ); ?></p>
						
						<p><strong><?php esc_html_e( 'Pro Version Customization:', 'wc-audio-preview' ); ?></strong></p>
						<ul>
							<li><?php esc_html_e( '🎨 Multiple player themes', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( '🎨 Custom color schemes', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( '🎨 Button style options', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( '🎨 Progress bar styles', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( '🎨 Custom CSS support', 'wc-audio-preview' ); ?></li>
						</ul>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
</div>