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
<div class="woo-audio-preview-admin-setting">
	<div class="woo-audio-preview-tab-header">
		<h3><?php esc_html_e( 'FAQ(s) ', 'wc-audio-preview' ); ?></h3>
		<input type="hidden" class="wb-ads-tab-active" value="support"/>
	</div>

	<div class="woo-audio-preview-admin-settings-block">
		<div id="woo-audio-preview-tbl" class="wb-ads-table">
			<div class="woo-audio-preview-admin-row border">
				<div class="woo-audio-preview-admin-col-12">
					<button class="woo-audio-preview-accordion">
						<?php esc_html_e( 'Does This plugin requires Woocommerce?', 'wc-audio-preview' ); ?>
					</button>
					<div class="wb-ads-panel">
						<p> 
							<?php esc_html_e( 'Yes, It needs you to have Woocommerce installed and activated.', 'wc-audio-preview' ); ?>
						</p>
					</div>
				</div>
			</div>
			<div class="woo-audio-preview-admin-row border">
				<div class="woo-audio-preview-admin-col-12">
					<button class="woo-audio-preview-accordion">
						<?php esc_html_e( 'Can We download the preview file?', 'wc-audio-preview' ); ?>
					</button>
					<div class="wb-ads-panel">
						<p> 
							<?php esc_html_e( 'Yes Preview file is downloadable', 'wc-audio-preview' ); ?>     
						</p>
					</div>
				</div>
			</div>
			<div class="woo-audio-preview-admin-row border">
				<div class="woo-audio-preview-admin-col-12">
					<button class="woo-audio-preview-accordion">
						<?php esc_html_e( 'Can multiple files be uploaded to preview?', 'wc-audio-preview' ); ?>
					</button>
					<div class="wb-ads-panel">
						<p> 
							<?php esc_html_e( 'Yes plugin provides a feature to upload multiple preview Files', 'wc-audio-preview' ); ?>    
						</p>
					</div>
				</div>
			</div>
			<div class="woo-audio-preview-admin-row border">
				<div class="woo-audio-preview-admin-col-12">
					<button class="woo-audio-preview-accordion">
						<?php esc_html_e( 'What if I need more features?', 'wc-audio-preview' ); ?>
					</button>
					<div class="wb-ads-panel">
						<p> 
							<?php esc_html_e( 'You can hire our team to assist you.', 'wc-audio-preview' ); ?>    
						</p>
					</div>
				</div>
			</div>
			<div class="woo-audio-preview-admin-row border">
				<div class="woo-audio-preview-admin-col-12">
					<button class="woo-audio-preview-accordion">
						<?php esc_html_e( 'What if I have a question?', 'wc-audio-preview' ); ?>
					</button>
					<div class="wb-ads-panel">

					<?php
					$contatct_page = '<a href="https://wbcomdesigns.com/contact/">contact page</a>';
					?>
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

