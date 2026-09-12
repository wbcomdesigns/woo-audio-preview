<?php
/**
 * Helpers shared between the admin and public sibling classes.
 *
 * Wc_Audio_Preview_Admin and Wc_Audio_Preview_Public are independent classes
 * (only Public is subclassed by the Pro add-on), so logic they both need lives
 * here instead of being re-authored in each. Because __FILE__ inside a trait
 * resolves to THIS file, callers pass their own directory to get_asset_filename().
 *
 * @link       http://wbcomdesigns.com
 * @since      1.7.0
 *
 * @package    Wc_Audio_Preview
 * @subpackage Wc_Audio_Preview/includes
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shared helpers for the admin and public classes.
 *
 * @package    Wc_Audio_Preview
 * @subpackage Wc_Audio_Preview/includes
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
trait Wc_Audio_Preview_Shared {

	/**
	 * Get asset filename with intelligent fallback.
	 *
	 * @since    1.6.0
	 * @param    string      $type      Asset type ('css' or 'js').
	 * @param    string      $filename  Base filename without extension.
	 * @param    string|null $base_path Directory to resolve against. Defaults to this
	 *                                  trait file's dir; every in-repo caller passes its
	 *                                  own class dir, and the Pro subclass passes its own
	 *                                  so it finds its own assets.
	 * @return   string|false           Full filename with path or false if not found.
	 */
	protected function get_asset_filename( $type, $filename, $base_path = null ) {
		// Determine if we should use minified files.
		$use_minified = ! ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG );

		// Determine if RTL is needed (only for CSS).
		$is_rtl = ( 'css' === $type ) ? is_rtl() : false;

		// Resolve against the caller's directory when provided, else this file's own.
		$dir = $base_path ? trailingslashit( $base_path ) : plugin_dir_path( __FILE__ );

		// Build the base directory path.
		$base_dir        = $dir . $type . '/';
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
			$actual_base_dir = $dir . 'css-rtl/';
		}

		// Check each variant in order.
		foreach ( $variants as $variant ) {
			if ( file_exists( $actual_base_dir . $variant ) ) {
				return $actual_type . '/' . $variant;
			}
		}

		return false;
	}

	/**
	 * Google Drive file-ID URL patterns.
	 *
	 * Single source for the three sharing/download/open link shapes. Each captures
	 * the file ID in group 1. Shared by the admin CDN matcher and the public
	 * extractor + playback converter so the regex set is authored once.
	 *
	 * @since    1.7.0
	 * @return   string[] Array of PCRE patterns.
	 */
	protected static function wcap_google_drive_id_patterns() {
		return array(
			// Standard sharing link pattern (with or without /view and query params).
			'/drive\.google\.com\/file\/d\/([a-zA-Z0-9-_]+)(?:\/view)?(?:\?.*)?/i',
			// Direct download pattern.
			'/drive\.google\.com\/uc\?(?:.*&)?id=([a-zA-Z0-9-_]+)(?:&.*)?/i',
			// Open link pattern.
			'/drive\.google\.com\/open\?id=([a-zA-Z0-9-_]+)/i',
		);
	}
}
