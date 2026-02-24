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

			$css_file = $this->get_asset_filename( 'css', 'woo-audio-preview-admin' );
			if ( $css_file ) {
				wp_enqueue_style(
					$this->plugin_name,
					plugin_dir_url( __FILE__ ) . $css_file,
					array(),
					$this->version,
					'all'
				);

				// Add enhanced styles for better UI.
				wp_add_inline_style( $this->plugin_name, $this->get_enhanced_styles() );
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
			$js_file = $this->get_asset_filename( 'js', 'woo-audio-preview-admin' );
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


	/**
	 * Actions performed to create a submenu page content.
	 *
	 * @since    1.0.0
	 * @access public
	 */
	public function wcap_admin_options_page() {
		global $allowedposttags;
		$tab = filter_input( INPUT_GET, 'tab' ) ? filter_input( INPUT_GET, 'tab' ) : 'woo-audio-preview-welcome';
		?>
	<div class="wrap">
		<div class="wbcom-bb-plugins-offer-wrapper">
				<div id="wb_admin_logo">
				</div>
			</div>
		<div class="wbcom-wrap wbcom-plugin-wrapper">
			<div class="bupr-header">
				<div class="wbcom_admin_header-wrapper">
					<div id="wb_admin_plugin_name">
						<?php esc_html_e( 'Audio Preview for WooCommerce', 'woo-audio-preview' ); ?>
						<span>
					<?php
					/* translators: %s: Plugin version number. */
					printf( esc_html__( 'Version %s', 'woo-audio-preview' ), esc_html( WCAP_TEXT_VERSION ) );
					?>
					</span>
					</div>
					<?php echo do_shortcode( '[wbcom_admin_setting_header]' ); ?>
				</div>
			</div>
			<div class="wbcom-admin-settings-page">
				<?php
				settings_errors();
				$this->wcap_plugin_settings_tabs();
				settings_fields( $tab );
				do_settings_sections( $tab );
				?>
			</div>
		</div>
	</div>
		<?php
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
	 * Actions performed to create tabs on the sub menu page.
	 */
	public function wcap_plugin_settings_tabs() {
		$current_tab = filter_input( INPUT_GET, 'tab' ) ? filter_input( INPUT_GET, 'tab' ) : 'woo-audio-preview-welcome';
		// Plugin settings tabs.
		echo '<div class="wbcom-tabs-section"><div class="nav-tab-wrapper"><div class="wb-responsive-menu"><span>' . esc_html( 'Menu' ) . '</span><input class="wb-toggle-btn" type="checkbox" id="wb-toggle-btn"><label class="wb-toggle-icon" for="wb-toggle-btn"><span class="wb-icon-bars"></span></label></div><ul>';
		foreach ( $this->plugin_settings_tabs as $tab_key => $tab_caption ) {
			$active = $current_tab === $tab_key ? 'nav-tab-active' : '';
			echo '<li class="' . esc_attr( $tab_key ) . '"><a class="nav-tab ' . esc_attr( $active ) . '" id="' . esc_attr( $tab_key ) . '-tab" href="?page=woo-audio-preview-settings&tab=' . esc_attr( $tab_key ) . '">' . esc_attr( $tab_caption ) . '</a></li>';
		}
		echo '</div></ul></div>';
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

	/**
	 * Actions performed on loading admin_menu.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @author   Wbcom Designs
	 */
	public function wcap_views_add_admin_settings() {
		if ( empty( $GLOBALS['admin_page_hooks']['wbcomplugins'] ) ) {
			add_menu_page( esc_html__( 'WB Plugins', 'woo-audio-preview' ), esc_html__( 'WB Plugins', 'woo-audio-preview' ), 'manage_options', 'wbcomplugins', array( $this, 'wcap_admin_options_page' ), 'dashicons-lightbulb', 59 );
			add_submenu_page( 'wbcomplugins', esc_html__( 'Welcome', 'woo-audio-preview' ), esc_html__( 'Welcome', 'woo-audio-preview' ), 'manage_options', 'wbcomplugins' );

		}
		add_submenu_page( 'wbcomplugins', esc_html__( 'Audio Preview for WooCommerce', 'woo-audio-preview' ), esc_html__( 'Audio Preview for WooCommerce', 'woo-audio-preview' ), 'manage_options', 'woo-audio-preview-settings', array( $this, 'wcap_admin_options_page' ) );
	}


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
		?>
		<div class="form-field preview_files">
			<div class="wcap-error-messages"></div>
			
			<!-- Enhanced help section -->
			<div class="wcap-help-section">
				<h4><?php esc_html_e( '🎵 Add Up to 3 Audio Previews', 'woo-audio-preview' ); ?></h4>
				<p><?php esc_html_e( 'You can add up to 3 audio preview files for this product. Leave fields empty if you need fewer previews.', 'woo-audio-preview' ); ?></p>
				<div class="wcap-supported-formats">
					<strong><?php esc_html_e( 'Supported:', 'woo-audio-preview' ); ?></strong>
					<?php esc_html_e( 'MP3, WAV, OGG, M4A, AAC, FLAC, WMA, WEBM files • Direct URLs • CDN links (Google Drive, Dropbox, SoundCloud, etc.)', 'woo-audio-preview' ); ?>
				</div>
			</div>
			
			<div class="wcap-fixed-audio-fields">
				<?php
				// Always show exactly 3 fields.
				for ( $i = 0; $i < 3; $i++ ) :
					$audio_name   = isset( $wcap_audio['wcap_audio_names'][ $i ] ) ? $wcap_audio['wcap_audio_names'][ $i ] : '';
					$audio_url    = isset( $wcap_audio['wcap_audio_urls'][ $i ] ) ? $wcap_audio['wcap_audio_urls'][ $i ] : '';
					$field_number = $i + 1;
					?>
					<div class="wcap-audio-field-group">
						<h4 class="wcap-field-title">
							<?php
							/* translators: %d: Audio preview field number. */
							echo esc_html( sprintf( __( 'Audio Preview %d', 'woo-audio-preview' ), $field_number ) );
							?>
							<?php if ( 0 === $i ) : ?>
								<span class="wcap-required"><?php esc_html_e( '(Primary)', 'woo-audio-preview' ); ?></span>
							<?php else : ?>
								<span class="wcap-optional"><?php esc_html_e( '(Optional)', 'woo-audio-preview' ); ?></span>
							<?php endif; ?>
						</h4>
						
						<div class="wcap-field-row">
							<label for="wcap_audio_name_<?php echo esc_attr( $i ); ?>">
								<?php esc_html_e( 'Audio Name:', 'woo-audio-preview' ); ?>
							</label>
							<input type="text" 
								id="wcap_audio_name_<?php echo esc_attr( $i ); ?>"
								class="wcap-audio-name widefat" 
								name="wcap_audio[wcap_audio_names][]" 
								value="<?php echo esc_attr( $audio_name ); ?>" 
								placeholder="
								<?php
								/* translators: %d: Track number. */
								echo esc_attr( sprintf( __( 'e.g., Track %d Preview', 'woo-audio-preview' ), $field_number ) );
								?>
							" />
						</div>
						
						<div class="wcap-field-row">
							<label for="wcap_audio_url_<?php echo esc_attr( $i ); ?>">
								<?php esc_html_e( 'Audio URL:', 'woo-audio-preview' ); ?>
							</label>
							<div class="wcap-url-input-group">
								<input type="url" 
									id="wcap_audio_url_<?php echo esc_attr( $i ); ?>"
									class="wcap-audio-url widefat" 
									name="wcap_audio[wcap_audio_urls][]" 
									value="<?php echo esc_url( $audio_url ); ?>" 
									placeholder="<?php esc_attr_e( 'https://example.com/audio.mp3 or CDN link', 'woo-audio-preview' ); ?>" />
								<button type="button" 
										class="button wcap-media-button" 
										data-field-index="<?php echo esc_attr( $i ); ?>">
									<?php esc_html_e( 'Media Library', 'woo-audio-preview' ); ?>
								</button>
								<?php if ( ! empty( $audio_url ) ) : ?>
									<button type="button" 
											class="button wcap-clear-button" 
											data-field-index="<?php echo esc_attr( $i ); ?>">
										<?php esc_html_e( 'Clear', 'woo-audio-preview' ); ?>
									</button>
								<?php endif; ?>
							</div>
							
							<?php
							// Show CDN indicator if URL is from a CDN.
							if ( ! empty( $audio_url ) ) {
								$cdn_info = $this->is_cdn_url( $audio_url );
								if ( $cdn_info ) :
									?>
									<div class="wcap-service-indicator">
										🔗 <?php echo esc_html( ucfirst( str_replace( '_', ' ', $cdn_info['service'] ) ) ); ?> link detected
									</div>
									<?php
								endif;
							}
							?>
						</div>
					</div>
				<?php endfor; ?>
			</div>
			
			<div class="wcap-pro-notice">
				<p>
					<strong><?php esc_html_e( '💎 Need more than 3 audio previews?', 'woo-audio-preview' ); ?></strong><br>
					<?php
					printf(
						/* translators: %s: Pro version link. */
						esc_html__( 'Upgrade to %s for unlimited audio previews and dynamic add/remove functionality.', 'woo-audio-preview' ),
						'<a href="https://wbcomdesigns.com/downloads/woo-audio-preview-pro/" target="_blank">' . esc_html__( 'Pro Version', 'woo-audio-preview' ) . '</a>'
					);
					?>
				</p>
			</div>
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

				// Process exactly 3 fields.
				for ( $i = 0; $i < 3; $i++ ) {
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

			// Save or delete meta.
			if ( $has_valid_audio ) {
				update_post_meta( $post_id, 'wcap_audio', $processed_audio );
			} else {
				delete_post_meta( $post_id, 'wcap_audio' );
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
	 * Get enhanced styles for the admin area
	 *
	 * @since    1.5.0
	 * @return   string    CSS styles.
	 */
	private function get_enhanced_styles() {
		return '
			.wcap-help-section {
				margin-bottom: 20px;
				padding: 15px;
				background: #f0f8ff;
				border-left: 4px solid #0073aa;
				border-radius: 4px;
			}
			
			.wcap-help-section h4 {
				margin: 0 0 10px 0;
				color: #0073aa;
			}
			
			.wcap-supported-grid {
				display: grid;
				grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
				gap: 15px;
				font-size: 13px;
			}
			
			.wcap-help-tip {
				margin: 10px 0 0 0;
				font-style: italic;
				color: #666;
			}
			
			.wcap-cdn-row {
				background-color: #f0f8ff;
			}
			
			.wcap-service-indicator {
				font-size: 11px;
				color: #0073aa;
				margin-top: 3px;
				font-style: italic;
			}
			
			.wcap-usage-examples {
				margin-top: 15px;
				padding: 12px;
				background: #f9f9f9;
				border-radius: 4px;
				font-size: 13px;
			}
			
			.wcap-usage-examples h4 {
				margin: 0 0 8px 0;
				color: #333;
			}
			
			.wcap-usage-examples ul {
				margin: 0;
				padding-left: 20px;
				color: #666;
			}
			
			.woo-audio-preview-table .wcap-media-button {
				white-space: nowrap;
				min-width: 100px;
			}
			
			.woo-audio-preview-table .sort {
				cursor: move;
				width: 20px;
				text-align: center;
				background: url("data:image/svg+xml;charset=UTF-8,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\'><path d=\'M9 3h6v2H9zm0 4h6v2H9zm0 4h6v2H9zm0 4h6v2H9z\' fill=\'%23666\'/></svg>") no-repeat center;
				background-size: 16px;
				opacity: 0.6;
				transition: opacity 0.3s ease;
			}
			
			.woo-audio-preview-table .sort:hover {
				opacity: 1;
			}
			
			/* Google Drive specific styling */
			.wcap-service-google_drive {
				background: #e8f5e9;
				border-color: #4caf50;
			}
			
			.wcap-service-google_drive .service-icon {
				color: #4caf50;
			}
		';
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