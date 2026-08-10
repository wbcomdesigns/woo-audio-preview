<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://wbcomdesigns.com
 * @since      1.0.0
 *
 * @package    Wc_Audio_Preview
 * @subpackage Wc_Audio_Preview/public
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wc_Audio_Preview
 * @subpackage Wc_Audio_Preview/public
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
/*
 * Helper methods here are `protected`, not `private`, on purpose.
 *
 * The Pro plugin's renderer extends this class, and a private helper is invisible to a subclass -
 * which is exactly how the Pro plugin ended up with its own copy of the CDN detection, the MIME
 * lookup, the Google Drive id parser and six other helpers. Ten near-identical implementations of
 * one idea, drifting apart with every fix applied to only one of them. Widening the visibility is
 * what lets that duplication be deleted rather than maintained.
 */
class Wc_Audio_Preview_Public {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	/**
	 * Whether this request will actually show a preview.
	 *
	 * Every asset this plugin ships - and WaveSurfer, which is not small - was loading on
	 * the home page, the shop, the cart and every other page of the store, for a feature
	 * that only appears on product pages. A store owner pays for that on every page view.
	 *
	 * Deliberately generous about what counts as a product page: page builders such as
	 * Elementor and Divi render product content without is_product() being true, so the
	 * global and a direct lookup are both consulted before giving up.
	 *
	 * @since  1.5.4
	 * @return bool
	 */
	protected function should_load_assets() {
		$load = false;

		if ( function_exists( 'is_product' ) && is_product() ) {
			$load = true;
		}

		if ( ! $load ) {
			global $product;
			if ( is_a( $product, 'WC_Product' ) ) {
				$load = true;
			}
		}

		/*
		 * Only on a singular view. On the shop archive get_the_ID() returns whichever
		 * product the loop is on, so this fallback answered "yes" for every archive and
		 * quietly undid the whole guard.
		 */
		if ( ! $load && is_singular() && function_exists( 'wc_get_product' ) ) {
			$load = wc_get_product( get_the_ID() ) instanceof WC_Product;
		}

		/**
		 * Filter whether the preview assets load on this request.
		 *
		 * The seam for anything that renders a player outside a product page - a shortcode
		 * in a page, a block in a template, or Pro's shop-page badges.
		 *
		 * @since 1.5.4
		 * @param bool $load Whether to enqueue.
		 */
		return (bool) apply_filters( 'wcap_should_load_assets', $load );
	}

	public function enqueue_styles() {
		if ( ! $this->should_load_assets() ) {
			return;
		}

		/*
		 * Fixed handles rather than $this->plugin_name.
		 *
		 * The Pro plugin's renderer extends this class, so both sets of assets are registered from
		 * ONE object - and $this->plugin_name is that object's name, which made both use the same
		 * handle. WordPress silently ignores the second registration of a handle, so whichever ran
		 * later simply never loaded. Distinct constant handles are what let both load.
		 */

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
		$css_file = $this->get_asset_filename( 'css', 'wcap-public' );
		if ( $css_file ) {
			wp_enqueue_style(
				'wcap-public',
				plugin_dir_url( __FILE__ ) . $css_file,
				array(),
				$this->version,
				'all'
			);
		}
	}

	/**
	 * Register third-party libraries both plugins share.
	 *
	 * Deliberately NOT part of enqueue_scripts(). The Pro plugin extends this class and replaces
	 * that method wholesale - it draws its own player, so it wants none of this plugin's
	 * presentation - and anything registered inside it is therefore lost whenever Pro is active.
	 * That is what made SoundCloud previews silently do nothing in Pro: it asked for a widget
	 * script that was never registered, and a missing handle raises no error.
	 *
	 * Registering is free when nothing enqueues the handle.
	 *
	 * @since 1.5.3
	 */
	public function register_shared_assets() {
		wp_register_script(
			'soundcloud-widget-api',
			plugin_dir_url( __FILE__ ) . 'js/soundcloud.min.js',
			array(),
			WCAP_TEXT_VERSION,
			true
		);
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		if ( ! $this->should_load_assets() ) {
			return;
		}

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

		// Build the JS filename with intelligent fallback.
		$js_file = $this->get_asset_filename( 'js', 'wcap-public' );

		if ( $js_file ) {
			wp_enqueue_script( 'soundcloud-widget-api' );
			wp_enqueue_script(
				'wcap-public',
				plugin_dir_url( __FILE__ ) . $js_file,
				array( 'jquery', 'soundcloud-widget-api' ),
				$this->version,
				true
			);

			// Localize script for better UX.
			wp_localize_script(
				'wcap-public',
				'wcap_public',
				array(
					'ajax_url'     => admin_url( 'admin-ajax.php' ),
					'nonce'        => wp_create_nonce( 'wcap-public-nonce' ),
					'loading_text' => __( 'Loading...', 'woo-audio-preview' ),
					'error_text'   => __( 'Error loading audio', 'woo-audio-preview' ),
					'play_text'    => __( 'Play', 'woo-audio-preview' ),
					'pause_text'   => __( 'Pause', 'woo-audio-preview' ),
				)
			);
		}
	}

