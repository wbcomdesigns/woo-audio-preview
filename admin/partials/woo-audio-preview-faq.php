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
							<?php esc_html_e( '4. Upload Audio File: Select your MP3 file to use as the preview', 'wc-audio-preview' ); ?>
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
							<?php esc_html_e( 'Yes, this plugin requires WooCommerce to be installed and activated on your WordPress site. The Woo Audio Preview plugin is specifically designed to work with WooCommerce products and will not function without it.', 'wc-audio-preview' ); ?>
						</p>
					</div>
				</div>
			</div>
			<div class="wbcom-faq-section-row">
				<div class="wbcom-faq-admin-row">
					<button class="wbcom-faq-accordion">
						<?php esc_html_e( 'Can customers download the preview file?', 'wc-audio-preview' ); ?>
					</button>
					<div class="wbcom-faq-panel">
						<p> 
							<?php esc_html_e( 'Yes, the preview audio files are downloadable by default. Customers can listen to the preview directly on the product page and also download it if they choose to do so.', 'wc-audio-preview' ); ?>     
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
						<?php esc_html_e( 'What if I need more features?', 'wc-audio-preview' ); ?>
					</button>
					<div class="wbcom-faq-panel">
						<p> 
							<?php esc_html_e( 'You can hire our team to assist you.', 'wc-audio-preview' ); ?> 
						</p>
					</div>
				</div>
			</div>
			<div class="wbcom-faq-section-row">
				<div class="wbcom-faq-admin-row">
					<button class="wbcom-faq-accordion">
						<?php esc_html_e( 'What if I have a question?', 'wc-audio-preview' ); ?>
					</button>
					<div class="wbcom-faq-panel">
						<?php $contatct_page = '<a href="https://wbcomdesigns.com/contact/">contact page</a>'; ?>
						<p>
							<?php esc_html_e( 'No problem. Please get in touch with us via our', 'wc-audio-preview' ); ?>   
							<a href="https://wbcomdesigns.com/contact/" target="_blank"><?php echo esc_html( 'contact page.' ); ?></a>  
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>



