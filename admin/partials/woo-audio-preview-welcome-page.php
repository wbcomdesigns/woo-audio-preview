<?php
/**
 * This file is used for rendering and saving plugin welcome settings.
 *
 * @package bp_stats
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
	// Exit if accessed directly.
}
?>
<div class="wbcom-tab-content">
	<div class="wbcom-welcome-main-wrapper">
		<div class="wbcom-welcome-head">
				<p class="wbcom-welcome-description">
				<?php esc_html_e( 'Woo Audio Preview adds a dedicated button to the single product page, allowing users to preview audio files before making a purchase. The plugin includes a user-friendly meta box where you can easily upload and manage sample audio files for preview.', 'wc-audio-preview' ); ?>
				</p>
		</div><!-- .wbcom-welcome-head -->

		<div class="wbcom-welcome-content">

		<div class="wbcom-welcome-support-info">
			<h3><?php esc_html_e( 'Help &amp; Support Resources', 'wc-audio-preview' ); ?></h3>
			<p><?php esc_html_e( 'If you need assistance, here are some helpful resources. Our documentation is a great place to start, and our support team is available if you require further help.', 'wc-audio-preview' ); ?></p>

			<div class="wbcom-support-info-wrap">
				<div class="wbcom-support-info-widgets">
					<div class="wbcom-support-inner">
						<h3><span class="dashicons dashicons-book"></span><?php esc_html_e( 'Documentation', 'wc-audio-preview' ); ?></h3>
						<p><?php esc_html_e( 'Explore our detailed guide on Woo Audio Preview to understand all the features and how to make the most of them.', 'wc-audio-preview' ); ?></p>
						<a href="<?php echo esc_url( 'https://docs.wbcomdesigns.com/doc_category/woo-audio-preview/' ); ?>" class="button button-primary button-welcome-support" target="_blank"><?php esc_html_e( 'Read Documentation', 'wc-audio-preview' ); ?></a>
					</div>
				</div>

				<div class="wbcom-support-info-widgets">
					<div class="wbcom-support-inner">
						<h3><span class="dashicons dashicons-sos"></span><?php esc_html_e( 'Support Center', 'wc-audio-preview' ); ?></h3>
						<p><?php esc_html_e( 'Our support team is here to assist you with any questions or issues. Feel free to contact us anytime through our support center.', 'wc-audio-preview' ); ?></p>
						<a href="<?php echo esc_url( 'https://wbcomdesigns.com/support/' ); ?>" class="button button-primary button-welcome-support" target="_blank"><?php esc_html_e( 'Get Support', 'wc-audio-preview' ); ?></a>
					</div>
				</div>
				<div class="wbcom-support-info-widgets">
					<div class="wbcom-support-inner">
						<h3><span class="dashicons dashicons-admin-comments"></span><?php esc_html_e( 'Share Your Feedback', 'wc-audio-preview' ); ?></h3>
						<p><?php esc_html_e( 'We’d love to hear about your experience with the plugin. Your feedback and suggestions help us improve future updates.', 'wc-audio-preview' ); ?></p>
						<a href="<?php echo esc_url( 'https://wbcomdesigns.com/submit-review/' ); ?>" class="button button-primary button-welcome-support" target="_blank"><?php esc_html_e( 'Send Feedback', 'wc-audio-preview' ); ?></a>
					</div>
				</div>
			</div>
		</div>
		</div>

	</div><!-- .wbcom-welcome-content -->
</div><!-- .wbcom-welcome-main-wrapper -->