	/**
	 * To display audio preview fields with modern UI.
	 */
	public function wcap_add_preview_field() {
		global $product;

		// WooCommerce-safe product retrieval — works with page builders and custom loops.
		if ( ! $product instanceof WC_Product ) {
			$product = wc_get_product( get_the_ID() );
		}
		if ( ! $product ) {
			return;
		}

		$product_id = $product->get_id();

		/*
		 * The raw stored value, passed to the two public hooks below.
		 *
		 * Those hooks documented a $wcap_audio parameter and passed a variable that no longer
		 * existed here - it was left behind when reading moved to WCAP_Audio. So every render
		 * emitted two "Undefined variable" warnings, and, worse, every listener on
		 * wcap_before_audio_preview / wcap_after_audio_preview received null where the
		 * documented payload should have been. The hooks are a public contract, so they get
		 * the value they promise rather than losing the parameter.
		 */
		$wcap_audio = get_post_meta( $product_id, WCAP_Audio::META_KEY, true );

		/*
		 * Read through WCAP_Audio, which understands every shape this plugin pair has ever
		 * written. The code this replaced accepted only the newest one and looped the NAMES
		 * array requiring both name and URL, so on a real store it silently dropped:
		 *
		 *   - every product from before 1.2.0 (the singular wcap_audio_url shape)
		 *   - every product whose keys did not match, which Pro's own v2 migration proves exist
		 *   - any product whose meta was a bare URL string
		 *   - any file uploaded without a name
		 *
		 * Measured against seeded products: four of six historical shapes returned zero files
		 * before this change and return them all after it.
		 */
		$valid_audios = WCAP_Audio::get( $product_id );

		if ( ! empty( $valid_audios ) ) {

			// Preserve the array shape the rest of this method and its filters expect.
			foreach ( $valid_audios as $wcap_index => $wcap_entry ) {
				$valid_audios[ $wcap_index ]['key'] = $wcap_index;
			}

			// Check if we have multiple audio files.
			$has_multiple = count( $valid_audios ) > 1;
			?>

			<?php
			/**
			 * Fires before the audio preview container is rendered.
			 *
			 * @since 1.5.1
			 *
			 * @param int   $product_id  The product ID.
			 * @param array $wcap_audio  The audio preview data.
			 * @param array $valid_audios Validated audio entries.
			 */
			do_action( 'wcap_before_audio_preview', $product_id, $wcap_audio, $valid_audios );
			?>

			<div class="wcap-audio-preview-container">
				<?php if ( $has_multiple ) : ?>
					<h3 class="wcap-preview-title">
						<svg class="wcap-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<path d="M9 18V5l12-2v13"></path>
							<circle cx="6" cy="18" r="3"></circle>
							<circle cx="18" cy="16" r="3"></circle>
						</svg>
						<?php esc_html_e( 'Audio Previews', 'woo-audio-preview' ); ?>
					</h3>
				<?php endif; ?>

				<div class="wcap-preview-list">
					<?php
					foreach ( $valid_audios as $audio_data ) {
						$key       = $audio_data['key'];
						$value     = $audio_data['name'];
						$audio_url = $audio_data['url'];

						// Determine if it's a CDN URL.
						$is_cdn = $this->wcap_is_cdn_url( $audio_url );

						// Check if we need iframe player.
						$needs_iframe = $this->wcap_needs_iframe_player( $audio_url );

						if ( $needs_iframe && $is_cdn && 'google_drive' === $is_cdn['service'] ) {
							// Use iframe for Google Drive.
							$this->render_google_drive_player( $key, $value, $audio_url );
						} elseif ( $needs_iframe && $is_cdn && 'soundcloud' === $is_cdn['service'] ) {
							// Use iframe for Sound Cloud.
								$this->render_sound_cloud_player( $key, $value, $audio_url );
						} else {
							// Use regular audio player.
							$this->render_audio_player( $key, $value, $audio_url, $is_cdn );
						}
					}
					?>
				</div>
			</div>

			<?php
			/**
			 * Fires after the audio preview container is rendered.
			 *
			 * @since 1.5.1
			 *
			 * @param int   $product_id  The product ID.
			 * @param array $wcap_audio  The audio preview data.
			 * @param array $valid_audios Validated audio entries.
			 */
			do_action( 'wcap_after_audio_preview', $product_id, $wcap_audio, $valid_audios );
			?>
			<?php
		}
		return true;
	}

