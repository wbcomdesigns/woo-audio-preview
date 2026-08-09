<?php
/**
 * One test per shape this plugin pair has ever written to wcap_audio.
 *
 * Each is named for the release the shape comes from. A failure here means a store running
 * that release would lose its audio on update - which is the only thing these tests are for.
 *
 * @package Woo_Audio_Preview
 */

use PHPUnit\Framework\TestCase;

/**
 * Every historical storage shape must still be readable.
 */
class HistoricalShapesTest extends TestCase {

	/**
	 * Reset the in-memory meta store.
	 */
	protected function setUp(): void {
		parent::setUp();
		$GLOBALS['wcap_test_meta'] = array();
	}

	/**
	 * Stage a stored value for a product.
	 *
	 * @param int   $id    Product ID.
	 * @param mixed $value Stored meta value.
	 */
	private function store( $id, $value ) {
		$GLOBALS['wcap_test_meta'][ $id ] = array( 'wcap_audio' => $value );
	}

	/**
	 * Shape A - the plural arrays, written from 1.2.0 to today.
	 */
	public function test_plural_arrays_1_2_0_onwards() {
		$this->store(
			1,
			array(
				'wcap_audio_names' => array( 'Track one', 'Track two' ),
				'wcap_audio_urls'  => array( 'https://x.test/1.mp3', 'https://x.test/2.mp3' ),
			)
		);

		$items = WCAP_Audio::get( 1 );

		$this->assertCount( 2, $items );
		$this->assertSame( 'Track one', $items[0]['name'] );
		$this->assertSame( 'https://x.test/2.mp3', $items[1]['url'] );
	}

	/**
	 * Shape A with Pro's additions - durations, watermarks.
	 */
	public function test_plural_arrays_with_pro_fields() {
		$this->store(
			2,
			array(
				'wcap_audio_names'      => array( 'Sample' ),
				'wcap_audio_urls'       => array( 'https://x.test/a.mp3' ),
				'wcap_audio_durations'  => array( 30 ),
				'wcap_audio_watermarks' => array( 'beep' ),
				'wcap_audio_theme'      => 'classic',
			)
		);

		$items = WCAP_Audio::get( 2 );

		$this->assertSame( 30, $items[0]['duration'] );
		$this->assertSame( 'beep', $items[0]['watermark'] );
	}

	/**
	 * Shape B - the singular pair, pre-1.2.0.
	 *
	 * This is the shape Pro's v2 migration converts. A store whose migration was skipped -
	 * which the migration allows, because it sets its version flag whether or not each row
	 * converted - still holds this today.
	 */
	public function test_singular_pair_pre_1_2_0() {
		$this->store(
			3,
			array(
				'wcap_audio_url'  => 'https://x.test/old.mp3',
				'wcap_audio_name' => 'Old track',
			)
		);

		$items = WCAP_Audio::get( 3 );

		$this->assertCount( 1, $items, 'A pre-1.2.0 store must not lose its audio.' );
		$this->assertSame( 'Old track', $items[0]['name'] );
	}

	/**
	 * Shape C - arbitrary keys containing 'url'.
	 *
	 * The fallback branch in Pro's v2 migration, so rows like this demonstrably exist.
	 */
	public function test_loose_keys_containing_url() {
		$this->store(
			4,
			array(
				'audio_url_1'  => 'https://x.test/one.mp3',
				'audio_name_1' => 'First',
				'audio_url_2'  => 'https://x.test/two.mp3',
			)
		);

		$items = WCAP_Audio::get( 4 );

		$this->assertCount( 2, $items );
		$this->assertSame( 'First', $items[0]['name'] );
	}

	/**
	 * Shape D - the meta value is the URL itself.
	 */
	public function test_bare_string_url() {
		$this->store( 5, 'https://x.test/bare.mp3' );

		$items = WCAP_Audio::get( 5 );

		$this->assertCount( 1, $items );
		$this->assertSame( 'bare.mp3', $items[0]['name'], 'A file with no name still needs a label.' );
	}

	/**
	 * Shape E - a numerically indexed list of rows.
	 */
	public function test_list_of_rows() {
		$this->store(
			6,
			array(
				array(
					'url'  => 'https://x.test/r1.mp3',
					'name' => 'Row one',
				),
				array( 'url' => 'https://x.test/r2.mp3' ),
			)
		);

		$items = WCAP_Audio::get( 6 );

		$this->assertCount( 2, $items );
		$this->assertSame( 'Row one', $items[0]['name'] );
	}

