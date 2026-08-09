<?php
/**
 * Enhanced admin-specific functionality supporting media library and CDN URLs
 *
 * @link       http://wbcomdesigns.com
 * @since      1.0.0
 *
 * @package    Wc_Audio_Preview
 * @subpackage Wc_Audio_Preview/admin
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enhanced admin-specific functionality of the plugin.
 *
 * Now supports media library integration and CDN/external URL validation
 * with improved UI matching the Document Preview plugin.
 *
 * @package    Wc_Audio_Preview
 * @subpackage Wc_Audio_Preview/admin
 * @author     Wbcom Designs <admin@wbcomdesigns.com>*/
class Wc_Audio_Preview_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Plugin_settings_tabs
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var mixed    $plugin_settings_tabs  The Settings tab.
	 */
	public $plugin_settings_tabs;

	/**
	 * Allowed audio file types
	 *
	 * @since    1.5.0
	 * @access   private
	 * @var      array    $allowed_file_types    Allowed file extensions.
	 */
	private $allowed_file_types = array( 'mp3', 'wav', 'ogg', 'm4a', 'aac', 'flac', 'wma', 'webm' );

	/**
	 * Allowed MIME types for audio files
	 *
	 * @since    1.5.0
	 * @access   private
	 * @var      array    $allowed_mime_types    Allowed MIME types.
	 */
	private $allowed_mime_types = array(
		'audio/mpeg',
		'audio/mp3',
		'audio/wav',
		'audio/wave',
		'audio/x-wav',
		'audio/ogg',
		'audio/mp4',
		'audio/x-m4a',
		'audio/aac',
		'audio/flac',
		'audio/x-ms-wma',
		'audio/webm',
	);

	/**
	 * CDN and streaming service patterns
	 *
	 * @since    1.5.0
	 * @access   private
	 * @var      array    $cdn_patterns    Patterns for CDN URLs.
	 */
	private $cdn_patterns = array(
		'soundcloud'   => array(
			'/soundcloud\.com\/[a-zA-Z0-9-_]+\/[a-zA-Z0-9-_]+/i',
			'/api\.soundcloud\.com\/tracks\/[0-9]+/i',
		),
		'spotify'      => array(
			'/open\.spotify\.com\/track\/[a-zA-Z0-9]+/i',
			'/spotify:track:[a-zA-Z0-9]+/i',
		),
		'amazon_s3'    => array(
			'/s3\.amazonaws\.com\/[^\/]+\/.+\.(mp3|wav|ogg|m4a)/i',
			'/[a-zA-Z0-9-]+\.s3\.[a-zA-Z0-9-]+\.amazonaws\.com\/.+\.(mp3|wav|ogg|m4a)/i',
		),
		'cloudfront'   => array(
			'/[a-zA-Z0-9]+\.cloudfront\.net\/.+\.(mp3|wav|ogg|m4a)/i',
		),
		'google_drive' => array(
			// Standard sharing link pattern (with or without /view and query params).
			'/drive\.google\.com\/file\/d\/([a-zA-Z0-9-_]+)(?:\/view)?(?:\?.*)?/i',
			// Direct download pattern.
			'/drive\.google\.com\/uc\?(?:.*&)?id=([a-zA-Z0-9-_]+)(?:&.*)?/i',
			// Open link pattern.
			'/drive\.google\.com\/open\?id=([a-zA-Z0-9-_]+)/i',
		),
		'dropbox'      => array(
			'/dropbox\.com\/s\/([a-zA-Z0-9_-]+)\/([^?]+\.(mp3|wav|ogg|m4a))/i',
			'/dl\.dropbox(?:usercontent)?\.com\/s\/([a-zA-Z0-9_-]+)\/([^?]+)/i',
		),
	);

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wc_Audio_Preview_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wc_Audio_Preview_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$screen = get_current_screen();
		if ( ( $screen->id === 'product' && ( $screen->action === 'add' || $screen->action === '' ) ) || ( isset( $_GET['page'] ) && sanitize_text_field( wp_unslash( $_GET['page'] ) ) === 'woo-audio-preview-settings' ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			$css_file = $this->get_asset_filename( 'css', 'wcap-admin' );
			if ( $css_file ) {
				wp_enqueue_style(
					$this->plugin_name,
					plugin_dir_url( __FILE__ ) . $css_file,
					array(),
					$this->version,
					'all'
				);

				// Add enhanced styles for better UI.
			}
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wc_Audio_Preview_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wc_Audio_Preview_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$screen = get_current_screen();

		if ( ( $screen->id === 'product' && ( $screen->action === 'add' || $screen->action === '' ) ) || ( isset( $_GET['page'] ) && sanitize_text_field( wp_unslash( $_GET['page'] ) ) === 'woo-audio-preview-settings' ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			// Enqueue media uploader.
			wp_enqueue_media();
			$js_file = $this->get_asset_filename( 'js', 'wcap-admin' );
			if ( $js_file ) {
				wp_enqueue_script(
					$this->plugin_name,
					plugin_dir_url( __FILE__ ) . $js_file,
					array( 'jquery', 'wp-i18n', 'media-upload' ),
					$this->version,
					false
				);

				// Enhanced localize script with CDN support.
				wp_localize_script(
					$this->plugin_name,
					'wcap_ajax_object',
					array(
						'ajax_url'           => admin_url( 'admin-ajax.php' ),
						'nonce'              => wp_create_nonce( 'ajax-nonce' ),
						'allowedExtensions'  => apply_filters( 'wcap_allowed_audio_extensions', $this->allowed_file_types ),
						'cdn_patterns'       => $this->get_cdn_patterns_for_js(),
						'error_messages'     => array(
							'invalid_file_type' => __( 'Invalid audio file type. Supported formats: MP3, WAV, OGG, M4A, AAC, FLAC, WMA, WEBM, or direct links from supported services.', 'woo-audio-preview' ),
							'file_required'     => __( 'Please select a file or enter a file URL.', 'woo-audio-preview' ),
							'name_required'     => __( 'Audio name is required.', 'woo-audio-preview' ),
							'url_invalid'       => __( 'Please enter a valid URL.', 'woo-audio-preview' ),
							'file_too_large'    => __( 'File size is too large. Maximum allowed size is 50MB.', 'woo-audio-preview' ),
							'cdn_detected'      => __( 'CDN/streaming service link detected! This will work great for preview.', 'woo-audio-preview' ),
						),
						'supported_services' => $this->get_supported_services_info(),
					)
				);
			}
		}
	}

	/**
	 * Get CDN patterns formatted for JavaScript
	 *
	 * @since    1.5.0
	 * @return   array    Patterns for JS.
	 */
	private function get_cdn_patterns_for_js() {
		// Return empty array since we're using hardcoded patterns in JS.
		return array();
	}

	/**
	 * Get supported services information
	 *
	 * @since    1.5.0
	 * @return   array    Services information.
	 */
	private function get_supported_services_info() {
		return array(
			'soundcloud'   => 'SoundCloud',
			'spotify'      => 'Spotify',
			'amazon_s3'    => 'Amazon S3',
			'cloudfront'   => 'CloudFront',
			'google_drive' => 'Google Drive',
			'dropbox'      => 'Dropbox',
		);
	}

	/**
	 * Action performed to hide all admin notices from setting page
	 *
	 * @return void
	 */
	public function wcap_hide_all_admin_notices_from_setting_page() {

		if ( isset( $_GET['page'] ) && in_array( sanitize_text_field( wp_unslash( $_GET['page'] ) ), array( 'wbcomplugins', 'wbcom-plugins-page', 'wbcom-support-page', 'woo-audio-preview-settings' ), true ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			// Remove non-critical notices only.
			remove_action( 'admin_notices', 'update_nag', 3 );
			remove_all_actions( 'admin_notices' );
			remove_all_actions( 'all_admin_notices' );

		}
	}



	/*
	 * wcap_wb_plugins_page() lived here. It rendered the shared Wbcom landing page; the settings library renders it now.
	 * Nothing referenced it once the parent menu stopped pointing at it.
	 */

	/*
	 * The Welcome / General (PRO) / FAQ screens used to be built here: a settings-section per tab,
	 * a hand-rolled horizontal tab strip, and three partials of wbcom- markup. They are now tabs
	 * on the shared settings page (WCAP_Settings_Tabs), which the Pro plugin also draws through,
	 * so free and Pro present one product instead of two different admin screens.
	 *
	 * The register_setting() calls that went with them registered two option rows that never held
	 * a setting - the sections were informational - so nothing is migrated and nothing is lost.
	 */


	/**
	 * Actions performed on loading admin_menu.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @author   Wbcom Designs
	 */
	public function wcap_views_add_admin_settings() {
		if ( empty( $GLOBALS['admin_page_hooks']['wbcomplugins'] ) ) {
			add_menu_page( esc_html__( 'WB Plugins', 'woo-audio-preview' ), esc_html__( 'WB Plugins', 'woo-audio-preview' ), 'manage_options', 'wbcomplugins', array( 'Wbcom_Settings_Page', 'render_welcome' ), 'dashicons-lightbulb', 59 );
			add_submenu_page( 'wbcomplugins', esc_html__( 'Welcome', 'woo-audio-preview' ), esc_html__( 'Welcome', 'woo-audio-preview' ), 'manage_options', 'wbcomplugins' );

		}
		/*
		 * The settings page itself is registered by WCAP_Settings_Page, which the Pro plugin also
		 * carries. Registering it here as well would put the entry in the menu twice.
		 */
	}


	/**
	 * Update edit form enctype.
	 */
	public function wcap_update_edit_form() {
		echo ' enctype="multipart/form-data"';
	}

	/**
	 * How many preview rows the box shows.
	 *
	 * Three in the free plugin - the limit IS the free tier. The Pro plugin filters this to lift
	 * it, which is why the number is not hardcoded in the loop any more; it used to appear three
	 * times (render, save, and the heading) and Pro's answer was to draw a whole second meta box
	 * with its own name and URL fields.
	 *
	 * @since  1.5.3
	 * @param  int $saved How many previews the product already has.
	 * @return int Rows to render, never fewer than what is already saved.
	 */
	public static function row_count( $saved = 0 ) {
		/**
		 * Filter the number of preview rows.
		 *
		 * @since 1.5.3
		 * @param int $rows  Row count. Default 3.
		 * @param int $saved Previews already saved on this product.
		 */
		$rows = (int) apply_filters( 'wcap_metabox_max_rows', 3, $saved );

		return max( 1, $rows, (int) $saved );
	}

	/**
	 * Register enhanced meta box.
	 */
	public function wcap_register_meta_boxes() {
		/**
		 * Whether something else is providing the product's audio preview box.
		 *
		 * The Pro plugin renders a richer box - unlimited rows, per-track duration, reordering -
		 * and returns true here so only one is shown. Without this seam both plugins registered a
		 * box on the same product, and because this one reads only its own three fixed fields it
		 * came up EMPTY beside Pro's populated one. Two boxes claiming to manage the same audio,
		 * one of them blank: fill in the blank one, press Update, and whichever saves last wins.
		 *
		 * That is a data-loss path, not a cosmetic duplicate.
		 *
		 * @since 1.5.3
		 * @param bool $handled Whether another plugin owns the metabox.
		 */
		if ( apply_filters( 'wcap_product_metabox_handled', false ) ) {
			return;
		}

		/**
		 * Filter the meta box title.
		 *
		 * A plain title. The supported-format list used to live in the heading, which made it
		 * three lines long on a narrow screen and read as a warning rather than as help - it is
		 * one collapsed note inside the box now. The filter is the seam an extension uses to add
		 * to the heading instead of registering a second box.
		 *
		 * @since 1.5.3
		 * @param string $label_text Meta box title. May contain markup.
		 */
		$label_text = apply_filters( 'wcap_metabox_title', __( 'Audio previews', 'woo-audio-preview' ) );

		add_meta_box(
			'wc-preview-audio-mata-id',
			$label_text,
			array( $this, 'wcap_display_callback' ),
			'product'
		);
	}

	/**
	 * One preview row.
	 *
	 * Extracted so the render loop and the "add another" template share a single definition. They
	 * used to be separate: the Pro plugin carried its own row markup AND its own JS template, so
	 * the add button stopped working the moment its meta box was folded into this one - there was
	 * no template left to clone.
	 *
	 * @since 1.5.3
	 * @param int|string $i          Row index, or a placeholder token for the JS template.
	 * @param string     $audio_name Saved name.
	 * @param string     $audio_url  Saved URL.
	 */
	public function render_row( $i, $audio_name = '', $audio_url = '' ) {
		$cdn_info = ( '' !== $audio_url ) ? $this->is_cdn_url( $audio_url ) : false;
		?>
		<tr class="wcap-audio-row">
			<td class="sort" data-label="<?php esc_attr_e( 'Sort', 'woo-audio-preview' ); ?>"></td>

			<td class="audio_name" data-label="<?php esc_attr_e( 'Name', 'woo-audio-preview' ); ?>">
				<input type="text"
					class="input_text wcap-audio-name"
					name="wcap_audio[wcap_audio_names][]"
					value="<?php echo esc_attr( $audio_name ); ?>"
					placeholder="<?php esc_attr_e( 'Track name', 'woo-audio-preview' ); ?>" />
			</td>

			<td class="audio_url" data-label="<?php esc_attr_e( 'URL', 'woo-audio-preview' ); ?>">
				<input type="url"
					class="input_text wcap-audio-url"
					name="wcap_audio[wcap_audio_urls][]"
					value="<?php echo esc_url( $audio_url ); ?>"
					placeholder="<?php esc_attr_e( 'https://example.com/audio.mp3 or CDN link', 'woo-audio-preview' ); ?>" />
				<?php if ( $cdn_info ) : ?>
					<span class="wcap-service-indicator">
						<?php
						printf(
							/* translators: %s: Detected service name, e.g. Dropbox. */
							esc_html__( '%s link detected', 'woo-audio-preview' ),
							esc_html( ucfirst( str_replace( '_', ' ', $cdn_info['service'] ) ) )
						);
						?>
					</span>
				<?php endif; ?>
			</td>

			<td class="audio_choose" width="1%" data-label="<?php esc_attr_e( 'Upload', 'woo-audio-preview' ); ?>">
				<button type="button" class="button wcap-media-button" data-field-index="<?php echo esc_attr( $i ); ?>">
					<?php esc_html_e( 'Choose file', 'woo-audio-preview' ); ?>
				</button>
			</td>

			<?php
			/**
			 * Render extra cells inside a preview row.
			 *
			 * Cells, not a block: the row is a table row, so an extension contributing here must
			 * emit <td>. The Pro plugin puts its per-track preview duration in one.
			 *
			 * @since 1.5.3
			 * @param int|string $i          Row index, or the template placeholder.
			 * @param string     $audio_name Saved name.
			 * @param string     $audio_url  Saved URL.
			 */
			do_action( 'wcap_metabox_row_fields', $i, $audio_name, $audio_url );
			?>

			<td class="audio_actions" width="1%" data-label="<?php esc_attr_e( 'Actions', 'woo-audio-preview' ); ?>">
				<button type="button" class="button button-small wcap-remove-row" title="<?php esc_attr_e( 'Remove this preview', 'woo-audio-preview' ); ?>">
					<?php esc_html_e( 'Remove', 'woo-audio-preview' ); ?>
				</button>
			</td>
		</tr>
		<?php
	}

	/**
	 * Enhanced meta box display callback with exactly 3 fixed fields
	 *
	 * @param WP_Post $post Current post object.
	 */
	public function wcap_display_callback( $post ) {
		// Add nonce for security and authentication.
		wp_nonce_field( 'wcap_nonce_action', 'wcap_nonce' );

		$wcap_audio = get_post_meta( $post->ID, 'wcap_audio', true );
		$wcap_items = class_exists( 'WCAP_Audio' ) ? WCAP_Audio::get( $post->ID ) : array();
		$wcap_rows  = self::row_count( count( $wcap_items ) );
		?>
		<div class="form-field preview_files">
			<div class="wcap-error-messages"></div>

			<details class="wcap-metabox-help">
				<summary><?php esc_html_e( 'What can I add here?', 'woo-audio-preview' ); ?></summary>
				<div class="wcap-metabox-help__body">
					<p><?php esc_html_e( 'MP3, WAV, OGG, M4A, AAC, FLAC, WMA and WEBM files, a direct URL, or a CDN link from Google Drive, Dropbox or SoundCloud.', 'woo-audio-preview' ); ?></p>
				</div>
			</details>

			<?php
			/**
			 * Render above the preview rows.
			 *
			 * The seam the Pro plugin uses to add the player theme control, instead of registering
			 * a second meta box beside this one.
			 *
			 * @since 1.5.3
			 * @param WP_Post $post Product being edited.
			 */
			do_action( 'wcap_metabox_before_rows', $post );
			?>

			<table class="widefat wcap-audio-table">
				<thead>
					<tr>
						<th class="sort">&nbsp;</th>
						<th><?php esc_html_e( 'Name', 'woo-audio-preview' ); ?></th>
						<th><?php esc_html_e( 'Audio URL', 'woo-audio-preview' ); ?></th>
						<th><?php esc_html_e( 'Upload', 'woo-audio-preview' ); ?></th>
						<?php
						/**
						 * Render extra column headings.
						 *
						 * Paired with wcap_metabox_row_fields: an extension adding a cell to each
						 * row adds its heading here, so the columns line up.
						 *
						 * @since 1.5.3
						 */
						do_action( 'wcap_metabox_row_headings' );
						?>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody class="wcap-audio-rows">
		<?php
		for ( $i = 0; $i < $wcap_rows; $i++ ) :
			$audio_name = isset( $wcap_items[ $i ]['name'] ) ? $wcap_items[ $i ]['name'] : '';
			$audio_url  = isset( $wcap_items[ $i ]['url'] ) ? $wcap_items[ $i ]['url'] : '';

			$this->render_row( $i, $audio_name, $audio_url );
		endfor;
		?>
				</tbody>
			</table>
					<?php
					/**
					 * Render below the preview rows.
					 *
					 * Where the Pro plugin puts Add More and Bulk Import.
					 *
					 * @since 1.5.3
					 * @param WP_Post $post Product being edited.
					 */
					do_action( 'wcap_metabox_after_rows', $post );
					?>

					<?php
					/*
					 * The template for a new row, built from the SAME render_row() the loop uses - so a row
					 * added in the browser carries every field a rendered row has, including any an
					 * extension contributed through wcap_metabox_row_fields.
					 *
					 * The Pro plugin used to keep its own template beside its own row markup. When its meta
					 * box was folded into this one the template went with it, and "add another preview"
					 * silently stopped adding anything.
					 */
					?>
					<script type="text/template" id="wcap-metabox-row-template">
						<?php $this->render_row( '__INDEX__' ); ?>
					</script>
					<?php if ( ! has_action( 'wcap_metabox_after_rows' ) ) : ?>
					<div class="wcap-pro-notice"><p><strong><?php esc_html_e( 'Need more audio previews?', 'woo-audio-preview' ); ?></strong><br>
							<?php
							printf(
							/* translators: %s: Pro version link. */
								esc_html__( 'Upgrade to %s for unlimited audio previews and dynamic add/remove functionality.', 'woo-audio-preview' ),
								'<a href="https://wbcomdesigns.com/downloads/woo-audio-preview-pro/" target="_blank">' . esc_html__( 'Pro Version', 'woo-audio-preview' ) . '</a>'
							);
							?>
					</p></div>
					<?php endif; ?>
				</div>
					<?php
	}

	/**
	 * Enhanced save meta box for fixed 3 fields.
	 *
	 * @param int $post_id Post ID.
	 */
	public function wcap_save_meta_box( $post_id ) {
		// Add nonce for security and authentication.
		$nonce_name   = isset( $_POST['wcap_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['wcap_nonce'] ) ) : '';
		$nonce_action = 'wcap_nonce_action';

		// Check if nonce is valid.
		if ( empty( $nonce_name ) || ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
			return;
		}

		// Check if user has permissions to save data.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Check if not an autosave.
		if ( wp_is_post_autosave( $post_id ) ) {
			return;
		}

		// Check if not a revision.
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		if ( isset( $_POST['post_type'] ) && 'product' === sanitize_text_field( wp_unslash( $_POST['post_type'] ) ) ) {
			$processed_audio = array(
				'wcap_audio_names'  => array(),
				'wcap_audio_urls'   => array(),
				'wcap_audio_source' => array(),
			);

			$has_valid_audio = false;

			if ( isset( $_POST['wcap_audio'] ) && is_array( $_POST['wcap_audio'] ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Values are sanitized individually below.
				$wcap_audio_raw = wp_unslash( $_POST['wcap_audio'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

				/*
				 * Bounded by however many rows the box actually rendered, not a hardcoded 3. With
				 * the Pro plugin lifting the limit, a fixed 3 here would silently discard every
				 * preview past the third on save - the fields would accept them and the row would
				 * come back short.
				 */
				$submitted = isset( $wcap_audio_raw['wcap_audio_names'] ) ? count( (array) $wcap_audio_raw['wcap_audio_names'] ) : 0;
				$limit     = self::row_count( $submitted );

				for ( $i = 0; $i < $limit; $i++ ) {
					$audio_name = isset( $wcap_audio_raw['wcap_audio_names'][ $i ] ) ?
						sanitize_text_field( $wcap_audio_raw['wcap_audio_names'][ $i ] ) : '';
					$audio_url  = isset( $wcap_audio_raw['wcap_audio_urls'][ $i ] ) ?
						esc_url_raw( $wcap_audio_raw['wcap_audio_urls'][ $i ] ) : '';

					// Only process if both name and URL are provided.
					if ( ! empty( $audio_name ) && ! empty( $audio_url ) ) {
						// Validate URL.
						$validation = $this->validate_audio_url( $audio_url );

						if ( $validation['success'] ) {
							$processed_audio['wcap_audio_names'][]  = $audio_name;
							$processed_audio['wcap_audio_urls'][]   = $audio_url;
							$processed_audio['wcap_audio_source'][] = $validation['source'];
							$has_valid_audio                        = true;
						}
					}
				}
			}

			/*
			 * Merge over what is stored rather than replacing the row.
			 *
			 * The Pro plugin keeps its per-track durations and the player theme in this same meta
			 * row, under sub-keys this handler knows nothing about. Writing $processed_audio
			 * wholesale erased them on every save from this box - and the delete branch erased
			 * them even when the owner had simply cleared the fields.
			 */
			$stored = get_post_meta( $post_id, 'wcap_audio', true );
			$stored = is_array( $stored ) ? $stored : array();

			if ( $has_valid_audio ) {
				update_post_meta( $post_id, 'wcap_audio', array_merge( $stored, $processed_audio ) );
			} else {
				$remaining = array_diff_key( $stored, $processed_audio );

				if ( $remaining ) {
					update_post_meta( $post_id, 'wcap_audio', $remaining );
				} else {
					delete_post_meta( $post_id, 'wcap_audio' );
				}
			}

			/**
			 * Save extra fields contributed to the preview rows.
			 *
			 * The seam the Pro plugin uses to store its durations and player theme, so there is
			 * one meta box with one save handler rather than two boxes racing each other.
			 *
			 * @since 1.5.3
			 * @param int   $post_id         Product being saved.
			 * @param array $processed_audio The names, URLs and sources this handler stored.
			 */
			do_action( 'wcap_metabox_save', $post_id, $processed_audio );
		}
	}
	/**
	 * Check if URL is from a CDN or streaming service.
	 *
	 * @since    1.5.0
	 * @param    string $url The URL to check.
	 * @return   array|false Service info or false if not CDN.
	 */
	private function is_cdn_url( $url ) {
		if ( empty( $url ) ) {
			return false;
		}

		foreach ( $this->cdn_patterns as $service => $patterns ) {
			foreach ( $patterns as $pattern ) {
				if ( preg_match( $pattern, $url, $matches ) ) {
					$result = array(
						'service'      => $service,
						'id'           => isset( $matches[1] ) ? $matches[1] : '',
						'is_cdn'       => true,
						'original_url' => $url,
					);

					// Convert Google Drive URLs to playable format.
					if ( 'google_drive' === $service && ! empty( $matches[1] ) ) {
						$result['playable_url'] = $this->convert_google_drive_url( $url, $matches[1] );
					}

					return $result;
				}
			}
		}
		return false;
	}

	/**
	 * Convert Google Drive sharing URL to direct download URL.
	 *
	 * @since    1.5.0
	 * @param    string $url      Google Drive URL.
	 * @param    string $file_id  Extracted file ID.
	 * @return   string           Direct download URL.
	 */
	private function convert_google_drive_url( $url, $file_id ) {
		// Convert to direct download format.
		// Note: This requires the file to be publicly accessible.
		return 'https://drive.google.com/uc?export=download&id=' . $file_id;
	}

	/**
	 * Validate audio URL.
	 *
	 * @since    1.5.0
	 * @param    string $url Audio URL to validate.
	 * @return   array       Validation result.
	 */
	private function validate_audio_url( $url ) {
		$result = array(
			'success' => false,
			'message' => '',
			'source'  => 'direct',
			'service' => '',
		);

		if ( empty( $url ) ) {
			$result['message'] = __( 'Audio URL cannot be empty.', 'woo-audio-preview' );
			return $result;
		}

		if ( ! filter_var( $url, FILTER_VALIDATE_URL ) ) {
			$result['message'] = __( 'Please enter a valid URL.', 'woo-audio-preview' );
			return $result;
		}

		// Check if it's a CDN URL first (before checking file extensions).
		$cdn_info = $this->is_cdn_url( $url );
		if ( $cdn_info ) {
			$result['success'] = true;
			$result['source']  = 'cdn';
			$result['service'] = $cdn_info['service'];
			$result['message'] = sprintf(
				/* translators: %s: CDN service name. */
				__( 'CDN %s link detected and validated.', 'woo-audio-preview' ),
				ucfirst( str_replace( '_', ' ', $cdn_info['service'] ) )
			);
			return $result;
		}

		// For non-CDN URLs, check file extension.
		$file_extension = '';

		// Extract extension, handling query parameters.
		if ( false !== strpos( $url, '?' ) ) {
			$url_parts      = explode( '?', $url );
			$file_extension = strtolower( pathinfo( $url_parts[0], PATHINFO_EXTENSION ) );
		} else {
			$file_extension = strtolower( pathinfo( $url, PATHINFO_EXTENSION ) );
		}

		// If no extension found or invalid extension for direct URLs.
		if ( empty( $file_extension ) || ! in_array( $file_extension, $this->allowed_file_types, true ) ) {
			$result['message'] = sprintf(
				/* translators: %s: Comma-separated list of supported audio formats. */
				__( 'Invalid audio file type. Supported formats: %s, or direct links from CDN/streaming services.', 'woo-audio-preview' ),
				implode( ', ', array_map( 'strtoupper', $this->allowed_file_types ) )
			);
			return $result;
		}

		$result['success'] = true;
		$result['source']  = 'direct';
		return $result;
	}

	/**
	 * Display admin notice.
	 *
	 * @since    1.5.0
	 * @param    string $message Message to display.
	 * @param    string $type    Notice type.
	 */
	private function add_admin_notice( $message, $type = 'error' ) {
		add_action(
			'admin_notices',
			function () use ( $message, $type ) {
				printf(
					'<div class="notice notice-%s wcap-admin-notice is-dismissible"><p>%s</p></div>',
					esc_attr( $type ),
					esc_html( $message )
				);
			}
		);
	}

	/**
	 * Function contains the audio delete functionality.
	 *
	 * @return void
	 */
	public function wcap_delete_audio_ajax() {
		if ( ! check_ajax_referer( 'ajax-nonce', 'nonce', false ) ) {
			wp_send_json_error( 'Invalid security token' );
			exit;
		}
		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( 'Insufficient permissions' );
			exit;
		}

		$post_id = isset( $_POST['p_id'] ) ? absint( wp_unslash( $_POST['p_id'] ) ) : '';
		$fileurl = isset( $_POST['file_url'] ) ? esc_url_raw( wp_unslash( $_POST['file_url'] ) ) : '';
		if ( ! $post_id || ! $fileurl ) {
			wp_send_json_error( 'Missing required parameters' );
			exit;
		}
		// Verify user can edit this specific post.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			wp_send_json_error( 'Cannot edit this product' );
			exit;
		}

		$filename      = basename( $fileurl );
		$upload_dir    = wp_upload_dir();
		$upload_path   = $upload_dir['basedir'];
		$uploaded_file = $upload_path . '/wcap_files/' . $filename;
		if ( file_exists( $uploaded_file ) && is_writable( $uploaded_file ) ) { // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_is_writable
			$result = wp_delete_file( $uploaded_file );
			if ( false !== $result ) {
				update_post_meta( $post_id, 'wcap_preview_attachment', '' );
				wp_send_json_success( 'File deleted successfully' );
			} else {
				$this->wcap_log_error( 'Failed to delete file: ' . $uploaded_file );
				wp_send_json_error( 'Could not delete file' );
			}
		} else {
			// File doesn't exist or isn't writable, just update the meta.
			update_post_meta( $post_id, 'wcap_preview_attachment', '' );
			wp_send_json_success( 'Metadata updated' );
		}
		exit;
	}

	/**
	 * Set Upload Directory.
	 *
	 * Sets the upload dir to edd. This function is called from
	 * wcap_change_audio_upload_dir().
	 *
	 * @since 1.0
	 * @param array $upload Upload directory information.
	 * @return array Upload directory information.
	 */
	public function wcap_set_upload_dir( $upload ) {
		$upload['subdir'] = '/wcap_files';
		$upload['path']   = $upload['basedir'] . $upload['subdir'];
		$upload['url']    = $upload['baseurl'] . $upload['subdir'];
		return $upload;
	}

	/**
	 * Display admin errors.
	 */
	public function wcap_display_admin_errors() {
		$screen = get_current_screen();

		// Only show on our plugin pages.
		if ( $screen && ( false !== strpos( $screen->id, 'woo-audio-preview' ) || 'product' === $screen->id ) ) {
			$errors = get_option( 'wcap_admin_errors', array() );

			if ( ! empty( $errors ) ) {
				echo '<div class="notice notice-error is-dismissible">';
				foreach ( $errors as $error ) {
					echo '<p>' . esc_html( $error ) . '</p>';
				}
				echo '</div>';

				// Clear errors after displaying.
				update_option( 'wcap_admin_errors', array() );
			}
		}
	}

	/**
	 * Log plugin errors for debugging.
	 *
	 * @param string $message Error message to log.
	 * @param string $level   Log level (error, warning, info).
	 */
	public function wcap_log_error( $message, $level = 'error' ) {
		if ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) {
			// For admin UI, store errors to be displayed.
			if ( is_admin() && 'error' === $level ) {
				$errors   = get_option( 'wcap_admin_errors', array() );
				$errors[] = $message;
				// Keep only last 10 errors.
				$errors = array_slice( $errors, -10 );
				update_option( 'wcap_admin_errors', $errors, false );
			}
		}
	}


	/**
	 * Get asset filename with intelligent fallback.
	 *
	 * @since    1.6.0
	 * @param    string $type     Asset type ('css' or 'js').
	 * @param    string $filename Base filename without extension.
	 * @return   string|false     Full filename with path or false if not found.
	 */
	private function get_asset_filename( $type, $filename ) {
		// Determine if we should use minified files.
		$use_minified = ! ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG );

		// Determine if RTL is needed (only for CSS).
		$is_rtl = ( 'css' === $type ) ? is_rtl() : false;

		// Build the base directory path.
		$base_dir        = plugin_dir_path( __FILE__ ) . $type . '/';
		$actual_type     = $type;
		$actual_base_dir = $base_dir;

		// Array of file variants to try in order of preference.
		$variants = array();

		if ( 'css' === $type ) {
			if ( $is_rtl && $use_minified ) {
				$variants[] = $filename . '.min.css';      // 1st preference: RTL minified.
				$variants[] = $filename . '.css';          // 2nd preference: RTL non-minified.
			} elseif ( $is_rtl && ! $use_minified ) {
				$variants[] = $filename . '.css';          // 1st preference: RTL non-minified.
			} elseif ( ! $is_rtl && $use_minified ) {
				$variants[] = $filename . '.min.css';          // 1st preference: LTR minified.
				$variants[] = $filename . '.css';              // 2nd preference: LTR non-minified.
			} else {
				$variants[] = $filename . '.css';              // 1st preference: LTR non-minified.
			}
		} elseif ( $use_minified ) {
				$variants[] = $filename . '.min.js';           // 1st preference: minified.
				$variants[] = $filename . '.js';               // 2nd preference: non-minified.
		} else {
			$variants[] = $filename . '.js';               // 1st preference: non-minified.
		}
		if ( 'css' === $type && $is_rtl ) {
			$actual_type     = 'css-rtl';
			$actual_base_dir = plugin_dir_path( __FILE__ ) . 'css-rtl/';
		}

		// Check each variant in order.
		foreach ( $variants as $variant ) {
			if ( file_exists( $actual_base_dir . $variant ) ) {
				return $actual_type . '/' . $variant;
			}
		}

		return false;
	}
}