	/**
	 * Render regular audio player.
	 *
	 * @param int    $key       Audio key.
	 * @param string $name      Audio name.
	 * @param string $audio_url Audio URL.
	 * @param array  $is_cdn    CDN info.
	 */
	protected function render_audio_player( $key, $name, $audio_url, $is_cdn ) {
		// Convert CDN URLs to playable format.
		$playable_url = $this->wcap_convert_cdn_url_for_playback( $audio_url );
		$mime_type    = $this->wcap_get_audio_mime_type( $playable_url );
		?>

		<div class="wcap-preview-item" data-audio-id="wcap-audio-<?php echo esc_attr( $key ); ?>">
			<button class="wcap-preview-button" type="button" aria-label="<?php /* translators: %s: Audio track name. */ echo esc_attr( sprintf( __( 'Play %s', 'woo-audio-preview' ), $name ) ); ?>">
				<div class="wcap-button-content">
					<span class="wcap-play-icon">
						<span class="wcap-icon-wrap">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
								<path d="M8 5v14l11-7z"/>
							</svg>
						</span>
					</span>
					<span class="wcap-pause-icon" style="display: none;">
						<span class="wcap-icon-wrap">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
								<path d="M6 4h4v16H6zM14 4h4v16h-4z"/>
							</svg>
						</span>
					</span>
					<span class="wcap-loading-spinner" style="display: none;">
						<span class="wcap-icon-wrap">
							<svg class="wcap-spinner" width="24" height="24" viewBox="0 0 24 24">
								<circle class="wcap-spinner-circle" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" fill="none"/>
							</svg>
						</span>
					</span>
					<div class="wcap-preview-info">
						<span class="wcap-preview-name"><?php echo esc_html( $name ); ?></span>
						<?php if ( $is_cdn ) : ?>
							<span class="wcap-preview-badge"><?php echo esc_html( $is_cdn['service_name'] ); ?></span>
						<?php endif; ?>
					</div>
				</div>
				<div class="wcap-progress-container" style="display: none;">
					<div class="wcap-progress-bar">
						<div class="wcap-progress-fill"></div>
					</div>
					<span class="wcap-time">0:00 / 0:00</span>
				</div>
			</button>

			<audio class="wcap-audio-element"
					id="wcap-audio-<?php echo esc_attr( $key ); ?>"
					preload="none"
					data-name="<?php echo esc_attr( $name ); ?>">
				<source src="<?php echo esc_url( $playable_url ); ?>" type="<?php echo esc_attr( $mime_type ); ?>" />
				<?php esc_html_e( 'Your browser does not support the audio element.', 'woo-audio-preview' ); ?>
			</audio>
		</div>

		<?php
	}

