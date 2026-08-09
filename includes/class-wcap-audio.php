<?php
/**
 * Canonical, shape-tolerant access to a product's audio previews.
 *
 * ONE reader for every shape this plugin pair has ever written, so that no store depends on a
 * migration having succeeded in order to keep its files. That inversion is the whole point:
 * with a tolerant reader, migration becomes an optimisation rather than the single thing
 * standing between an owner and their audio.
 *
 * The shapes, taken from the git history of both plugins rather than from the current code:
 *
 *   A. Plural (1.2.0 to now)  wcap_audio_urls[] + wcap_audio_names[]
 *                             Pro adds wcap_audio_durations[], _theme, _watermarks[]
 *   B. Singular (pre-1.2.0)   wcap_audio_url + wcap_audio_name
 *   C. Loose keys             any key containing 'url', paired with the matching 'name' key.
 *                             The Pro v2 migration's fallback branch, so rows in this shape
 *                             demonstrably exist in the wild.
 *   D. Bare string            the meta value is the URL itself
 *   E. List of rows           numerically indexed array of array( url, name )
 *
 * Two sub-keys that a naive cleanup would delete, and must not:
 *
 *   wcap_display_audio_players  a per-product toggle from 1.3.0-1.4.0. Nothing reads it any
 *                               more, but Pro's Dokan vendor dashboard STILL RENDERS the
 *                               checkbox, so vendors are setting it today and the value is
 *                               still being written.
 *   wcap_preview_attachment     the old uploaded-file mechanism, stored both as a sub-key
 *                               here and as its own post meta. Current code only ever writes
 *                               an empty string, but installs that predate that change hold
 *                               real values.
 *
 * @link       https://wbcomdesigns.com
 * @since      1.5.3
 *
 * @package    Woo_Audio_Preview
 * @subpackage Woo_Audio_Preview/includes
 */

defined( 'ABSPATH' ) || exit;

/**
 * Reads and writes a product's audio previews.
 *
 * @since 1.5.3
 */
class WCAP_Audio {

	/**
	 * Post meta key holding the audio previews.
	 *
	 * @since 1.5.3
	 * @var   string
	 */
	const META_KEY = 'wcap_audio';

	/**
	 * Canonical sub-keys written from 1.5.3 on.
	 *
	 * @since 1.5.3
	 * @var   string
	 */
	const URL_KEY       = 'wcap_audio_urls';
	const NAME_KEY      = 'wcap_audio_names';
	const DURATION_KEY  = 'wcap_audio_durations';
	const WATERMARK_KEY = 'wcap_audio_watermarks';

	/**
	 * A product's audio previews, normalised.
	 *
	 * @since  1.5.3
	 * @param  int $product_id Product ID.
	 * @return array List of array( 'name', 'url', 'duration', 'watermark' ). Empty when none.
	 */
	public static function get( $product_id ) {
		$raw = get_post_meta( (int) $product_id, self::META_KEY, true );

		if ( empty( $raw ) ) {
			return array();
		}

		$items = self::extract( $raw );

		/**
		 * Filter a product's audio previews after normalisation.
		 *
		 * @since 1.5.3
		 * @param array $items      List of array( 'name', 'url', 'duration', 'watermark' ).
		 * @param int   $product_id Product ID.
		 * @param mixed $raw        The stored value, for anything reading a shape not handled here.
		 */
		return apply_filters( 'wcap_product_audio', $items, (int) $product_id, $raw );
	}

	/**
	 * Pull audio entries out of whatever shape the value is in.
	 *
	 * @since  1.5.3
	 * @param  mixed $raw Stored meta value.
	 * @return array
	 */
	private static function extract( $raw ) {
		// Shape D: the meta value is the URL itself.
		if ( is_string( $raw ) ) {
			$raw = trim( $raw );

			return '' === $raw ? array() : array( self::row( $raw, '' ) );
		}

		if ( ! is_array( $raw ) ) {
			return array();
		}

		// Shape A: the plural arrays, current since 1.2.0.
		if ( isset( $raw[ self::URL_KEY ] ) && is_array( $raw[ self::URL_KEY ] ) ) {
			return self::from_parallel_arrays( $raw );
		}

		// Shape B: the singular pair, pre-1.2.0.
		if ( isset( $raw['wcap_audio_url'] ) ) {
			$name = isset( $raw['wcap_audio_name'] ) ? $raw['wcap_audio_name'] : '';

			return array( self::row( $raw['wcap_audio_url'], $name ) );
		}

		// Shape E: a list of rows.
		if ( isset( $raw[0] ) && is_array( $raw[0] ) ) {
			$items = array();

			foreach ( $raw as $entry ) {
				$url = '';

				foreach ( array( 'url', 'wcap_audio_url', 'audio_url', 'file' ) as $candidate ) {
					if ( ! empty( $entry[ $candidate ] ) ) {
						$url = $entry[ $candidate ];
						break;
					}
				}

				if ( '' === $url ) {
					continue;
				}

				$items[] = self::row(
					$url,
					isset( $entry['name'] ) ? $entry['name'] : '',
					isset( $entry['duration'] ) ? $entry['duration'] : null
				);
			}

			return $items;
		}

		// Shape C: any key containing 'url', paired with its matching 'name' key.
		return self::from_loose_keys( $raw );
	}

