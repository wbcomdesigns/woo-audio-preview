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
		$rtl_css = is_rtl() ? '-rtl' : '';

		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			$css_extension = '.css';

		} else {
			$css_extension = '.min.css';
		}
		if(is_rtl()){
			$css_extension = '.css';
		}
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css'.$rtl_css.'/wc-audio-preview-public'.$css_extension, array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			$js_extension = '.js';
		} else {
			$js_extension = '.min.js';
		}
		
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wc-audio-preview-public'.$js_extension, array( 'jquery' ), $this->version, false );
	}

	/**
	 * To display audio preview fields.
	 */
	public function wcap_add_preview_field() {
		global $post;

		$wcap_audio                  = get_post_meta( $post->ID, 'wcap_audio', true );
		if ( ! empty( $wcap_audio ) && isset($wcap_audio['wcap_audio_urls'])) {
			foreach ( $wcap_audio['wcap_audio_names'] as $key => $value ) {
				if ( ! empty( $value ) ) {
					$audio_url = $wcap_audio['wcap_audio_urls'][$key];
                	$mime_type = $this->get_audio_mime_type($audio_url);
					?>

				<div class='product_meta wcap-preview-btn-div' data-id="wcap-player-id-<?php echo esc_attr( $key ); ?>">
					<a class="wcap-preview-btn button" href="javascript:void(0)"><?php echo isset( $value) ? esc_html( $value ) : ''; ?></a>
				</div>
						
				<div class="wcap-player-cl" id="wcap-player-id-<?php echo esc_attr( $key ); ?>">
					<audio controls="controls" id="audio_player" preload="none" controlsList="nodownload">
						<source src="<?php echo esc_url($audio_url); ?>" type="<?php echo esc_attr($mime_type); ?>" />
						<?php esc_html_e( 'Your browser does not support the audio element.', 'wc-audio-preview' ); ?>
					</audio>
				</div>
							<?php
				}
			}
		}
		return true;
	}

	private function get_audio_mime_type($url) {
		$extension = strtolower(pathinfo($url, PATHINFO_EXTENSION));
		$mime_types = array(
			'mp3' => 'audio/mpeg',
			'wav' => 'audio/wav',
			'ogg' => 'audio/ogg',
			'm4a' => 'audio/mp4'
		);
		return isset($mime_types[$extension]) ? $mime_types[$extension] : 'audio/mpeg';
	}

}
