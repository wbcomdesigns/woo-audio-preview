<?php
/**
 * The settings page shell shared by Audio Preview and Audio Preview Pro.
 *
 * This file is IDENTICAL in both plugins, on purpose. Pro is a superset of free and replaces it,
 * so the two are never active together and neither can borrow the other's admin screen - yet a
 * store owner who upgrades must not be dropped into a different-looking product. Before this,
 * free drew a sidebar and Pro drew horizontal tabs, so upgrading changed the furniture as well as
 * the feature set.
 *
 * The shell owns the page: the menu entry, the sidebar, hash routing, assets and the save notice.
 * It owns no settings of its own. Each plugin contributes its tabs through two seams:
 *
 *   add_filter( 'wcap_settings_nav_groups', ... )   - declare the nav entries
 *   add_action( 'wcap_settings_tab_content', ... )  - render one tab's body
 *
 * That is what keeps free and Pro looking like one product: a tab added by either side goes
 * through the same shell and inherits the same spacing, card structure and heading treatment.
 * Neither side styles its own chrome.
 *
 * @link       https://wbcomdesigns.com
 * @since      1.5.3
 *
 * @package    Woo_Audio_Preview
 */

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WCAP_Settings_Page' ) ) {
	return;
}

/**
 * Pattern A settings page: sidebar left, content right, hash-routed.
 *
 * @since 1.5.3
 */
class WCAP_Settings_Page {

	/**
	 * Menu slug. Shared by both plugins so a bookmark survives an upgrade.
	 *
	 * @since 1.5.3
	 * @var   string
	 */
	const SLUG = 'woo-audio-preview-settings';

	/**
	 * Parent menu slug (the shared Wbcom menu).
	 *
	 * @since 1.5.3
	 * @var   string
	 */
	const PARENT = 'wbcomplugins';

	/**
	 * Slugs that used to render settings and now redirect here.
	 *
	 * Pro shipped its own top-level settings screen at wcap-pro-settings. Owners have that URL
	 * bookmarked and support articles point at it, so it redirects rather than 404s.
	 *
	 * @since 1.5.3
	 * @var   array
	 */
	const LEGACY_SLUGS = array( 'wcap-pro-settings' );

	/**
	 * Whether the page has already been registered this request.
	 *
	 * @since 1.5.3
	 * @var   bool
	 */
	private static $registered = false;

	/**
	 * URL of the plugin that booted the shell, for asset loading.
	 *
	 * @since 1.5.3
	 * @var   string
	 */
	private static $assets_url = '';

	/**
	 * Version of the plugin that booted the shell, for asset cache busting.
	 *
	 * @since 1.5.3
	 * @var   string
	 */
	private static $version = '';

