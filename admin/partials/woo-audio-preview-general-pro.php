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
	<form method="post" action="options.php">
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<label for="wcap-pro-tab">
							<?php esc_html_e( 'Preview Button Display Position', 'wc-audio-preview' ); ?>
						</label>
					</th>
					<td>
						<label class="wcap-pro-switch">
							<select id="wcap-pro-btn-display-position" name="wcap_pro_admin_general_option[preview_button_display_position]" disabled>
								<option value="woocommerce_before_add_to_cart_button" <?php selected( $wcap_pro_display_btn_position, 'woocommerce_before_add_to_cart_button' ); ?>><?php esc_html_e( 'Before Add to cart Button', 'wc-audio-preview' ); ?></option>
								<option value="woocommerce_after_add_to_cart_button" <?php selected( $wcap_pro_display_btn_position, 'woocommerce_after_add_to_cart_button' ); ?>><?php esc_html_e( 'After Add to cart Button', 'wc-audio-preview' ); ?></option>
							</select>
							<div class="wcap-pro bupr-round"></div>
						</label>
					</td>
				</tr>
				<tr>
				<th scope="row"><label for="blogname"><?php esc_html_e( 'Exclude Category', 'wc-audio-preview' ); ?></label></th>
					<td>
						<select id="wcap-pro-allow-audio-support" multiple name="wcap_pro_admin_general_option[preview_audio_support][]" disabled>
									<option value=""><?php echo esc_html( 'Select Category' ); ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="blogname"><?php esc_html_e( 'Preview Button Text Color', 'wc-audio-preview' ); ?></label></th>
					<td>
						<input type="color" name="wcap_pro_admin_general_option[preview_button_text_color]" value="<?php echo esc_attr( $wcap_pro_get_btn_text_color ); ?>" disabled>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="blogname"><?php esc_html_e( 'Preview Button Hover Text Color', 'wc-audio-preview' ); ?></label></th>
					<td>
						<input type="color" name="wcap_pro_admin_general_option[preview_button_hover_text_color]" value="<?php echo esc_attr( $wcap_pro_get_btn_hover_text_color ); ?>" disabled>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="blogname"><?php esc_html_e( 'Preview Button Background Color', 'wc-audio-preview' ); ?></label></th>
					<td>
						<input type="color" name="wcap_pro_admin_general_option[preview_button_bg_color]" value="<?php echo esc_attr( $wcap_pro_get_btn_bg_color ); ?>" disabled>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="blogname"><?php esc_html_e( 'Preview Button Hover Background Color', 'wc-audio-preview' ); ?></label></th>
					<td>
						<input type="color" name="wcap_pro_admin_general_option[preview_button_hover_bg_color]" value="<?php echo esc_attr( $wcap_pro_get_btn_hover_bg_color ); ?>" disabled>
					</td>
				</tr>
			</tbody>
		</table>
	</form>
</div>