	/**
	 * Render Google Drive player with iframe.
	 *
	 * @param int    $key       Audio key.
	 * @param string $name      Audio name.
	 * @param string $audio_url Audio URL.
	 */
	protected function render_google_drive_player( $key, $name, $audio_url ) {
		// Extract Google Drive file ID.
		$file_id = $this->extract_google_drive_id( $audio_url );
		if ( ! $file_id ) {
			// Fallback to regular player if can't extract ID.
			$this->render_audio_player(
				$key,
				$name,
				$audio_url,
				array(
					'service'      => 'google_drive',
					'service_name' => 'Google Drive',
				)
			);
			return;
		}

		$iframe_url = 'https://drive.google.com/file/d/' . $file_id . '/preview';
		?>

		<div class="wcap-preview-item wcap-gdrive-item" data-audio-id="wcap-audio-<?php echo esc_attr( $key ); ?>" data-gdrive-key="<?php echo esc_attr( $key ); ?>">
			<button class="wcap-preview-button wcap-gdrive-button" type="button"
					aria-label="<?php /* translators: %s: Audio track name. */ echo esc_attr( sprintf( __( 'Play %s', 'woo-audio-preview' ), $name ) ); ?>">
				<div class="wcap-button-content">
					<span class="wcap-play-icon" id="wcap-play-<?php echo esc_attr( $key ); ?>">
						<span class="wcap-icon-wrap">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
								<path d="M8 5v14l11-7z"/>
							</svg>
						</span>
					</span>
					<span class="wcap-pause-icon" id="wcap-pause-<?php echo esc_attr( $key ); ?>" style="display: none;">
						<span class="wcap-icon-wrap">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
								<path d="M6 4h4v16H6zM14 4h4v16h-4z"/>
							</svg>
						</span>
					</span>
					<div class="wcap-preview-info">
						<span class="wcap-preview-name"><?php echo esc_html( $name ); ?></span>
						<span class="wcap-preview-badge">Google Drive</span>
					</div>
				</div>
			</button>
			<div class="wcap-gdrive-player" id="wcap-gdrive-<?php echo esc_attr( $key ); ?>" style="display: none;">
				<iframe
					src=""
					data-src="<?php echo esc_url( $iframe_url ); ?>"
					width="100%"
					height="80"
					frameborder="0"
					allow="autoplay"
					allowfullscreen>
				</iframe>
			</div>
		</div>

		<?php
	}

	/**
	 * Render Sound Cloud player with iframe.
	 *
	 * @param int    $key       Audio key.
	 * @param string $name      Audio name.
	 * @param string $audio_url Audio URL.
	 */
	protected function render_sound_cloud_player( $key, $name, $audio_url ) {

		$embed_url = 'https://w.soundcloud.com/player/?url=' . rawurlencode( $audio_url );

		/**
		 * Filter the SoundCloud embed URL.
		 *
		 * The seam Pro uses to add its player parameters. Pro used to override this whole
		 * method to change one string, and the copy had already drifted: it lost the button's
		 * aria-label and the data-soundcloud-key attribute this markup carries.
		 *
		 * @since 1.5.4
		 * @param string $embed_url The widget URL.
		 * @param string $audio_url The track URL being embedded.
		 * @param mixed  $key       Row key for this preview.
		 */
		$embed_url = apply_filters( 'wcap_soundcloud_embed_url', $embed_url, $audio_url, $key );

		?>
		<div class="wcap-preview-item wcap-soundcloud-item" data-audio-id="wcap-audio-<?php echo esc_attr( $key ); ?>" data-soundcloud-key="<?php echo esc_attr( $key ); ?>">
			<button class="wcap-preview-button wcap-soundcloud-button" type="button"
					aria-label="<?php /* translators: %s: Audio track name. */ echo esc_attr( sprintf( __( 'Play %s', 'woo-audio-preview' ), $name ) ); ?>">
				<div class="wcap-button-content">
					<span class="wcap-play-icon" id="wcap-play-<?php echo esc_attr( $key ); ?>">
						<span class="wcap-icon-wrap">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
								<path d="M8 5v14l11-7z"/>
							</svg>
						</span>
					</span>
					<span class="wcap-pause-icon" id="wcap-pause-<?php echo esc_attr( $key ); ?>" style="display: none;">
						<span class="wcap-icon-wrap">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
								<path d="M6 4h4v16H6zM14 4h4v16h-4z"/>
							</svg>
						</span>
					</span>
					<div class="wcap-preview-info">
						<span class="wcap-preview-name"><?php echo esc_html( $name ); ?></span>
						<span class="wcap-preview-badge">Sound Cloud </span>
					</div>
				</div>
			</button>
			<div class="wcap-soundcloud-player" id="wcap-soundcloud-<?php echo esc_attr( $key ); ?>" style="display: none;">
				<iframe
					src="<?php echo esc_url( $embed_url ); ?>"
					width="100%"
					height="100"
					frameborder="0"
					allow="autoplay"
					allowfullscreen>
				</iframe>
			</div>
		</div>

		<?php
	}

