<?php
/**
 * Welcome/Getting Started page for Audio Preview plugin
 *
 * @package woo-audio-preview
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wbcom-tab-content">
	<div class="wbcom-welcome-main-wrapper">
		<div class="wbcom-welcome-header">
			<h1><?php esc_html_e( '🎵 Welcome to Audio Preview for WooCommerce', 'woo-audio-preview' ); ?></h1>
			<p class="wbcom-welcome-subtitle">
				<?php esc_html_e( 'Thank you for installing Audio Preview! Follow these simple steps to start adding audio previews to your products.', 'woo-audio-preview' ); ?>
			</p>
		</div>

		<!-- Quick Start Guide -->
		<div class="wbcom-quick-start">
			<h2><?php esc_html_e( '🚀 Quick Start Guide', 'woo-audio-preview' ); ?></h2>
			
			<div class="wbcom-steps">
				<div class="wbcom-step">
					<div class="step-number">1</div>
					<div class="step-content">
						<h3><?php esc_html_e( 'Edit a Product', 'woo-audio-preview' ); ?></h3>
						<p><?php esc_html_e( 'Go to WooCommerce → Products and edit any product where you want to add audio previews.', 'woo-audio-preview' ); ?></p>
					</div>
				</div>

				<div class="wbcom-step">
					<div class="step-number">2</div>
					<div class="step-content">
						<h3><?php esc_html_e( 'Find Audio Preview Section', 'woo-audio-preview' ); ?></h3>
						<p><?php esc_html_e( 'Scroll down to find the "Audio Preview Items" meta box below the product description.', 'woo-audio-preview' ); ?></p>
					</div>
				</div>

				<div class="wbcom-step">
					<div class="step-number">3</div>
					<div class="step-content">
						<h3><?php esc_html_e( 'Add Your Audio Files', 'woo-audio-preview' ); ?></h3>
						<p><?php esc_html_e( 'Add up to 3 audio previews by entering a name and URL, or uploading from Media Library.', 'woo-audio-preview' ); ?></p>
					</div>
				</div>

				<div class="wbcom-step">
					<div class="step-number">4</div>
					<div class="step-content">
						<h3><?php esc_html_e( 'Save & Preview', 'woo-audio-preview' ); ?></h3>
						<p><?php esc_html_e( 'Save your product and view it on the frontend. Your audio previews will appear before the Add to Cart button!', 'woo-audio-preview' ); ?></p>
					</div>
				</div>
			</div>
		</div>

		<!-- Key Features -->
		<div class="wbcom-key-features">
			<h2><?php esc_html_e( '✨ Key Features', 'woo-audio-preview' ); ?></h2>
			
			<div class="wbcom-features-grid">
				<div class="wbcom-feature">
					<div class="feature-icon">🎵</div>
					<h4><?php esc_html_e( 'Multiple Formats', 'woo-audio-preview' ); ?></h4>
					<p><?php esc_html_e( 'Supports MP3, WAV, OGG, M4A, AAC, FLAC, WMA, and WEBM audio formats.', 'woo-audio-preview' ); ?></p>
				</div>

				<div class="wbcom-feature">
					<div class="feature-icon">☁️</div>
					<h4><?php esc_html_e( 'CDN Support', 'woo-audio-preview' ); ?></h4>
					<p><?php esc_html_e( 'Use audio files from Google Drive, Dropbox, SoundCloud, Amazon S3, and more.', 'woo-audio-preview' ); ?></p>
				</div>

				<div class="wbcom-feature">
					<div class="feature-icon">📱</div>
					<h4><?php esc_html_e( 'Mobile Friendly', 'woo-audio-preview' ); ?></h4>
					<p><?php esc_html_e( 'Responsive audio player works perfectly on all devices and screen sizes.', 'woo-audio-preview' ); ?></p>
				</div>

				<div class="wbcom-feature">
					<div class="feature-icon">🎨</div>
					<h4><?php esc_html_e( 'Theme Compatible', 'woo-audio-preview' ); ?></h4>
					<p><?php esc_html_e( 'Neutral design automatically adapts to your theme colors and style.', 'woo-audio-preview' ); ?></p>
				</div>
			</div>
		</div>

		<!-- Tips & Best Practices -->
		<div class="wbcom-tips-section">
			<h2><?php esc_html_e( '💡 Tips & Best Practices', 'woo-audio-preview' ); ?></h2>
			
			<div class="wbcom-tips-grid">
				<div class="wbcom-tip">
					<h4><?php esc_html_e( '🎯 Optimal Preview Length', 'woo-audio-preview' ); ?></h4>
					<p><?php esc_html_e( 'Keep previews between 30-60 seconds. Long enough to showcase quality, short enough to encourage purchase.', 'woo-audio-preview' ); ?></p>
				</div>

				<div class="wbcom-tip">
					<h4><?php esc_html_e( '🔒 Protect Your Content', 'woo-audio-preview' ); ?></h4>
					<p><?php esc_html_e( 'Use lower quality versions (128kbps) for previews and save high quality files for customers only.', 'woo-audio-preview' ); ?></p>
				</div>

				<div class="wbcom-tip">
					<h4><?php esc_html_e( '📝 Descriptive Names', 'woo-audio-preview' ); ?></h4>
					<p><?php esc_html_e( 'Use clear names like "Intro Preview", "Chorus Sample", or "Chapter 1 Excerpt" to guide customers.', 'woo-audio-preview' ); ?></p>
				</div>

				<div class="wbcom-tip">
					<h4><?php esc_html_e( '🌐 CDN Benefits', 'woo-audio-preview' ); ?></h4>
					<p><?php esc_html_e( 'Host large audio files on CDN services to improve page load times and reduce server bandwidth.', 'woo-audio-preview' ); ?></p>
				</div>
			</div>
		</div>

		<!-- Support Resources -->
		<div class="wbcom-support-resources">
			<h2><?php esc_html_e( '🆘 Need Help?', 'woo-audio-preview' ); ?></h2>
			
			<div class="wbcom-resources-grid">
				<a href="?page=woo-audio-preview-settings&tab=woo-audio-preview-faq" class="wbcom-resource-card">
					<div class="resource-icon">❓</div>
					<h4><?php esc_html_e( 'Read FAQ', 'woo-audio-preview' ); ?></h4>
					<p><?php esc_html_e( 'Find answers to common questions', 'woo-audio-preview' ); ?></p>
				</a>

				<a href="https://docs.wbcomdesigns.com/doc_category/woo-audio-preview/" target="_blank" class="wbcom-resource-card">
					<div class="resource-icon">📖</div>
					<h4><?php esc_html_e( 'Documentation', 'woo-audio-preview' ); ?></h4>
					<p><?php esc_html_e( 'Detailed guides and tutorials', 'woo-audio-preview' ); ?></p>
				</a>

				<a href="https://wordpress.org/support/plugin/woo-audio-preview/" target="_blank" class="wbcom-resource-card">
					<div class="resource-icon">💬</div>
					<h4><?php esc_html_e( 'Community Forum', 'woo-audio-preview' ); ?></h4>
					<p><?php esc_html_e( 'Get help from the community', 'woo-audio-preview' ); ?></p>
				</a>

				<a href="https://wbcomdesigns.com/support/" target="_blank" class="wbcom-resource-card">
					<div class="resource-icon">🎧</div>
					<h4><?php esc_html_e( 'Premium Support', 'woo-audio-preview' ); ?></h4>
					<p><?php esc_html_e( 'Priority support for Pro users', 'woo-audio-preview' ); ?></p>
				</a>
			</div>
		</div>

		<!-- Pro Upgrade Prompt -->
		<div class="wbcom-upgrade-prompt">
			<div class="upgrade-content">
				<h3><?php esc_html_e( '🚀 Need More Features?', 'woo-audio-preview' ); ?></h3>
				<p><?php esc_html_e( 'Upgrade to Pro for unlimited audio previews, multi-vendor support, advanced protection, and more!', 'woo-audio-preview' ); ?></p>
			</div>
			<div class="upgrade-action">
				<a href="?page=woo-audio-preview-settings&tab=woo-audio-preview-pro" class="button button-primary">
					<?php esc_html_e( 'View Pro Features', 'woo-audio-preview' ); ?>
				</a>
			</div>
		</div>
	</div>
</div>
