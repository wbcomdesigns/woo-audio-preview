<?php
/**
 * Placement: shortcode and block.
 *
 * The player is hooked onto a WooCommerce action, which is right for a normal product
 * template and useless the moment a theme does not fire that hook - block themes, page
 * builders, and any custom single-product layout. A store owner in that position had no
 * escape hatch at all: the preview simply never appeared, with nothing to configure.
 *
 * Both surfaces render through the same public renderer, so there is one implementation of
 * the markup and the block cannot drift from the hooked output.
 *
 * @link       https://wbcomdesigns.com
 * @since      1.5.4
 *
 * @package    Wc_Audio_Preview
 * @subpackage Wc_Audio_Preview/includes
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the shortcode and the block.
 *
 * @since 1.5.4
 */
class WCAP_Placement {

	/**
	 * Hook the shortcode and block registration.
	 *
	 * @since 1.5.4
	 */
	public static function init() {
		add_shortcode( 'audio_preview', array( __CLASS__, 'shortcode' ) );
		add_action( 'init', array( __CLASS__, 'register_block' ) );

		// A player rendered here needs the assets, wherever "here" is.
		add_filter( 'wcap_should_load_assets', array( __CLASS__, 'load_assets_for_placement' ) );
	}

	/**
	 * Render the preview for a product.
	 *
	 * @since  1.5.4
	 * @param  int $product_id Product to render for. Defaults to the current product.
	 * @return string Markup, or an empty string when there is nothing to show.
	 */
	public static function render( $product_id = 0 ) {
		if ( ! function_exists( 'wc_get_product' ) ) {
			return '';
		}

		$product_id = $product_id ? absint( $product_id ) : get_the_ID();

		if ( ! $product_id || ! wc_get_product( $product_id ) instanceof WC_Product ) {
			return '';
		}

		/*
		 * The renderer reads the global $product, exactly as it does on a product page.
		 * Swap it for the duration and put it back, so a shortcode inside a loop cannot
		 * leave the wrong product behind for whatever renders next.
		 */
		global $product;

		$previous = $product;
		$product  = wc_get_product( $product_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- Restored below.

		ob_start();

		/**
		 * Render the preview for the current global product.
		 *
		 * Whichever tier owns the renderer answers this, so manual placement cannot drift
		 * from what the WooCommerce hook produces.
		 *
		 * @since 1.5.4
		 * @param int $product_id Product being rendered.
		 */
		do_action( 'wcap_render_preview', $product_id );

		$output = ob_get_clean();

		$product = $previous; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- Restoring.

		return $output;
	}

	/**
	 * Whether a shortcode or block on this request needs the assets.
	 *
	 * @since  1.5.4
	 * @param  bool $load Whether the plugin already decided to load.
	 * @return bool
	 */
	public static function load_assets_for_placement( $load ) {
		if ( $load ) {
			return true;
		}

		$post = get_post();

		if ( ! $post instanceof WP_Post ) {
			return $load;
		}

		return has_shortcode( $post->post_content, 'audio_preview' )
			|| has_block( 'woo-audio-preview/preview', $post );
	}

	/**
	 * Register the block.
	 *
	 * Server-rendered on purpose: the markup lives in PHP already, so a JavaScript
	 * re-implementation would be a second copy of the output that could drift from the first.
	 *
	 * @since 1.5.4
	 */
	public static function register_block() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		register_block_type(
			'woo-audio-preview/preview',
			array(
				'api_version'     => 3,
				'title'           => __( 'Audio Preview', 'woo-audio-preview' ),
				'description'     => __( 'Plays the audio previews attached to a product, so shoppers can listen before they buy.', 'woo-audio-preview' ),
				'category'        => 'woocommerce',
				'icon'            => 'format-audio',
				'attributes'      => array(
					'productId' => array(
						'type'    => 'number',
						'default' => 0,
					),
				),
				'supports'        => array(
					'html'  => false,
					'align' => array( 'wide', 'full' ),
				),
				'render_callback' => array( __CLASS__, 'render_block' ),
			)
		);
	}

	/**
	 * Block render callback.
	 *
	 * @since  1.5.4
	 * @param  array $attributes Block attributes.
	 * @return string
	 */
	public static function render_block( $attributes ) {
		$product_id = isset( $attributes['productId'] ) ? absint( $attributes['productId'] ) : 0;

		return self::render( $product_id );
	}

	/**
	 * Shortcode handler.
	 *
	 * @since  1.5.4
	 * @param  array $atts Shortcode attributes.
	 * @return string
	 */
	public static function shortcode( $atts ) {
		$atts = shortcode_atts( array( 'product' => 0 ), $atts, 'audio_preview' );

		return self::render( (int) $atts['product'] );
	}
}
