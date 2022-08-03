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
				<?php esc_html_e( 'Woo Audio Preview provides a button on the single product page which will allow you to preview the Audio. The plugin provides a simple meta box to add sample audio file which can be previewed.', 'wc-audio-preview' ); ?>
				</p>
		</div><!-- .wbcom-welcome-head -->

		<div class="wbcom-welcome-content">

		<div class="wbcom-welcome-support-info">
			<h3><?php esc_html_e( 'Help &amp; Support Resources', 'wc-audio-preview' ); ?></h3>
			<p><?php esc_html_e( 'Here are all the resources you may need to get help from us. Documentation is usually the best place to start. Should you require help anytime, our customer care team is available to assist you at the support center.', 'wc-audio-preview' ); ?></p>

			<div class="wbcom-support-info-wrap">
				<div class="wbcom-support-info-widgets">
					<div class="wbcom-support-inner">
						<h3><span class="dashicons dashicons-book"></span><?php esc_html_e( 'Documentation', 'wc-audio-preview' ); ?></h3>
						<p><?php esc_html_e( 'Woo Audio Preview provides a button on the single product page which will allow you to preview the Audio. The plugin provides a simple meta box to add sample audio file which can be previewed.', 'wc-audio-preview' ); ?></p>
						<a href="<?php echo esc_url( 'https://wbcomdesigns.com/docs/woo-addons/woo-audio-preview/' ); ?>" class="button button-primary button-welcome-support" target="_blank"><?php esc_html_e( 'Read Documentation', 'wc-audio-preview' ); ?></a>
					</div>
				</div>

				<div class="wbcom-support-info-widgets">
					<div class="wbcom-support-inner">
						<h3><span class="dashicons dashicons-sos"></span><?php esc_html_e( 'Support Center', 'wc-audio-preview' ); ?></h3>
						<p><?php esc_html_e( 'We strive to offer the best customer care via our support center. Once your theme is activated, you can ask us for help anytime.', 'wc-audio-preview' ); ?></p>
						<a href="<?php echo esc_url( 'https://wbcomdesigns.com/support/' ); ?>" class="button button-primary button-welcome-support" target="_blank"><?php esc_html_e( 'Get Support', 'wc-audio-preview' ); ?></a>
					</div>
				</div>
				<div class="wbcom-support-info-widgets">
					<div class="wbcom-support-inner">
						<h3><span class="dashicons dashicons-admin-comments"></span><?php esc_html_e( 'Got Feedback?', 'wc-audio-preview' ); ?></h3>
						<p><?php esc_html_e( 'We want to hear about your experience with the plugin. We would also love to hear any suggestions you may for future updates.', 'wc-audio-preview' ); ?></p>
						<a href="<?php echo esc_url( 'https://wbcomdesigns.com/contact/' ); ?>" class="button button-primary button-welcome-support" target="_blank"><?php esc_html_e( 'Send Feedback', 'wc-audio-preview' ); ?></a>
					</div>
				</div>
			</div>
		</div>
		</div>

	</div><!-- .wbcom-welcome-content -->
</div><!-- .wbcom-welcome-main-wrapper -->
