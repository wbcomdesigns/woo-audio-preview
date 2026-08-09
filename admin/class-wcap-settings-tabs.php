<?php
/**
 * The free plugin's contribution to the shared settings page.
 *
 * Free owns three real tabs and shows the Pro tabs in place, badged and locked. That placement is
 * deliberate: when the owner upgrades, those same entries become working tabs in the same
 * positions, so the product they bought looks like the product they were already using rather
 * than a different admin screen. Both plugins draw their nav through WCAP_Settings_Page, so
 * nothing here styles chrome.
 *
 * @link       https://wbcomdesigns.com
 * @since      1.5.3
 *
 * @package    Woo_Audio_Preview
 * @subpackage Woo_Audio_Preview/admin
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the free plugin's settings tabs.
 *
 * @since 1.5.3
 */
class WCAP_Settings_Tabs {

	/**
	 * Where the upgrade buttons point.
	 *
	 * @since 1.5.3
	 * @var   string
	 */
	const UPGRADE_URL = 'https://wbcomdesigns.com/downloads/woocommerce-audio-preview/';

	/**
	 * Hook into the shared settings page.
	 *
	 * @since 1.5.3
	 */
	public static function init() {
		add_filter( 'wcap_settings_nav_groups', array( __CLASS__, 'nav_groups' ) );
		add_action( 'wcap_settings_tab_content', array( __CLASS__, 'render_tab' ) );
	}

	/**
	 * Declare the nav.
	 *
	 * @since  1.5.3
	 * @param  array $groups Groups declared so far.
	 * @return array
	 */
	public static function nav_groups( $groups ) {
		$groups['start'] = array(
			'label' => __( 'Getting started', 'woo-audio-preview' ),
			'items' => array(
				'welcome' => array(
					'title' => __( 'Welcome', 'woo-audio-preview' ),
					'icon'  => 'rocket',
				),
				'faq'     => array(
					'title' => __( 'FAQ', 'woo-audio-preview' ),
					'icon'  => 'help-circle',
				),
			),
		);

		/*
		 * The Pro tabs are shown here only while Pro is absent. When it is installed it registers
		 * these same three ids with real settings behind them, through the same filter - so
		 * leaving the locked copies in place would put two nav entries and two sections under one
		 * id. The owner sees the same three entries either way; what changes is whether they open
		 * a settings screen or an explanation of what Pro adds.
		 */
		if ( self::pro_is_active() ) {
			return $groups;
		}

		$groups['upgrade'] = array(
			'label' => __( 'In Pro', 'woo-audio-preview' ),
			'items' => array(
				'player'    => array(
					'title' => __( 'Player', 'woo-audio-preview' ),
					'icon'  => 'sliders-horizontal',
					'pro'   => true,
				),
				'watermark' => array(
					'title' => __( 'Watermark', 'woo-audio-preview' ),
					'icon'  => 'shield',
					'pro'   => true,
				),
				'advanced'  => array(
					'title' => __( 'Advanced', 'woo-audio-preview' ),
					'icon'  => 'zap',
					'pro'   => true,
				),
			),
		);

		return $groups;
	}

	/**
	 * Whether the Pro plugin is present and supplying the premium tabs.
	 *
	 * Detected by the class that registers those tabs rather than by a version constant, because
	 * that class IS the thing whose presence matters here.
	 *
	 * @since  1.5.3
	 * @return bool
	 */
	private static function pro_is_active() {
		return class_exists( 'WCAP_Pro_Settings_Tabs' );
	}

	/**
	 * Render one tab.
	 *
	 * @since 1.5.3
	 * @param string $tab Tab id.
	 */
	public static function render_tab( $tab ) {
		if ( self::pro_is_active() && in_array( $tab, array( 'player', 'watermark', 'advanced' ), true ) ) {
			return;
		}

		switch ( $tab ) {
			case 'welcome':
				self::welcome();
				break;
			case 'faq':
				self::faq();
				break;
			case 'player':
				self::locked(
					__( 'Player', 'woo-audio-preview' ),
					__( 'Choose how the player looks and behaves for every product at once.', 'woo-audio-preview' ),
					array(
						__( 'Player themes and colours', 'woo-audio-preview' ),
						__( 'Autoplay, loop and default volume', 'woo-audio-preview' ),
						__( 'Waveform, time display and volume control', 'woo-audio-preview' ),
						__( 'Preview length limit, so a preview is never the whole track', 'woo-audio-preview' ),
					)
				);
				break;
			case 'watermark':
				self::locked(
					__( 'Watermark', 'woo-audio-preview' ),
					__( 'Play a sound over the preview so a downloaded copy is not sellable.', 'woo-audio-preview' ),
					array(
						__( 'Bundled beep, working the moment you switch it on', 'woo-audio-preview' ),
						__( 'Or your own audio file', 'woo-audio-preview' ),
						__( 'How often it plays, and how loud', 'woo-audio-preview' ),
						__( 'Dip the track underneath it', 'woo-audio-preview' ),
					)
				);
				break;
			case 'advanced':
				self::locked(
					__( 'Advanced', 'woo-audio-preview' ),
					__( 'Serve audio from a CDN and control what loads before playback.', 'woo-audio-preview' ),
					array(
						__( 'Audio from external domains and CDNs', 'woo-audio-preview' ),
						__( 'Preload strategy and lazy loading', 'woo-audio-preview' ),
						__( 'Download protection and right-click blocking', 'woo-audio-preview' ),
						__( 'Multi-vendor support for Dokan, WCFM and WC Vendors', 'woo-audio-preview' ),
					)
				);
				break;
		}
	}

