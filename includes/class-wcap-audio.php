<?php
/**
 * Audio preview data reader.
 *
 * The single place that turns the raw `wcap_audio` post meta into a normalised
 * list of playable entries. Both this plugin's public renderer and the Pro
 * add-on read through this class instead of re-implementing the meta shape, so
 * the two never drift apart on how stored audio is interpreted.
 *
 * @package Woo_Audio_Preview
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WCAP_Audio' ) ) {

	/**
	 * Reads and normalises stored audio previews for a product.
	 */
	class WCAP_Audio {

		/**
		 * Return the valid audio entries stored for a product.
		 *
		 * @param int $product_id Product ID.
		 * @return array List of entries, each { key, name, url }. Empty when none valid.
		 */
		public static function get( $product_id ) {
			$wcap_audio = get_post_meta( $product_id, 'wcap_audio', true );

			if ( empty( $wcap_audio )
				|| empty( $wcap_audio['wcap_audio_urls'] )
				|| empty( $wcap_audio['wcap_audio_names'] ) ) {
				return array();
			}

			$valid_audios = array();
			foreach ( (array) $wcap_audio['wcap_audio_names'] as $key => $value ) {
				if ( ! empty( $value ) && ! empty( $wcap_audio['wcap_audio_urls'][ $key ] ) ) {
					$valid_audios[] = array(
						'key'  => $key,
						'name' => $value,
						'url'  => $wcap_audio['wcap_audio_urls'][ $key ],
					);
				}
			}

			return $valid_audios;
		}
	}
}