	/**
	 * Start the shell.
	 *
	 * Safe to call from both plugins - the first caller wins and the second returns immediately,
	 * so two active copies cannot produce two menu entries.
	 *
	 * @since 1.5.3
	 * @param string $assets_url URL of the calling plugin's root, with trailing slash.
	 * @param string $version    Calling plugin's version.
	 */
	public static function boot( $assets_url, $version ) {
		if ( self::$registered ) {
			return;
		}

		self::$registered = true;
		self::$assets_url = trailingslashit( $assets_url );
		self::$version    = $version;

		add_action( 'admin_menu', array( __CLASS__, 'register_page' ), 20 );
		add_action( 'admin_init', array( __CLASS__, 'redirect_legacy_slugs' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue' ) );
	}

	/**
	 * Add the settings page under the shared Wbcom menu.
	 *
	 * @since 1.5.3
	 */
	public static function register_page() {
		add_submenu_page(
			self::PARENT,
			esc_html__( 'Audio Preview', 'woo-audio-preview' ),
			esc_html__( 'Audio Preview', 'woo-audio-preview' ),
			'manage_options',
			self::SLUG,
			array( __CLASS__, 'render' )
		);
	}

	/**
	 * Send retired settings URLs to the current one.
	 *
	 * @since 1.5.3
	 */
	public static function redirect_legacy_slugs() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Reading the page slug to route, nothing is modified.
		$page = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';

		if ( '' === $page || ! in_array( $page, self::LEGACY_SLUGS, true ) ) {
			return;
		}

		wp_safe_redirect( admin_url( 'admin.php?page=' . self::SLUG ) );
		exit;
	}

	/**
	 * The nav, as declared by whichever plugins are active.
	 *
	 * Shape: array of groups, each with a label and an array of items keyed by tab id:
	 *
	 *   array(
	 *       'setup' => array(
	 *           'label' => 'Setup',
	 *           'items' => array(
	 *               'general' => array( 'title' => 'General', 'icon' => 'settings', 'pro' => false ),
	 *           ),
	 *       ),
	 *   )
	 *
	 * @since  1.5.3
	 * @return array
	 */
	public static function nav_groups() {
		/**
		 * Filter the settings nav.
		 *
		 * This is the seam a plugin uses to add a tab. Free and Pro both go through it, which is
		 * what makes their tabs indistinguishable to the owner.
		 *
		 * @since 1.5.3
		 * @param array $groups Nav groups.
		 */
		$groups = apply_filters( 'wcap_settings_nav_groups', array() );

		return is_array( $groups ) ? $groups : array();
	}

	/**
	 * Every tab id in nav order.
	 *
	 * @since  1.5.3
	 * @return array
	 */
	private static function tab_ids() {
		$ids = array();

		foreach ( self::nav_groups() as $group ) {
			if ( ! empty( $group['items'] ) && is_array( $group['items'] ) ) {
				$ids = array_merge( $ids, array_keys( $group['items'] ) );
			}
		}

		return $ids;
	}

	/**
	 * Load the page's own assets, and nothing on any other screen.
	 *
	 * @since 1.5.3
	 * @param string $hook Current admin page hook.
	 */
	public static function enqueue( $hook ) {
		if ( false === strpos( (string) $hook, self::SLUG ) ) {
			return;
		}

		wp_enqueue_script( 'wcap-lucide', self::$assets_url . 'assets/vendor/lucide.min.js', array(), '0.460.0', true );
		wp_enqueue_style( 'wcap-settings-shell', self::$assets_url . 'admin/css/wcap-settings-shell.css', array(), self::$version );
		wp_style_add_data( 'wcap-settings-shell', 'rtl', 'replace' );
		wp_enqueue_script( 'wcap-settings-shell', self::$assets_url . 'admin/js/wcap-settings-shell.js', array( 'wcap-lucide' ), self::$version, true );
	}

	/**
	 * Render the page.
	 *
	 * @since 1.5.3
	 */
	public static function render() {
		$groups = self::nav_groups();
		$ids    = self::tab_ids();

		if ( empty( $ids ) ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Tab routing only.
		$requested = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : '';
		$current   = in_array( $requested, $ids, true ) ? $requested : $ids[0];
		?>
		<div class="wrap wcap-admin">
			<?php settings_errors(); ?>

			<div class="wcap-settings-wrap">
				<nav class="wcap-settings-sidebar" aria-label="<?php esc_attr_e( 'Settings sections', 'woo-audio-preview' ); ?>">
					<div class="wcap-settings-sidebar__brand">
						<span class="wcap-settings-sidebar__logo"><i data-lucide="audio-lines"></i></span>
						<div>
							<strong><?php esc_html_e( 'Audio Preview', 'woo-audio-preview' ); ?></strong>
							<span><?php esc_html_e( 'Settings', 'woo-audio-preview' ); ?></span>
						</div>
					</div>

					<?php foreach ( $groups as $group ) : ?>
						<?php
						if ( empty( $group['items'] ) ) {
							continue;
						}
						?>
						<div class="wcap-settings-nav-group">
							<?php if ( ! empty( $group['label'] ) ) : ?>
								<span class="wcap-settings-nav-group__label"><?php echo esc_html( $group['label'] ); ?></span>
							<?php endif; ?>

							<?php foreach ( $group['items'] as $tab_id => $item ) : ?>
								<a class="wcap-settings-nav-item<?php echo $tab_id === $current ? ' is-active' : ''; ?>"
									href="<?php echo esc_url( self::tab_url( $tab_id ) ); ?>"
									data-section="<?php echo esc_attr( $tab_id ); ?>"
									<?php echo $tab_id === $current ? 'aria-current="page"' : ''; ?>>
									<i data-lucide="<?php echo esc_attr( isset( $item['icon'] ) ? $item['icon'] : 'circle' ); ?>"></i>
									<span><?php echo esc_html( isset( $item['title'] ) ? $item['title'] : $tab_id ); ?></span>
									<?php if ( ! empty( $item['pro'] ) ) : ?>
										<span class="wcap-pro-badge"><?php esc_html_e( 'Pro', 'woo-audio-preview' ); ?></span>
									<?php endif; ?>
								</a>
							<?php endforeach; ?>
						</div>
					<?php endforeach; ?>
				</nav>

				<div class="wcap-settings-content">
					<?php foreach ( $ids as $tab_id ) : ?>
						<div class="wcap-settings-section<?php echo $tab_id === $current ? ' is-active' : ''; ?>" id="section-<?php echo esc_attr( $tab_id ); ?>">
							<div class="wcap-settings-body">
								<?php
								/**
								 * Render one settings tab.
								 *
								 * Renderers output bare sections - the shell supplies the padding, the card
								 * chrome and the heading structure, so a tab from Pro cannot end up spaced
								 * differently from a tab from free.
								 *
								 * @since 1.5.3
								 * @param string $tab_id Tab being rendered.
								 */
								do_action( 'wcap_settings_tab_content', $tab_id );
								?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * URL of one tab.
	 *
	 * A real URL rather than a bare fragment, so the tab survives a save, a refresh and a
	 * middle-click, and so the page still works with JavaScript unavailable.
	 *
	 * @since  1.5.3
	 * @param  string $tab_id Tab id.
	 * @return string
	 */
	public static function tab_url( $tab_id ) {
		return admin_url( 'admin.php?page=' . self::SLUG . '&tab=' . rawurlencode( $tab_id ) );
	}

	/**
	 * Open a settings card.
	 *
	 * Tab renderers call this instead of writing their own markup, which is what stops Pro's
	 * cards from drifting away from free's.
	 *
	 * @since 1.5.3
	 * @param string $title Card title.
	 * @param string $desc  Optional description.
	 */
	public static function card_open( $title, $desc = '' ) {
		?>
		<section class="wcap-card">
			<div class="wcap-card__head">
				<p class="wcap-card__title"><?php echo esc_html( $title ); ?></p>
				<?php if ( '' !== $desc ) : ?>
					<p class="wcap-card__desc"><?php echo esc_html( $desc ); ?></p>
				<?php endif; ?>
			</div>
			<div class="wcap-card__body">
		<?php
	}

	/**
	 * Close a settings card.
	 *
	 * @since 1.5.3
	 */
	public static function card_close() {
		echo '</div></section>';
	}
}