	/**
	 * Extract Google Drive file ID from URL.
	 *
	 * @param string $url Google Drive URL.
	 * @return string|false File ID or false.
	 */
	protected function extract_google_drive_id( $url ) {
		$patterns = array(
			'/drive\.google\.com\/file\/d\/([a-zA-Z0-9-_]+)(?:\/view)?(?:\?.*)?/i',
			'/drive\.google\.com\/uc\?(?:.*&)?id=([a-zA-Z0-9-_]+)(?:&.*)?/i',
			'/drive\.google\.com\/open\?id=([a-zA-Z0-9-_]+)/i',
		);

		foreach ( $patterns as $pattern ) {
			if ( preg_match( $pattern, $url, $matches ) ) {
				return $matches[1];
			}
		}

		return false;
	}

	/**
	 * Check if URL needs iframe player.
	 *
	 * @param string $url Audio URL.
	 * @return bool
	 */
	protected function wcap_needs_iframe_player( $url ) {
		// Currently only Google Drive and SoundCloud need iframe.
		return false !== strpos( $url, 'drive.google.com' ) || false !== strpos( $url, 'soundcloud.com' );
	}

	/**
	 * Convert CDN URLs to playable format.
	 *
	 * @param string $url The original URL.
	 * @return string The playable URL.
	 */
	protected function wcap_convert_cdn_url_for_playback( $url ) {
		if ( empty( $url ) ) {
			return $url;
		}

		// Google Drive conversion (for non-iframe fallback).
		$google_drive_patterns = array(
			'/drive\.google\.com\/file\/d\/([a-zA-Z0-9-_]+)(?:\/view)?(?:\?.*)?/i',
			'/drive\.google\.com\/uc\?(?:.*&)?id=([a-zA-Z0-9-_]+)(?:&.*)?/i',
			'/drive\.google\.com\/open\?id=([a-zA-Z0-9-_]+)/i',
		);

		foreach ( $google_drive_patterns as $pattern ) {
			if ( preg_match( $pattern, $url, $matches ) ) {
				$file_id = $matches[1];
				// Try direct download URL (may not work for all files).
				return 'https://drive.google.com/uc?export=download&id=' . $file_id;
			}
		}

		// Dropbox conversion.
		if ( false !== strpos( $url, 'dropbox.com' ) ) {
			// Convert sharing link to direct download link.
			$url = str_replace( '?dl=0', '?raw=1', $url );
			$url = str_replace( 'www.dropbox.com', 'dl.dropboxusercontent.com', $url );
		}

		// OneDrive conversion.
		if ( false !== strpos( $url, '1drv.ms' ) || false !== strpos( $url, 'onedrive.live.com' ) ) {
			// OneDrive direct download format.
			$url = str_replace( 'embed', 'download', $url );
		}

		// Box.com conversion.
		if ( false !== strpos( $url, 'box.com' ) ) {
			$url = str_replace( '/s/', '/shared/static/', $url );
		}

		return $url;
	}