	/**
	 * Read the plural arrays, tolerating gaps between them.
	 *
	 * @since  1.5.3
	 * @param  array $raw Stored value.
	 * @return array
	 */
	private static function from_parallel_arrays( $raw ) {
		$urls       = (array) $raw[ self::URL_KEY ];
		$names      = isset( $raw[ self::NAME_KEY ] ) ? (array) $raw[ self::NAME_KEY ] : array();
		$durations  = isset( $raw[ self::DURATION_KEY ] ) ? (array) $raw[ self::DURATION_KEY ] : array();
		$watermarks = isset( $raw[ self::WATERMARK_KEY ] ) ? (array) $raw[ self::WATERMARK_KEY ] : array();

		$items = array();

		/*
		 * Driven by the URLS, not the names. The previous reader looped the NAMES and required
		 * both to be non-empty, so a file with a URL and a blank name was dropped - the audio
		 * was in the database and never played, and nothing said why. A file with no name gets
		 * one derived from its filename instead.
		 */
		foreach ( $urls as $index => $url ) {
			if ( '' === trim( (string) $url ) ) {
				continue;
			}

			$items[] = self::row(
				$url,
				isset( $names[ $index ] ) ? $names[ $index ] : '',
				isset( $durations[ $index ] ) ? $durations[ $index ] : null,
				isset( $watermarks[ $index ] ) ? $watermarks[ $index ] : null
			);
		}

		return $items;
	}

	/**
	 * Read arbitrary keys that contain 'url'.
	 *
	 * @since  1.5.3
	 * @param  array $raw Stored value.
	 * @return array
	 */
	private static function from_loose_keys( $raw ) {
		$items = array();

		foreach ( $raw as $key => $value ) {
			if ( ! is_string( $key ) || false === strpos( $key, 'url' ) || empty( $value ) ) {
				continue;
			}

			if ( is_array( $value ) ) {
				continue;
			}

			$name_key = str_replace( 'url', 'name', $key );
			$name     = isset( $raw[ $name_key ] ) && is_string( $raw[ $name_key ] ) ? $raw[ $name_key ] : '';

			$items[] = self::row( $value, $name );
		}

		return $items;
	}

	/**
	 * Build one normalised entry.
	 *
	 * @since  1.5.3
	 * @param  string $url       Audio URL.
	 * @param  string $name      Display name, may be empty.
	 * @param  mixed  $duration  Preview duration in seconds, or null.
	 * @param  mixed  $watermark Watermark configuration, or null.
	 * @return array
	 */
	private static function row( $url, $name, $duration = null, $watermark = null ) {
		$url  = trim( (string) $url );
		$name = trim( (string) $name );

		if ( '' === $name ) {
			// A file with no name still deserves a label rather than a blank button.
			$name = basename( strtok( $url, '?' ) );
		}

		return array(
			'name'      => $name,
			'url'       => $url,
			'duration'  => is_numeric( $duration ) ? (int) $duration : null,
			'watermark' => $watermark,
		);
	}

	/**
	 * Whether a product has any playable audio.
	 *
	 * @since  1.5.3
	 * @param  int $product_id Product ID.
	 * @return bool
	 */
	public static function has_any( $product_id ) {
		return array() !== self::get( $product_id );
	}

	/**
	 * The per-product "display audio players" toggle.
	 *
	 * Nothing has read this since 1.4.0, but Pro's Dokan vendor dashboard still renders the
	 * checkbox, so vendors set it today and the value is still written. Exposed here so the
	 * setting can be honoured again rather than deleted as dead - a control that silently does
	 * nothing is worse than no control.
	 *
	 * @since  1.5.3
	 * @param  int $product_id Product ID.
	 * @return bool Defaults to true when never set.
	 */
	public static function players_enabled( $product_id ) {
		$raw = get_post_meta( (int) $product_id, self::META_KEY, true );

		if ( ! is_array( $raw ) || ! isset( $raw['wcap_display_audio_players'] ) ) {
			return true;
		}

		return in_array( $raw['wcap_display_audio_players'], array( 'yes', '1', 1, true ), true );
	}

	/**
	 * Store a product's audio previews in the canonical shape.
	 *
	 * Preserves sub-keys this class does not own, so writing never destroys a value some other
	 * part of the pair still depends on.
	 *
	 * @since  1.5.3
	 * @param  int   $product_id Product ID.
	 * @param  array $items      List of array( 'name', 'url', 'duration', 'watermark' ).
	 * @return bool Whether the meta row changed.
	 */
	public static function save( $product_id, $items ) {
		$existing = get_post_meta( (int) $product_id, self::META_KEY, true );
		$existing = is_array( $existing ) ? $existing : array();

		$urls       = array();
		$names      = array();
		$durations  = array();
		$watermarks = array();

		foreach ( (array) $items as $item ) {
			if ( empty( $item['url'] ) ) {
				continue;
			}

			$urls[]       = $item['url'];
			$names[]      = isset( $item['name'] ) ? $item['name'] : '';
			$durations[]  = isset( $item['duration'] ) ? $item['duration'] : 0;
			$watermarks[] = isset( $item['watermark'] ) ? $item['watermark'] : '';
		}

		// Drop the shapes this class replaces, keep everything else.
		unset(
			$existing['wcap_audio_url'],
			$existing['wcap_audio_name'],
			$existing[ self::URL_KEY ],
			$existing[ self::NAME_KEY ],
			$existing[ self::DURATION_KEY ],
			$existing[ self::WATERMARK_KEY ]
		);

		if ( empty( $urls ) ) {
			return (bool) update_post_meta( (int) $product_id, self::META_KEY, $existing );
		}

		$existing[ self::URL_KEY ]       = $urls;
		$existing[ self::NAME_KEY ]      = $names;
		$existing[ self::DURATION_KEY ]  = $durations;
		$existing[ self::WATERMARK_KEY ] = $watermarks;

		return (bool) update_post_meta( (int) $product_id, self::META_KEY, $existing );
	}
}
