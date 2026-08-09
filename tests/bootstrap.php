<?php
/**
 * Test bootstrap.
 *
 * No WordPress test library: the reader under test is pure logic over a meta value, so
 * stubbing get_post_meta keeps the suite fast enough to run on every push.
 *
 * @package Woo_Audio_Preview
 */

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

$GLOBALS['wcap_test_meta']    = array();
$GLOBALS['wcap_test_filters'] = array();

if ( ! function_exists( 'get_post_meta' ) ) {
	/**
	 * Stub: read from the in-memory store the tests populate.
	 *
	 * @param int    $post_id Post ID.
	 * @param string $key     Meta key.
	 * @return mixed
	 */
	function get_post_meta( $post_id, $key ) {
		return isset( $GLOBALS['wcap_test_meta'][ $post_id ][ $key ] )
			? $GLOBALS['wcap_test_meta'][ $post_id ][ $key ]
			: '';
	}
}

if ( ! function_exists( 'update_post_meta' ) ) {
	/**
	 * Stub: write to the in-memory store.
	 *
	 * @param int    $post_id Post ID.
	 * @param string $key     Meta key.
	 * @param mixed  $value   Value.
	 * @return bool
	 */
	function update_post_meta( $post_id, $key, $value ) {
		$GLOBALS['wcap_test_meta'][ $post_id ][ $key ] = $value;
		return true;
	}
}

if ( ! function_exists( 'apply_filters' ) ) {
	/**
	 * Stub: pass the value through unchanged.
	 *
	 * @param string $hook  Hook name.
	 * @param mixed  $value Value.
	 * @return mixed
	 */
	function apply_filters( $hook, $value ) {
		return $value;
	}
}

require_once dirname( __DIR__ ) . '/includes/class-wcap-audio.php';
