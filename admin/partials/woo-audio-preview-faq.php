<?php
/**
 * Updated FAQ for Audio Preview plugin
 *
 * @package    woo-audio-preview
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wbcom-tab-content">      
<div class="wbcom-faq-adming-setting">
	<div class="wbcom-admin-title-section">
		<h3><?php esc_html_e( 'Frequently Asked Questions', 'woo-audio-preview' ); ?></h3>
	</div>
	<div class="wbcom-faq-admin-settings-block">
		<div id="wbcom-faq-settings-section" class="wbcom-faq-table">
			
			<div class="wbcom-faq-section-row">
				<div class="wbcom-faq-admin-row">
					<button class="wbcom-faq-accordion">
						<?php esc_html_e( '🚀 What is the difference between Free and Pro versions?', 'woo-audio-preview' ); ?>
					</button>
					<div class="wbcom-faq-panel">
						<p><strong><?php esc_html_e( 'Free Version:', 'woo-audio-preview' ); ?></strong></p>
						<ul>
							<li><?php esc_html_e( '• Up to 3 audio previews per product (fixed fields)', 'woo-audio-preview' ); ?></li>
							<li><?php esc_html_e( '• Support for all major audio formats', 'woo-audio-preview' ); ?></li>
							<li><?php esc_html_e( '• CDN and streaming service support', 'woo-audio-preview' ); ?></li>
							<li><?php esc_html_e( '• Basic audio player', 'woo-audio-preview' ); ?></li>
						</ul>
						<p><strong><?php esc_html_e( 'Pro Version:', 'woo-audio-preview' ); ?></strong></p>
						<ul>
							<li><?php esc_html_e( '• Unlimited audio previews per product', 'woo-audio-preview' ); ?></li>
							<li><?php esc_html_e( '• Dynamic add/remove audio files', 'woo-audio-preview' ); ?></li>
							<li><?php esc_html_e( '• Multi-vendor marketplace support (Dokan, WCFM, WC Vendors)', 'woo-audio-preview' ); ?></li>
							<li><?php esc_html_e( '• Custom player themes and colors', 'woo-audio-preview' ); ?></li>
							<li><?php esc_html_e( '• Preview duration control', 'woo-audio-preview' ); ?></li>
							<li><?php esc_html_e( '• Audio watermarking', 'woo-audio-preview' ); ?></li>
							<li><?php esc_html_e( '• Bulk import features', 'woo-audio-preview' ); ?></li>
							<li><?php esc_html_e( '• Priority support', 'woo-audio-preview' ); ?></li>
						</ul>
					</div>
				</div>
			</div>

			<div class="wbcom-faq-section-row">
				<div class="wbcom-faq-admin-row">
					<button class="wbcom-faq-accordion">
						<?php esc_html_e( '📋 How do I add audio previews to my products?', 'woo-audio-preview' ); ?>
					</button>
					<div class="wbcom-faq-panel">
						<p><?php esc_html_e( 'Adding audio previews is simple:', 'woo-audio-preview' ); ?></p>
						<ol>
							<li><?php esc_html_e( 'Edit your WooCommerce product', 'woo-audio-preview' ); ?></li>
							<li><?php esc_html_e( 'Scroll to the "Audio Preview Items" section', 'woo-audio-preview' ); ?></li>
							<li><?php esc_html_e( 'Add up to 3 audio files:', 'woo-audio-preview' ); ?>
								<ul>
									<li><?php esc_html_e( 'Enter a name for each audio preview', 'woo-audio-preview' ); ?></li>
									<li><?php esc_html_e( 'Either upload from Media Library or paste a URL', 'woo-audio-preview' ); ?></li>
									<li><?php esc_html_e( 'You can use CDN links (Google Drive, Dropbox, etc.)', 'woo-audio-preview' ); ?></li>
								</ul>
							</li>
							<li><?php esc_html_e( 'Save your product', 'woo-audio-preview' ); ?></li>
						</ol>
						<p><em><?php esc_html_e( 'Pro tip: Leave fields empty if you need fewer than 3 previews.', 'woo-audio-preview' ); ?></em></p>
					</div>
				</div>
			</div>

			<div class="wbcom-faq-section-row">
				<div class="wbcom-faq-admin-row">
					<button class="wbcom-faq-accordion">
						<?php esc_html_e( '🎵 What audio formats are supported?', 'woo-audio-preview' ); ?>
					</button>
					<div class="wbcom-faq-panel">
						<p><?php esc_html_e( 'The plugin supports all major audio formats:', 'woo-audio-preview' ); ?></p>
						<ul>
							<li><strong>MP3</strong> - <?php esc_html_e( 'Universal compatibility (recommended)', 'woo-audio-preview' ); ?></li>
							<li><strong>WAV</strong> - <?php esc_html_e( 'High quality, larger files', 'woo-audio-preview' ); ?></li>
							<li><strong>OGG</strong> - <?php esc_html_e( 'Open format, good compression (not supported on iOS)', 'woo-audio-preview' ); ?></li>
							<li><strong>M4A</strong> - <?php esc_html_e( 'Apple\'s format, good quality', 'woo-audio-preview' ); ?></li>
							<li><strong>AAC</strong> - <?php esc_html_e( 'Advanced audio coding', 'woo-audio-preview' ); ?></li>
							<li><strong>FLAC</strong> - <?php esc_html_e( 'Lossless compression', 'woo-audio-preview' ); ?></li>
							<li><strong>WMA</strong> - <?php esc_html_e( 'Windows Media Audio', 'woo-audio-preview' ); ?></li>
							<li><strong>WEBM</strong> - <?php esc_html_e( 'Web-optimized format', 'woo-audio-preview' ); ?></li>
						</ul>
						<p><strong><?php esc_html_e( '☁️ CDN & Streaming Services:', 'woo-audio-preview' ); ?></strong></p>
						<ul>
							<li><?php esc_html_e( 'Google Drive', 'woo-audio-preview' ); ?></li>
							<li><?php esc_html_e( 'Dropbox', 'woo-audio-preview' ); ?></li>
							<li><?php esc_html_e( 'SoundCloud', 'woo-audio-preview' ); ?></li>
							<li><?php esc_html_e( 'Amazon S3', 'woo-audio-preview' ); ?></li>
							<li><?php esc_html_e( 'CloudFront', 'woo-audio-preview' ); ?></li>
						</ul>
					</div>
				</div>
			</div>

			<div class="wbcom-faq-section-row">
				<div class="wbcom-faq-admin-row">
					<button class="wbcom-faq-accordion">
						<?php esc_html_e( '🛍️ Does this work with multi-vendor marketplaces?', 'woo-audio-preview' ); ?>
					</button>
					<div class="wbcom-faq-panel">
						<p><?php esc_html_e( 'The Pro version includes full multi-vendor support for:', 'woo-audio-preview' ); ?></p>
						<ul>
							<li><?php esc_html_e( '• Dokan Multivendor Marketplace', 'woo-audio-preview' ); ?></li>
							<li><?php esc_html_e( '• WCFM Marketplace', 'woo-audio-preview' ); ?></li>
							<li><?php esc_html_e( '• WC Vendors', 'woo-audio-preview' ); ?></li>
							<li><?php esc_html_e( '• WC Marketplace', 'woo-audio-preview' ); ?></li>
						</ul>
						<p><?php esc_html_e( 'Vendors can manage their own audio previews from their dashboard, making it perfect for music marketplaces, audiobook stores, and digital audio platforms.', 'woo-audio-preview' ); ?></p>
						<p>
							<a href="https://wbcomdesigns.com/downloads/woo-audio-preview-pro/" target="_blank" class="button button-primary">
								<?php esc_html_e( 'Get Multi-vendor Support', 'woo-audio-preview' ); ?>
							</a>
						</p>
					</div>
				</div>
			</div>

			<div class="wbcom-faq-section-row">
				<div class="wbcom-faq-admin-row">
					<button class="wbcom-faq-accordion">
						<?php esc_html_e( '❓ Why can I only add 3 audio previews?', 'woo-audio-preview' ); ?>
					</button>
					<div class="wbcom-faq-panel">
						<p><?php esc_html_e( 'The free version is limited to 3 audio previews per product to cover most common use cases:', 'woo-audio-preview' ); ?></p>
						<ul>
							<li><?php esc_html_e( 'Main track preview', 'woo-audio-preview' ); ?></li>
							<li><?php esc_html_e( 'Intro/verse preview', 'woo-audio-preview' ); ?></li>
							<li><?php esc_html_e( 'Chorus/highlight preview', 'woo-audio-preview' ); ?></li>
						</ul>
						<p><?php esc_html_e( 'If you need more previews (for albums, audiobooks, or sound packs), the Pro version offers:', 'woo-audio-preview' ); ?></p>
						<ul>
							<li><?php esc_html_e( '✅ Unlimited audio previews', 'woo-audio-preview' ); ?></li>
							<li><?php esc_html_e( '✅ Dynamic add/remove buttons', 'woo-audio-preview' ); ?></li>
							<li><?php esc_html_e( '✅ Bulk import options', 'woo-audio-preview' ); ?></li>
							<li><?php esc_html_e( '✅ Organized playlist display', 'woo-audio-preview' ); ?></li>
						</ul>
					</div>
				</div>
			</div>

			<div class="wbcom-faq-section-row">
				<div class="wbcom-faq-admin-row">
					<button class="wbcom-faq-accordion">
						<?php esc_html_e( '🔒 How can I protect my audio files from downloading?', 'woo-audio-preview' ); ?>
					</button>
					<div class="wbcom-faq-panel">
						<p><strong><?php esc_html_e( 'Free Version Protection:', 'woo-audio-preview' ); ?></strong></p>
						<ul>
							<li><?php esc_html_e( 'Use short preview clips (30-60 seconds)', 'woo-audio-preview' ); ?></li>
							<li><?php esc_html_e( 'Upload lower quality versions for preview', 'woo-audio-preview' ); ?></li>
							<li><?php esc_html_e( 'Use streaming services like SoundCloud', 'woo-audio-preview' ); ?></li>
						</ul>
						<p><strong><?php esc_html_e( 'Pro Version Advanced Protection:', 'woo-audio-preview' ); ?></strong></p>
						<ul>
							<li><?php esc_html_e( '🛡️ Audio watermarking', 'woo-audio-preview' ); ?></li>
							<li><?php esc_html_e( '🛡️ Voice-over protection', 'woo-audio-preview' ); ?></li>
							<li><?php esc_html_e( '🛡️ Time-limited previews', 'woo-audio-preview' ); ?></li>
							<li><?php esc_html_e( '🛡️ Secure streaming options', 'woo-audio-preview' ); ?></li>
							<li><?php esc_html_e( '🛡️ Right-click protection', 'woo-audio-preview' ); ?></li>
						</ul>
					</div>
				</div>
			</div>

			<div class="wbcom-faq-section-row">
				<div class="wbcom-faq-admin-row">
					<button class="wbcom-faq-accordion">
						<?php esc_html_e( '📱 Is the audio player mobile-friendly?', 'woo-audio-preview' ); ?>
					</button>
					<div class="wbcom-faq-panel">
						<p><?php esc_html_e( 'Yes! The audio player is fully responsive and works great on:', 'woo-audio-preview' ); ?></p>
						<ul>
							<li><?php esc_html_e( '✓ iOS devices (iPhone, iPad)', 'woo-audio-preview' ); ?></li>
							<li><?php esc_html_e( '✓ Android phones and tablets', 'woo-audio-preview' ); ?></li>
							<li><?php esc_html_e( '✓ All modern browsers', 'woo-audio-preview' ); ?></li>
						</ul>
						<p><strong><?php esc_html_e( 'Note:', 'woo-audio-preview' ); ?></strong> <?php esc_html_e( 'OGG format is not supported on iOS devices. We recommend using MP3 for maximum compatibility.', 'woo-audio-preview' ); ?></p>
					</div>
				</div>
			</div>

			<div class="wbcom-faq-section-row">
				<div class="wbcom-faq-admin-row">
					<button class="wbcom-faq-accordion">
						<?php esc_html_e( '🎨 Can I customize the player appearance?', 'woo-audio-preview' ); ?>
					</button>
					<div class="wbcom-faq-panel">
						<p><strong><?php esc_html_e( 'Free Version:', 'woo-audio-preview' ); ?></strong></p>
						<p><?php esc_html_e( 'The player uses a neutral design that adapts to your theme colors automatically.', 'woo-audio-preview' ); ?></p>
						
						<p><strong><?php esc_html_e( 'Pro Version Customization:', 'woo-audio-preview' ); ?></strong></p>
						<ul>
							<li><?php esc_html_e( '🎨 Multiple player themes', 'woo-audio-preview' ); ?></li>
							<li><?php esc_html_e( '🎨 Custom color schemes', 'woo-audio-preview' ); ?></li>
							<li><?php esc_html_e( '🎨 Button style options', 'woo-audio-preview' ); ?></li>
							<li><?php esc_html_e( '🎨 Progress bar styles', 'woo-audio-preview' ); ?></li>
							<li><?php esc_html_e( '🎨 Custom CSS support', 'woo-audio-preview' ); ?></li>
						</ul>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
</div>