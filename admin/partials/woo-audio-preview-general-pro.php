<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://wbcomdesigns.com
 * @since      1.0.0
 *
 * @package    Woo_Audio_Preview
 * @subpackage Woo_Audio_Preview/admin/partials
 */

?>
<div class="wbcom-tab-content woo-document-pro">
	<div class="wbcom-wrapper-admin">
		<div class="wbcom-admin-title-section">
			<h3><?php esc_html_e( 'General Settings', 'wc-audio-preview' ); ?></h3>
		</div>
		<div class="wbcom-admin-option-wrap wbcom-admin-option-wrap-view">
			<form method="post" action="options.php">
				<div class="form-table">
					<div class="wbcom-settings-section-wrap">
						<div class="wbcom-settings-section-options-heading">
							<label for="wcap-pro-tab">
							<?php esc_html_e( 'Preview Button Display Position', 'wc-audio-preview' ); ?>
							</label>
						</div>
						<div class="wbcom-settings-section-options">
							<label class="bupr-switch">
								<select id="wcap-pro-btn-display-position" name="wcap_pro_admin_general_option[preview_button_display_position]" disabled>
									<option value="woocommerce_before_add_to_cart_button"><?php esc_html_e( 'Before Add to cart Button', 'wc-audio-preview' ); ?></option>
									<option value="woocommerce_after_add_to_cart_button"><?php esc_html_e( 'After Add to cart Button', 'wc-audio-preview' ); ?></option>
								</select>
								<div class="bupr-slider bupr-round"></div>
							</label>
						</div>
					</div>
				</div>
				<div class="form-table">
					<div class="wbcom-settings-section-wrap">
						<div class="wbcom-settings-section-options-heading">
							<label for="wcap-pro-tab">
							<?php esc_html_e( 'Exclude Category', 'wc-audio-preview' ); ?>
							</label>
						</div>
						<div class="wbcom-settings-section-options">
							<label class="bupr-switch">
								<select id="wcap-pro-allow-audio-support" multiple name="wcap_pro_admin_general_option[preview_audio_support][]" disabled>
									<option value=""><?php echo esc_html( 'Select Category' ); ?></option>
								</select>
								<div class="bupr-slider bupr-round"></div>
							</label>
						</div>
					</div>
				</div>
				<div class="form-table">
					<div class="wbcom-settings-section-wrap">
						<div class="wbcom-settings-section-options-heading">
							<label for="wcap-pro-tab">
							<?php esc_html_e( 'Preview Button Text Color', 'wc-audio-preview' ); ?>
							</label>
						</div>
						<div class="wbcom-settings-section-options">
							<label class="bupr-switch">
								<input type="color" name="wcap_pro_admin_general_option[preview_button_text_color]" value="" disabled>
								<div class="bupr-slider bupr-round"></div>
							</label>
						</div>
					</div>
				</div>
				<div class="form-table">
					<div class="wbcom-settings-section-wrap">
						<div class="wbcom-settings-section-options-heading">
							<label for="wcap-pro-tab">
							<?php esc_html_e( 'Preview Button Text Color', 'wc-audio-preview' ); ?>
							</label>
						</div>
						<div class="wbcom-settings-section-options">
							<label class="bupr-switch">
								<input type="color" name="wcap_pro_admin_general_option[preview_button_hover_text_color]" value="" disabled>
								<div class="bupr-slider bupr-round"></div>
							</label>
						</div>
					</div>
				</div>
				<div class="form-table">
					<div class="wbcom-settings-section-wrap">
						<div class="wbcom-settings-section-options-heading">
							<label for="wcap-pro-tab">
							<?php esc_html_e( 'Preview Button Background Color', 'wc-audio-preview' ); ?>
							</label>
						</div>
						<div class="wbcom-settings-section-options">
							<label class="bupr-switch">
								<input type="color" name="wcap_pro_admin_general_option[preview_button_bg_color]" value="" disabled>
								<div class="bupr-slider bupr-round"></div>
							</label>
						</div>
					</div>
				</div>
				<div class="form-table">
					<div class="wbcom-settings-section-wrap">
						<div class="wbcom-settings-section-options-heading">
							<label for="wcap-pro-tab">
							<?php esc_html_e( 'Preview Button Hover Background Color', 'wc-audio-preview' ); ?>
							</label>
						</div>
						<div class="wbcom-settings-section-options">
							<label class="bupr-switch">
								<input type="color" name="wcap_pro_admin_general_option[preview_button_hover_bg_color]" value="" disabled>
								<div class="bupr-slider bupr-round"></div>
							</label>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
