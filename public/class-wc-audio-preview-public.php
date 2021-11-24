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
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wc-audio-preview-public.css', array(), $this->version, 'all' );
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
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wc-audio-preview-public.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * WCAP Display Audio Preview.
	 */
	public function wcap_add_preview_field() {
		global $post;

		$wcap_preview                = get_post_meta( $post->ID, 'wcap_preview_attachment', true );
		$wcap_audio                  = get_post_meta( $post->ID, 'wcap_audio', true );
		$wcap_audio_display_playlist = isset( $wcap_audio['wcap_display_audio_players'] ) ? $wcap_audio['wcap_display_audio_players'] : '';
		if ( ! empty( $wcap_preview ) && empty( $wcap_audio ) ) :
			?>
			<div class='product_meta wcap-preview-btn-div' data-id="wcap-player-id">
				<a class="wcap-preview-btn button" href="javascript:void(0)"><?php echo isset( $wcap_preview['name'] ) ? esc_attr( $wcap_preview['name'] ) : ''; ?></a>
			</div>
			<div class="wcap-player-cl" id="wcap-player-id">
				<audio controls="controls" id="audio_player" preload="auto" controlsList="nodownload">
					<source src="<?php echo isset( $wcap_preview['url'] ) ? esc_attr( $wcap_preview['url'] ) : ''; ?>" type="audio/mpeg" />
					<?php esc_html_e( 'Your browser does not support the audio element.', 'wc-audio-preview' ); ?>
				</audio>
			</div>
			<?php
			endif;

		if ( ! empty( $wcap_audio['wcap_audio_names'][0] ) && 'yes' == $wcap_audio_display_playlist ) {

			?>
				<audio controls="controls" id="audio_player" autoplay preload="auto" controlsList="nodownload">
					<?php esc_html_e( 'Your browser does not support the audio element.', 'wc-audio-preview' ); ?>
					<?php
						$wcap_count_number = 1;
					foreach ( $wcap_audio['wcap_audio_names'] as $key => $value ) {
						?>
						<source src="<?php echo isset( $wcap_audio['wcap_audio_urls'][ $key ] ) ? esc_attr( $wcap_audio['wcap_audio_urls'][ $key ] ) : ''; ?>" data-track-number="<?php esc_html_e( '0', 'woo-audio-preview-pro' ); ?><?php echo esc_attr( $wcap_count_number ); ?>">
					<?php $wcap_count_number++; } ?>
				</audio>
				<div class="wcap-audio-playlist">
				<?php
				$wcap_count_number = 1;
				foreach ( $wcap_audio['wcap_audio_names'] as $key => $value ) {
					?>
					<div class="wcap-audio-playlist-row" data-audio-row="<?php echo esc_attr( $wcap_count_number ); ?>">
						<div class="wcap-audio-small-toggle-btn">
							<span class="dashicons dashicons-controls-play" id="wcap_icon_select_audio" data-audio="<?php echo isset( $wcap_audio['wcap_audio_urls'][ $key ] ) ? esc_attr( $wcap_audio['wcap_audio_urls'][ $key ] ) : ''; ?>"></span></i>
						</div>
						<div class="wcap-audio-number"><?php echo wp_kses_post( $wcap_count_number ); ?></div>
						<div class="wcap-audio-title">
						<a href="javascript:void(0)" class="link" id="wcap_select_audio"  data-audio="<?php echo isset( $wcap_audio['wcap_audio_urls'][ $key ] ) ? esc_attr( $wcap_audio['wcap_audio_urls'][ $key ] ) : ''; ?>">
							<?php echo isset( $wcap_audio['wcap_audio_names'][ $key ] ) ? esc_attr( $wcap_audio['wcap_audio_names'][ $key ] ) : ''; ?>
						</a>
						</div>
					</div>
					<?php $wcap_count_number++; } ?>
				</div>
						<?php

		} elseif ( ! empty( $wcap_audio ) ) {
			foreach ( $wcap_audio['wcap_audio_names'] as $key => $value ) {
				if ( ! empty( $value ) ) {
					?>

				<div class='product_meta wcap-preview-btn-div' data-id="wcap-player-id-<?php echo esc_attr( $key ); ?>">
					<a class="wcap-preview-btn button" href="javascript:void(0)"><?php echo isset( $wcap_audio['wcap_audio_names'][ $key ] ) ? esc_attr( $wcap_audio['wcap_audio_names'][ $key ] ) : ''; ?></a>
				</div>
						<?php } ?>
				<div class="wcap-player-cl" id="wcap-player-id-<?php echo esc_attr( $key ); ?>">
					<audio controls="controls" id="audio_player" preload="auto" controlsList="nodownload">
						<source src="<?php echo isset( $wcap_audio['wcap_audio_urls'][ $key ] ) ? esc_attr( $wcap_audio['wcap_audio_urls'][ $key ] ) : ''; ?>" type="audio/mpeg" />
						<?php esc_html_e( 'Your browser does not support the audio element.', 'wc-audio-preview' ); ?>
					</audio>
				</div>
							<?php
			}
		}
		return true;
	}

}
