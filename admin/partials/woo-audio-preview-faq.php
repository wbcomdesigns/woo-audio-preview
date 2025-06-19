<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://wbcomdesigns.com
 * @since      1.0.0
 *
 * @package    wc-audio-preview
 * @subpackage wc-audio-preview/admin/partials
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
						<?php esc_html_e( 'What are the essential steps to use the plugin?', 'wc-audio-preview' ); ?>
					</button>
					<div class="wbcom-faq-panel">
						<p> 
							<?php esc_html_e( 'Setting up audio previews for your products is simple: ', 'wc-audio-preview' ); ?>
						</p>
						<p> 
							<?php esc_html_e( '1. Navigate to Products: Go to WooCommerce → Products in your WordPress admin', 'wc-audio-preview' ); ?>
						</p>
						<p> 
							<?php esc_html_e( '2. Edit Your Product: Select the product you want to add an audio preview to', 'wc-audio-preview' ); ?>
						</p>
						<p> 
							<?php esc_html_e( '3. Find Preview Section: Scroll down to the "Audio Preview Item" section', 'wc-audio-preview' ); ?>
						</p>
						<p> 
							<?php esc_html_e( '4. Upload Audio File: Select your audio file to use as the preview', 'wc-audio-preview' ); ?>
						</p>
						<p> 
							<?php esc_html_e( '5. Save Changes: Update your product to activate the preview', 'wc-audio-preview' ); ?>
						</p>
						<p> 
							<?php esc_html_e( 'The audio preview button will now appear on your product page, allowing customers to listen before purchasing.', 'wc-audio-preview' ); ?>
						</p>
					</div>
				</div>
			</div>
			<div class="wbcom-faq-section-row">
				<div class="wbcom-faq-admin-row">
					<button class="wbcom-faq-accordion">
						<?php esc_html_e( 'Does this plugin require WooCommerce?', 'wc-audio-preview' ); ?>
					</button>
					<div class="wbcom-faq-panel">
						<p> 
							<?php esc_html_e( 'Yes, it requires WooCommerce to be installed and activated.', 'wc-audio-preview' ); ?>
						</p>
					</div>
				</div>
			</div>
			<div class="wbcom-faq-section-row">
				<div class="wbcom-faq-admin-row">
					<button class="wbcom-faq-accordion">
						<?php esc_html_e( 'What audio formats are supported?', 'wc-audio-preview' ); ?>
					</button>
					<div class="wbcom-faq-panel">
						<p> 
							<?php esc_html_e( 'The plugin supports the following audio formats:', 'wc-audio-preview' ); ?>
						</p>
						<ul>
							<li><?php esc_html_e( 'MP3 - Universal compatibility (recommended)', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( 'WAV - High quality, larger files', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( 'OGG - Open format, good compression', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( "M4A - Apple's audio format", 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( 'AAC - Advanced audio coding', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( 'FLAC - Lossless compression', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( 'WMA - Windows Media Audio', 'wc-audio-preview' ); ?></li>
							<li><?php esc_html_e( 'WEBM - Web-optimized format', 'wc-audio-preview' ); ?></li>
						</ul>
						<p> 
							<?php esc_html_e( 'You can either upload files directly or use external URLs to audio files hosted elsewhere.', 'wc-audio-preview' ); ?>
						</p>
					</div>
				</div>
			</div>
			<div class="wbcom-faq-section-row">
				<div class="wbcom-faq-admin-row">
					<button class="wbcom-faq-accordion">
						<?php esc_html_e( 'Can I upload multiple preview files for one product?', 'wc-audio-preview' ); ?>
					</button>
					<div class="wbcom-faq-panel">
						<p> 
							<?php esc_html_e( 'Absolutely! The plugin supports multiple audio preview files per product. This allows you to showcase different samples, tracks, or variations of your audio product, giving customers a comprehensive preview experience.', 'wc-audio-preview' ); ?>
						</p>
					</div>
				</div>
			</div>
			 <div class="wbcom-faq-section-row">
				<div class="wbcom-faq-admin-row">
					<button class="wbcom-faq-accordion">
						<?php esc_html_e( '❓ Why isn’t the OGG audio format working on iPhones or iPads?', 'wc-audio-preview' ); ?>
					</button>
					<div class="wbcom-faq-panel">
						<p> 
							<?php esc_html_e( 'The OGG audio format is not natively supported by iOS devices, including iPhones and iPads. This limitation is due to Apple’s system-level media support, which does not include the OGG (Vorbis) codec. As a result, audio files in .ogg format may not play properly or at all on Safari or other browsers on iOS', 'wc-audio-preview' ); ?> 
						</p>
					</div>
				</div>
			</div>
			<div class="wbcom-faq-section-row">
				<div class="wbcom-faq-admin-row">
					<button class="wbcom-faq-accordion">
						<?php esc_html_e( 'How do I reorder audio files?', 'wc-audio-preview' ); ?>
					</button>
					<div class="wbcom-faq-panel">
						<p>
							<?php esc_html_e( 'Use the drag handle (⋮⋮⋮) on the left side of each audio row to drag and drop files into your desired order.', 'wc-audio-preview' ); ?>   
						</p>
					</div>
				</div>
			</div> 
		</div>
	</div>
</div>
</div>



