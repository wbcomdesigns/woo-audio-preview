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
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
class Wc_Audio_Preview_Admin {

	use Wc_Audio_Preview_Shared;

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
		// 'google_drive' is added in the constructor from the shared pattern set.
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

		// Google Drive patterns come from the shared helper so the regex set is authored once.
		$this->cdn_patterns['google_drive'] = self::wcap_google_drive_id_patterns();
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

			$css_file = $this->get_asset_filename( 'css', 'wc-audio-preview-admin', plugin_dir_path( __FILE__ ) );
			if ( $css_file ) {
				wp_enqueue_style(
					$this->plugin_name,
					plugin_dir_url( __FILE__ ) . $css_file,
					array(),
					$this->version,
					'all'
				);
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
			$js_file = $this->get_asset_filename( 'js', 'wc-audio-preview-admin', plugin_dir_path( __FILE__ ) );
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

		if ( isset( $_GET['page'] ) && in_array( sanitize_text_field( wp_unslash( $_GET['page'] ) ), array( 'wbcomplugins', 'woo-audio-preview-settings' ), true ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			// Remove non-critical notices only.
			remove_action( 'admin_notices', 'update_nag', 3 );
			remove_all_actions( 'admin_notices' );
			remove_all_actions( 'all_admin_notices' );

		}
	}


	/**
	 * Actions performed to create a submenu page content.
	 *
	 * @since    1.0.0
	 * @access public
	 */
	/**
	 * Put this screen on the shared Wbcom settings shell.
	 *
	 * The shell owns the menu entry, the sidebar, tab routing, assets and notice suppression, so
	 * this screen matches every other Wbcom plugin's admin. This plugin contributes nav entries and
	 * tab bodies through the shell's two seams, and the Pro add-on contributes its own tabs through
	 * the same seams - so free's tabs and Pro's are drawn by one screen with one look.
	 *
	 * @since 1.5.2
	 */
	public function boot_settings_page() {
		if ( ! class_exists( 'Wbcom_Settings_Page' ) ) {
			return;
		}

		Wbcom_Settings_Page::boot(
			array(
				'prefix'       => 'wcap',
				'slug'         => 'woo-audio-preview-settings',
				// Retired slugs (Pro's old standalone screen) redirect here instead of 404ing.
				'legacy_slugs' => array( 'wcap-pro-settings', 'wcap-settings' ),
				'assets_url'   => WCAP_PLUGIN_URI,
				'version'      => WCAP_TEXT_VERSION,
				'icon'         => 'audio-lines',
				'labels'       => array(
					'menu_title' => __( 'Audio Preview', 'woo-audio-preview' ),
					'brand'      => __( 'Audio Preview', 'woo-audio-preview' ),
					'subtitle'   => __( 'Audio previews for WooCommerce', 'woo-audio-preview' ),
					'nav_label'  => __( 'Audio Preview settings sections', 'woo-audio-preview' ),
					'pro_badge'  => __( 'Pro', 'woo-audio-preview' ),
				),
			)
		);

		// Priority 5 so free's core tabs (Welcome first) lead the nav and Pro's tabs follow.
		add_filter( 'wcap_settings_nav_groups', array( $this, 'settings_nav_groups' ), 5 );
		add_action( 'wcap_settings_tab_content', array( $this, 'render_settings_tab' ) );
	}

	/**
	 * Declare the settings nav.
	 *
	 * Built from the same tab list the old screen used, so the entries and their order are
	 * unchanged; only the chrome around them moved to the shared shell.
	 *
	 * @since  1.5.2
	 * @param  array $groups Groups declared so far.
	 * @return array
	 */
	public function settings_nav_groups( $groups ) {
		$icons = array(
			'woo-audio-preview-welcome' => 'layout-dashboard',
			'woo-audio-preview-pro'     => 'star',
			'woo-audio-preview-faq'     => 'help-circle',
		);

		$items = array();

		foreach ( (array) $this->plugin_settings_tabs as $tab_id => $label ) {
			$items[ $tab_id ] = array(
				'title' => $label,
				'icon'  => isset( $icons[ $tab_id ] ) ? $icons[ $tab_id ] : 'circle-dot',
			);
		}

		$groups['main'] = array(
			'label' => __( 'Audio Preview', 'woo-audio-preview' ),
			'items' => $items,
		);

		return $groups;
	}

	/**
	 * Render one settings tab.
	 *
	 * Each tab's body is still a registered settings section, so this hands off to the same
	 * callbacks the old screen used - including any an add-on registered.
	 *
	 * @since 1.5.2
	 * @param string $tab Current tab id.
	 */
	public function render_settings_tab( $tab ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		do_settings_sections( $tab );
	}

	/**
	 * Actions performed on loading plugin settings
	 *
	 * @since    1.0.9
	 * @access   public
	 * @author   Wbcom Designs
	 */
	public function wcap_init_plugin_settings() {
		$this->plugin_settings_tabs['woo-audio-preview-welcome'] = esc_html__( 'Welcome', 'woo-audio-preview' );
		register_setting(
			'woo_audio_preview_admin_welcome_options',
			'woo_audio_preview_admin_welcome_options',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		add_settings_section( 'woo-audio-preview-welcome', ' ', array( $this, 'wcap_admin_welcome_content' ), 'woo-audio-preview-welcome' );

		$this->plugin_settings_tabs['woo-audio-preview-pro'] = esc_html__( 'General (PRO)', 'woo-audio-preview' );
		add_settings_section( 'woo-audio-preview-general-pro', ' ', array( $this, 'wcap_general_pro' ), 'woo-audio-preview-pro' );

		$this->plugin_settings_tabs['woo-audio-preview-faq'] = esc_html__( 'FAQ', 'woo-audio-preview' );
		register_setting(
			'woo_audio_preview_general_options',
			'woo_audio_preview_general_options',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		add_settings_section( 'woo-audio-preview-faq', ' ', array( $this, 'wcap_general_options_content' ), 'woo-audio-preview-faq' );
	}

	/**
	 * Create the shared "WB Plugins" parent menu when no other suite plugin has.
	 *
	 * The shell hangs the settings page under this parent; whichever suite plugin loads first
	 * creates it, and the rest stand down.
	 *
	 * @since 1.5.2
	 */
	public function register_parent_menu() {
		if ( ! class_exists( 'Wbcom_Settings_Page' ) || ! empty( $GLOBALS['admin_page_hooks']['wbcomplugins'] ) ) {
			return;
		}

		add_menu_page(
			esc_html__( 'WB Plugins', 'woo-audio-preview' ),
			esc_html__( 'WB Plugins', 'woo-audio-preview' ),
			'manage_options',
			'wbcomplugins',
			array( 'Wbcom_Settings_Page', 'render_welcome' ),
			'dashicons-lightbulb',
			59
		);
	}

	/**
	 * Audio Preview for WooCommerce admin welcome tab content.
	 *
	 * @return void
	 */
	public function wcap_admin_welcome_content() {
		include plugin_dir_path( __DIR__ ) . 'admin/partials/woo-audio-preview-welcome-page.php';
	}

	/**
	 * Audio Preview for WooCommerce admin general tab content.
	 *
	 * @return void
	 */
	public function wcap_general_options_content() {
		include plugin_dir_path( __DIR__ ) . 'admin/partials/woo-audio-preview-faq.php';
	}

	/**
	 * Audio Preview for WooCommerce admin general pro tab content.
	 *
	 * @return void
	 */
	public function wcap_general_pro() {
		include plugin_dir_path( __DIR__ ) . 'admin/partials/woo-audio-preview-general-pro.php';
	}

	/*
	 * wcap_views_add_admin_settings() registered this plugin's own top-level menu and its
	 * hand-rolled settings page. Both are the shared Wbcom_Settings_Page shell's job now:
	 * register_parent_menu() creates the WB Plugins parent (if no suite plugin has yet) and the
	 * shell registers the settings submenu under it. See boot_settings_page().
	 */


	/**
	 * Update edit form enctype.
	 */
	public function wcap_update_edit_form() {
		echo ' enctype="multipart/form-data"';
	}

	/**
	 * Register enhanced meta box.
	 */
	public function wcap_register_meta_boxes() {
		global $post;
		$label_text = sprintf(
			/* translators: %s: Supported audio format information. */
			__( 'Audio Preview Items %s', 'woo-audio-preview' ),
			'<span class="wcap-required-span">' . __( '(Supports: MP3, WAV, OGG, M4A, AAC, FLAC, WMA, WEBM files. CDN and streaming service URLs supported.)', 'woo-audio-preview' ) . '</span>'
		);

		add_meta_box(
			'wc-preview-audio-mata-id',
			$label_text,
			array( $this, 'wcap_display_callback' ),
			'product'
		);
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

		$saved_names = ( is_array( $wcap_audio ) && isset( $wcap_audio['wcap_audio_names'] ) && is_array( $wcap_audio['wcap_audio_names'] ) )
			? $wcap_audio['wcap_audio_names']
			: array();

		/**
		 * How many preview rows the box renders.
		 *
		 * Free ships three. Pro raises this (and adds the row/theme/action fields through the
		 * hooks below) so a store is not capped at three - the seam Pro was written against but
		 * that this box never exposed.
		 *
		 * @since 1.5.3
		 * @param int $rows  Number of rows to render.
		 * @param int $saved Number of rows already saved.
		 */
		$max_rows = (int) apply_filters( 'wcap_metabox_max_rows', 3, count( $saved_names ) );
		if ( $max_rows < 1 ) {
			$max_rows = 3;
		}

		// Pro contributes a player-theme control above the rows.
		do_action( 'wcap_metabox_before_rows', $post );
		?>
		<div class="form-field preview_files">
			<div class="wcap-error-messages"></div>

			<!-- Enhanced help section -->
			<div class="wcap-help-section">
				<h4><?php esc_html_e( 'Audio Previews', 'woo-audio-preview' ); ?></h4>
				<p><?php esc_html_e( 'Add audio preview files for this product. Leave a row empty to skip it.', 'woo-audio-preview' ); ?></p>
				<div class="wcap-supported-formats">
					<strong><?php esc_html_e( 'Supported:', 'woo-audio-preview' ); ?></strong>
					<?php esc_html_e( 'MP3, WAV, OGG, M4A, AAC, FLAC, WMA, WEBM files • Direct URLs • CDN links (Google Drive, Dropbox, SoundCloud, etc.)', 'woo-audio-preview' ); ?>
				</div>
			</div>

			<table class="wcap-audio-fields wcap-fixed-audio-fields widefat"><!-- wcap-fixed-audio-fields: JS anchor for fixed-mode (media/clear) handlers -->
				<thead>
					<tr>
						<th class="wcap-col-name"><?php esc_html_e( 'Audio Name', 'woo-audio-preview' ); ?></th>
						<th class="wcap-col-url"><?php esc_html_e( 'Audio URL', 'woo-audio-preview' ); ?></th>
						<?php
						// Pro adds column headings here (e.g. Preview length) to sit over its row cells.
						do_action( 'wcap_metabox_row_headings' );
						?>
					</tr>
				</thead>
				<tbody>
					<?php
					for ( $i = 0; $i < $max_rows; $i++ ) :
						$audio_name = isset( $wcap_audio['wcap_audio_names'][ $i ] ) ? $wcap_audio['wcap_audio_names'][ $i ] : '';
						$audio_url  = isset( $wcap_audio['wcap_audio_urls'][ $i ] ) ? $wcap_audio['wcap_audio_urls'][ $i ] : '';
						?>
						<tr class="wcap-audio-field-row">
							<td class="wcap-col-name" data-label="<?php esc_attr_e( 'Audio Name', 'woo-audio-preview' ); ?>">
								<input type="text"
									id="wcap_audio_name_<?php echo esc_attr( $i ); ?>"
									class="wcap-audio-name widefat"
									name="wcap_audio[wcap_audio_names][]"
									value="<?php echo esc_attr( $audio_name ); ?>"
									<?php /* translators: %d: Track number. Placeholder must stay on one line - a leading newline makes the attribute invalid and the browser renders nothing. */ ?>
									placeholder="<?php echo esc_attr( sprintf( __( 'e.g., Track %d Preview', 'woo-audio-preview' ), $i + 1 ) ); ?>" />
							</td>
							<td class="wcap-col-url wcap-field-row" data-label="<?php esc_attr_e( 'Audio URL', 'woo-audio-preview' ); ?>"><!-- wcap-field-row: JS hook the admin script's closest() relies on -->
								<div class="wcap-url-input-group">
									<input type="url"
										id="wcap_audio_url_<?php echo esc_attr( $i ); ?>"
										class="wcap-audio-url widefat"
										name="wcap_audio[wcap_audio_urls][]"
										value="<?php echo esc_url( $audio_url ); ?>"
										placeholder="<?php esc_attr_e( 'https://example.com/audio.mp3 or CDN link', 'woo-audio-preview' ); ?>" />
									<button type="button" class="button wcap-media-button" data-field-index="<?php echo esc_attr( $i ); ?>">
										<?php esc_html_e( 'Media Library', 'woo-audio-preview' ); ?>
									</button>
									<?php if ( ! empty( $audio_url ) ) : ?>
										<button type="button" class="button wcap-clear-button" data-field-index="<?php echo esc_attr( $i ); ?>">
											<?php esc_html_e( 'Clear', 'woo-audio-preview' ); ?>
										</button>
									<?php endif; ?>
								</div>
								<?php
								if ( ! empty( $audio_url ) ) {
									$cdn_info = $this->is_cdn_url( $audio_url );
									if ( $cdn_info ) :
										?>
										<div class="wcap-service-indicator">
											<?php echo esc_html( ucfirst( str_replace( '_', ' ', $cdn_info['service'] ) ) ); ?> link detected
										</div>
										<?php
									endif;
								}
								?>
							</td>
							<?php
							// Pro adds its per-row cells here (e.g. the preview-length input).
							do_action( 'wcap_metabox_row_fields', $i, $audio_name, $audio_url );
							?>
						</tr>
					<?php endfor; ?>
				</tbody>
			</table>

			<?php
			// Pro adds the add-another / bulk-import controls below the rows.
			do_action( 'wcap_metabox_after_rows', $post );

			// The upsell only makes sense when Pro is NOT extending the box.
			if ( ! has_action( 'wcap_metabox_after_rows' ) ) :
				?>
				<div class="wcap-pro-notice">
					<p>
						<strong><?php esc_html_e( 'Need more than 3 audio previews?', 'woo-audio-preview' ); ?></strong><br>
						<?php
						printf(
							/* translators: %s: Pro version link. */
							esc_html__( 'Upgrade to %s for unlimited audio previews and dynamic add/remove functionality.', 'woo-audio-preview' ),
							'<a href="https://wbcomdesigns.com/downloads/woo-audio-preview-pro/" target="_blank">' . esc_html__( 'Pro Version', 'woo-audio-preview' ) . '</a>'
						);
						?>
					</p>
				</div>
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

			$has_valid_audio   = false;
			$validation_errors = array();
			// Original submitted-row index of each kept row, in kept order. $processed_audio is
			// compacted (empty/invalid rows dropped), so a consumer that reads per-row POST data
			// keyed by position - Pro's durations - needs this map to avoid shifting values onto
			// the wrong track.
			$kept_indices = array();

			if ( isset( $_POST['wcap_audio'] ) && is_array( $_POST['wcap_audio'] ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Values are sanitized individually below.
				$wcap_audio_raw = wp_unslash( $_POST['wcap_audio'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

				// Process every submitted row, not a fixed three - Pro raises the row count.
				$submitted_rows = isset( $wcap_audio_raw['wcap_audio_names'] ) && is_array( $wcap_audio_raw['wcap_audio_names'] )
					? count( $wcap_audio_raw['wcap_audio_names'] )
					: 0;

				for ( $i = 0; $i < $submitted_rows; $i++ ) {
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
							$kept_indices[]                         = $i;
							$has_valid_audio                        = true;
						} else {
							// Surface the rejection instead of silently dropping the row.
							$validation_errors[] = sprintf(
								/* translators: 1: row number, 2: audio track name, 3: reason the URL was rejected. */
								__( 'Audio row %1$d ("%2$s") was not saved: %3$s', 'woo-audio-preview' ),
								$i + 1,
								$audio_name,
								$validation['message']
							);
						}
					} elseif ( ! empty( $audio_url ) && empty( $audio_name ) ) {
						// A URL with no name is also dropped today; tell the owner why.
						$validation_errors[] = sprintf(
							/* translators: %d: row number. */
							__( 'Audio row %d was not saved: please add a name for the track.', 'woo-audio-preview' ),
							$i + 1
						);
					}
				}
			}

			// Save or delete meta. Merge onto the existing row so sub-keys this box does not
			// manage - Pro's per-track durations and player theme - survive a save from here.
			if ( $has_valid_audio ) {
				$existing = get_post_meta( $post_id, 'wcap_audio', true );
				$existing = is_array( $existing ) ? $existing : array();
				$processed_audio = array_merge( $existing, $processed_audio );
				update_post_meta( $post_id, 'wcap_audio', $processed_audio );
			} else {
				delete_post_meta( $post_id, 'wcap_audio' );
			}

			/**
			 * Fires after the box stores its names and URLs, so Pro can persist its own
			 * sub-keys (durations, theme) into the same meta row from the same request.
			 *
			 * @since 1.5.3
			 * @param int   $post_id         Product being saved.
			 * @param array $processed_audio Names and URLs just stored (compacted).
			 * @param array $kept_indices    Original submitted-row index of each kept row, in
			 *                               kept order. Lets a consumer read its own per-row POST
			 *                               values (e.g. durations) without shifting them when an
			 *                               earlier row was empty or failed validation.
			 */
			do_action( 'wcap_metabox_save', $post_id, $processed_audio, $kept_indices );

			// Persist any rejection messages so they surface on the next admin load
			// (wcap_display_admin_errors renders them on the product/settings screen).
			// Written directly (not via the WP_DEBUG-gated wcap_log_error) so owners
			// see them on production too.
			if ( ! empty( $validation_errors ) ) {
				$existing_errors = get_option( 'wcap_admin_errors', array() );
				if ( ! is_array( $existing_errors ) ) {
					$existing_errors = array();
				}
				$existing_errors = array_slice( array_merge( $existing_errors, $validation_errors ), -10 );
				update_option( 'wcap_admin_errors', $existing_errors, false );
			}
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
				wp_send_json_success( 'File deleted successfully' );
			} else {
				$this->wcap_log_error( 'Failed to delete file: ' . $uploaded_file );
				wp_send_json_error( 'Could not delete file' );
			}
		} else {
			// File doesn't exist or isn't writable; nothing left to remove.
			wp_send_json_success( 'File already removed' );
		}
		exit;
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

}