	/**
	 * Getting-started tab.
	 *
	 * @since 1.5.3
	 */
	private static function welcome() {
		$steps = array(
			array(
				'title' => __( 'Edit a product', 'woo-audio-preview' ),
				'desc'  => __( 'Open any WooCommerce product you want to add previews to.', 'woo-audio-preview' ),
			),
			array(
				'title' => __( 'Find the Audio Preview box', 'woo-audio-preview' ),
				'desc'  => __( 'It sits below the product description, named "Audio Preview Items".', 'woo-audio-preview' ),
			),
			array(
				'title' => __( 'Add your audio', 'woo-audio-preview' ),
				'desc'  => __( 'Up to three previews per product. Upload from the Media Library or paste a URL - CDN links work.', 'woo-audio-preview' ),
			),
			array(
				'title' => __( 'Update the product', 'woo-audio-preview' ),
				'desc'  => __( 'The player appears on the product page straight away. Nothing else to configure.', 'woo-audio-preview' ),
			),
		);

		Wbcom_Settings_Page::card_open(
			__( 'Quick start', 'woo-audio-preview' ),
			__( 'Four steps from installed to a working preview on your store.', 'woo-audio-preview' )
		);
		?>
		<ol class="wbcom-steps">
			<?php foreach ( $steps as $i => $step ) : ?>
				<li class="wbcom-step">
					<span class="wbcom-step__num"><?php echo esc_html( $i + 1 ); ?></span>
					<div>
						<strong><?php echo esc_html( $step['title'] ); ?></strong>
						<p><?php echo esc_html( $step['desc'] ); ?></p>
					</div>
				</li>
			<?php endforeach; ?>
		</ol>
		<?php
		Wbcom_Settings_Page::card_close();

		Wbcom_Settings_Page::card_open(
			__( 'Supported audio', 'woo-audio-preview' ),
			__( 'What you can point a preview at.', 'woo-audio-preview' )
		);
		?>
		<p>
			<?php esc_html_e( 'MP3, WAV, OGG, M4A and AAC, uploaded to your Media Library or hosted anywhere reachable by URL - including Google Drive, Dropbox and any CDN.', 'woo-audio-preview' ); ?>
		</p>
		<?php
		Wbcom_Settings_Page::card_close();
	}

	/**
	 * FAQ tab.
	 *
	 * @since 1.5.3
	 */
	private static function faq() {
		$faqs = array(
			array(
				'q' => __( 'How many previews can I add per product?', 'woo-audio-preview' ),
				'a' => __( 'Three in the free plugin. Pro removes the limit and lets you add and remove rows as you go.', 'woo-audio-preview' ),
			),
			array(
				'q' => __( 'Can I use audio hosted somewhere other than my site?', 'woo-audio-preview' ),
				'a' => __( 'Yes. Paste any reachable URL, including Google Drive, Dropbox and CDN links.', 'woo-audio-preview' ),
			),
			array(
				'q' => __( 'Will shoppers be able to download the preview?', 'woo-audio-preview' ),
				'a' => __( 'The browser player can offer a download. Pro adds download protection, right-click blocking and an audio watermark so a copied preview is not worth selling.', 'woo-audio-preview' ),
			),
			array(
				'q' => __( 'Can I limit how much of a track plays?', 'woo-audio-preview' ),
				'a' => __( 'Pro adds a preview length limit, so shoppers hear a sample rather than the whole file.', 'woo-audio-preview' ),
			),
			array(
				'q' => __( 'Does it work on a multi-vendor marketplace?', 'woo-audio-preview' ),
				'a' => __( 'Pro supports Dokan, WCFM and WC Vendors, so vendors manage previews from their own dashboards.', 'woo-audio-preview' ),
			),
			array(
				'q' => __( 'Do I lose my audio if I upgrade to Pro?', 'woo-audio-preview' ),
				'a' => __( 'No. Pro reads the previews you have already saved. Nothing needs re-entering.', 'woo-audio-preview' ),
			),
		);

		Wbcom_Settings_Page::card_open(
			__( 'Frequently asked questions', 'woo-audio-preview' ),
			__( 'The things store owners ask most often.', 'woo-audio-preview' )
		);
		?>
		<div class="wbcom-faq">
			<?php foreach ( $faqs as $faq ) : ?>
				<details class="wbcom-faq__item">
					<summary><?php echo esc_html( $faq['q'] ); ?></summary>
					<p><?php echo esc_html( $faq['a'] ); ?></p>
				</details>
			<?php endforeach; ?>
		</div>
		<?php
		Wbcom_Settings_Page::card_close();
	}

	/**
	 * A Pro tab as seen from the free plugin.
	 *
	 * Shows what the tab does rather than an empty screen or a bare advert, so the owner can tell
	 * whether the upgrade solves their problem.
	 *
	 * @since 1.5.3
	 * @param string $title    Tab title.
	 * @param string $desc     What the tab is for.
	 * @param array  $features What Pro adds here.
	 */
	private static function locked( $title, $desc, $features ) {
		Wbcom_Settings_Page::card_open( $title, $desc );
		?>
		<ul class="wbcom-feature-list">
			<?php foreach ( $features as $feature ) : ?>
				<li><i data-lucide="check"></i><span><?php echo esc_html( $feature ); ?></span></li>
			<?php endforeach; ?>
		</ul>
		<p>
			<a class="wbcom-btn wbcom-btn--primary" href="<?php echo esc_url( self::UPGRADE_URL ); ?>" target="_blank" rel="noopener noreferrer">
				<i data-lucide="arrow-up-circle"></i>
				<?php esc_html_e( 'See what Pro adds', 'woo-audio-preview' ); ?>
			</a>
		</p>
		<?php
		Wbcom_Settings_Page::card_close();
	}
}