	/**
	 * Check if URL is from a CDN service.
	 *
	 * @param string $url The URL to check.
	 * @return array|false Service info or false.
	 */
	protected function wcap_is_cdn_url( $url ) {
		if ( empty( $url ) ) {
			return false;
		}

		$services = array(
			'google_drive' => array(
				'name'     => 'Google Drive',
				'patterns' => array(
					'/drive\.google\.com/i',
				),
			),
			'dropbox'      => array(
				'name'     => 'Dropbox',
				'patterns' => array(
					'/dropbox\.com/i',
					'/dl\.dropboxusercontent\.com/i',
				),
			),
			'onedrive'     => array(
				'name'     => 'OneDrive',
				'patterns' => array(
					'/1drv\.ms/i',
					'/onedrive\.live\.com/i',
				),
			),
			'box'          => array(
				'name'     => 'Box',
				'patterns' => array(
					'/box\.com/i',
				),
			),
			'soundcloud'   => array(
				'name'     => 'SoundCloud',
				'patterns' => array(
					'/soundcloud\.com/i',
				),
			),
			's3'           => array(
				'name'     => 'Amazon S3',
				'patterns' => array(
					'/s3\.amazonaws\.com/i',
					'/\.s3\./i',
					'/\.s3-/i',
				),
			),
			'cloudfront'   => array(
				'name'     => 'CloudFront',
				'patterns' => array(
					'/cloudfront\.net/i',
				),
			),
			'mediafire'    => array(
				'name'     => 'MediaFire',
				'patterns' => array(
					'/mediafire\.com/i',
				),
			),
		);

		foreach ( $services as $service_key => $service ) {
			foreach ( $service['patterns'] as $pattern ) {
				if ( preg_match( $pattern, $url ) ) {
					return array(
						'service'      => $service_key,
						'service_name' => $service['name'],
					);
				}
			}
		}

		return false;
	}

	/**
	 * Get MIME type for audio file.
	 *
	 * @param string $url The audio URL.
	 * @return string The MIME type.
	 */
	protected function wcap_get_audio_mime_type( $url ) {
		// For CDN URLs where we can't determine extension.
		if ( false !== strpos( $url, 'drive.google.com' ) ||
			false !== strpos( $url, 'dropbox.com' ) ||
			false !== strpos( $url, 'soundcloud.com' ) ||
			false !== strpos( $url, '1drv.ms' ) ||
			false !== strpos( $url, 'box.com' ) ||
			false !== strpos( $url, 'mediafire.com' ) ) {
			return 'audio/mpeg'; // Default to MP3 as most compatible.
		}

		// Clean URL from query parameters.
		$clean_url = strtok( $url, '?' );
		$extension = strtolower( pathinfo( $clean_url, PATHINFO_EXTENSION ) );

		$mime_types = array(
			'mp3'  => 'audio/mpeg',
			'wav'  => 'audio/wav',
			'ogg'  => 'audio/ogg',
			'm4a'  => 'audio/mp4',
			'mp4'  => 'audio/mp4',
			'aac'  => 'audio/aac',
			'flac' => 'audio/flac',
			'wma'  => 'audio/x-ms-wma',
			'webm' => 'audio/webm',
			'opus' => 'audio/opus',
			'oga'  => 'audio/ogg',
		);

		/**
		 * Filter the extension-to-MIME map used for the <audio> source type.
		 *
		 * Add an entry here when teaching the player a new audio format, so the browser is
		 * told what it is being handed. Pair it with `wcap_allowed_audio_extensions`, which
		 * decides what the product box will accept in the first place.
		 *
		 * @since 1.5.4
		 * @param array $mime_types Extension => MIME type.
		 */
		$mime_types = apply_filters( 'wcap_audio_mime_types', $mime_types );
		return isset( $mime_types[ $extension ] ) ? $mime_types[ $extension ] : 'audio/mpeg';
	}

	/**
	 * Get asset filename with intelligent fallback.
	 *
	 * @since    1.6.0
	 * @param    string $type     Asset type ('css' or 'js').
	 * @param    string $filename Base filename without extension.
	 * @return   string|false     Full filename with path or false if not found.
	 */
	protected function get_asset_filename( $type, $filename, $base = '' ) {
		// Determine if we should use minified files.
		$use_minified = ! ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG );

		// Determine if RTL is needed (only for CSS).
		$is_rtl = ( 'css' === $type ) ? is_rtl() : false;

		// Build the base directory path.
		/*
		 * Which plugin's assets to search. Defaults to this one's - and a subclass MUST pass its
		 * own, because __FILE__ here is lexical and always points at this file. Resolving the
		 * subclass's assets against this directory is how a file present in free and absent in Pro
		 * was reported as found and then 404ed from Pro's URL.
		 */
		$base            = '' === $base ? plugin_dir_path( __FILE__ ) : trailingslashit( $base );
		$base_dir        = $base . $type . '/';
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
			$actual_base_dir = $base . 'css-rtl/';
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

// Google Drive and SoundCloud styles are now in the main CSS file.