	/**
	 * A file with a URL and no name must still play.
	 *
	 * The previous reader looped the NAMES array and required both to be non-empty, so this
	 * file sat in the database and never appeared - with nothing saying why.
	 */
	public function test_url_without_a_name_is_not_dropped() {
		$this->store(
			7,
			array(
				'wcap_audio_names' => array( '', 'Second' ),
				'wcap_audio_urls'  => array( 'https://x.test/unnamed.mp3', 'https://x.test/second.mp3' ),
			)
		);

		$items = WCAP_Audio::get( 7 );

		$this->assertCount( 2, $items, 'A file with no name must still be playable.' );
		$this->assertSame( 'unnamed.mp3', $items[0]['name'] );
	}

	/**
	 * A name with no URL is not a file.
	 */
	public function test_name_without_a_url_is_dropped() {
		$this->store(
			8,
			array(
				'wcap_audio_names' => array( 'Ghost', 'Real' ),
				'wcap_audio_urls'  => array( '', 'https://x.test/real.mp3' ),
			)
		);

		$items = WCAP_Audio::get( 8 );

		$this->assertCount( 1, $items );
		$this->assertSame( 'Real', $items[0]['name'] );
	}

	/**
	 * No meta, empty meta and junk all return an empty array rather than a warning.
	 */
	public function test_missing_or_unusable_meta_returns_an_array() {
		$this->assertSame( array(), WCAP_Audio::get( 999 ) );
		$this->store( 10, '' );
		$this->assertSame( array(), WCAP_Audio::get( 10 ) );
		$this->store( 11, 42 );
		$this->assertSame( array(), WCAP_Audio::get( 11 ) );
		$this->assertFalse( WCAP_Audio::has_any( 999 ) );
	}

	/**
	 * The per-product display toggle survives, because Dokan vendors still set it.
	 */
	public function test_display_toggle_is_readable_and_defaults_to_on() {
		$this->assertTrue( WCAP_Audio::players_enabled( 20 ), 'Never set means on.' );

		$this->store( 21, array( 'wcap_display_audio_players' => 'yes' ) );
		$this->assertTrue( WCAP_Audio::players_enabled( 21 ) );

		$this->store( 22, array( 'wcap_display_audio_players' => 'no' ) );
		$this->assertFalse( WCAP_Audio::players_enabled( 22 ) );
	}

	/**
	 * Saving normalises the shape without destroying sub-keys this class does not own.
	 */
	public function test_save_normalises_but_preserves_foreign_sub_keys() {
		$this->store(
			30,
			array(
				'wcap_audio_url'             => 'https://x.test/legacy.mp3',
				'wcap_display_audio_players' => 'no',
				'wcap_preview_attachment'    => array( 123 ),
			)
		);

		WCAP_Audio::save( 30, WCAP_Audio::get( 30 ) );

		$stored = $GLOBALS['wcap_test_meta'][30]['wcap_audio'];

		$this->assertArrayHasKey( 'wcap_audio_urls', $stored, 'Writes use the canonical shape.' );
		$this->assertArrayNotHasKey( 'wcap_audio_url', $stored, 'The superseded shape is removed.' );
		$this->assertSame( 'no', $stored['wcap_display_audio_players'], 'A vendor setting must survive a save.' );
		$this->assertSame( array( 123 ), $stored['wcap_preview_attachment'], 'Foreign sub-keys must survive a save.' );
	}

	/**
	 * Reading, saving and reading again returns the same files.
	 */
	public function test_round_trip_is_lossless_for_every_shape() {
		$shapes = array(
			array( 'wcap_audio_url' => 'https://x.test/a.mp3' ),
			array(
				'wcap_audio_urls'  => array( 'https://x.test/b.mp3' ),
				'wcap_audio_names' => array( 'B' ),
			),
			'https://x.test/c.mp3',
			array( 'some_url_key' => 'https://x.test/d.mp3' ),
		);

		foreach ( $shapes as $i => $shape ) {
			$id = 100 + $i;
			$this->store( $id, $shape );

			$before = WCAP_Audio::get( $id );
			WCAP_Audio::save( $id, $before );
			$after = WCAP_Audio::get( $id );

			$this->assertSame(
				wp_list_pluck_urls( $before ),
				wp_list_pluck_urls( $after ),
				"Shape {$i} lost files on a save/read round trip."
			);
		}
	}
}

/**
 * Pull the URLs out of a normalised list.
 *
 * @param array $items Normalised audio list.
 * @return array
 */
function wp_list_pluck_urls( $items ) {
	return array_map(
		static function ( $item ) {
			return $item['url'];
		},
		$items
	);
}
