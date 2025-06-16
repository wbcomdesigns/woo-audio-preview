<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://wbcomdesigns.com
 * @since      1.0.0
 *
 * @package    Wc_Audio_Preview
 * @subpackage Wc_Audio_Preview/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wc_Audio_Preview
 * @subpackage Wc_Audio_Preview/admin
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
class Wc_Audio_Preview_Admin {

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
	 * Plugin_settings_tabs
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var mixed    $plugin_settings_tabs  The Settings tab.
	 */
	public $plugin_settings_tabs;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
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
		$screen = get_current_screen();
		$rtl_css = is_rtl() ? '-rtl' : '';

		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			$css_extension = '.css';
		} else {
			$css_extension = '.min.css';
		}
		if(is_rtl()){
			$css_extension = '.css';
		}
		if (($screen->id === 'product' && ($screen->action === 'add' || $screen->action === '')) || (isset($_GET['page']) && $_GET['page'] === 'woo-audio-preview-settings')) {//phpcs:ignore
			
			wp_enqueue_style($this->plugin_name, plugin_dir_url( __FILE__ ) . 'css'. $rtl_css .'/wc-audio-preview-admin'.$css_extension, array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
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
		$screen = get_current_screen();
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			$js_extension = '.js';
		} else {
			$js_extension = '.min.js';
		}
		
		

		if (($screen->id === 'product' && ($screen->action === 'add' || $screen->action === '')) || (isset($_GET['page']) && $_GET['page'] === 'woo-audio-preview-settings')) { //phpcs:ignore
			
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wc-audio-preview-admin'.$js_extension, array( 'jquery','wp-i18n' ), $this->version, false );
			wp_localize_script(
			$this->plugin_name,
			'wcap_ajax_object',
				array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'nonce'    => wp_create_nonce( 'ajax-nonce' ),
				)
			);
		}
	}

	/**
	 * Action performed to hide all admin notices from setting page
	 *
	 * @return void
	 */
	public function wcap_hide_all_admin_notices_from_setting_page() {
		
		if (isset($_GET['page']) && in_array($_GET['page'], array('wbcomplugins', 'wbcom-plugins-page', 'wbcom-support-page', 'woo-audio-preview-settings'), true)) { //phpcs:ignore
        
			// Remove non-critical notices only
			remove_action('admin_notices', 'update_nag', 3);
			remove_all_actions( 'admin_notices' );
			remove_all_actions( 'all_admin_notices' );
			
		}
	}


	/**
	 * Actions performed to create a submenu page content.
	 *
	 * @since    1.0.0
	 * @access public
	 */
	public function wcap_admin_options_page() {
		global $allowedposttags;
		$tab = filter_input( INPUT_GET, 'tab' ) ? filter_input( INPUT_GET, 'tab' ) : 'woo-audio-preview-welcome';
		?>
	<div class="wrap">
		<div class="wbcom-bb-plugins-offer-wrapper">
				<div id="wb_admin_logo">
				</div>
			</div>
		<div class="wbcom-wrap wbcom-plugin-wrapper">
			<div class="bupr-header">
				<div class="wbcom_admin_header-wrapper">
					<div id="wb_admin_plugin_name">
						<?php esc_html_e( 'Audio Preview for WooCommerce', 'wc-audio-preview' ); ?>
						<span><?php printf( __( 'Version %s', 'wc-audio-preview' ), WCAP_TEXT_VERSION );//phpcs:ignore ?></span>
					</div>
					<?php echo do_shortcode( '[wbcom_admin_setting_header]' ); ?>
				</div>
			</div>
			<div class="wbcom-admin-settings-page">
				<?php
				settings_errors();
				$this->wcap_plugin_settings_tabs();
				settings_fields( $tab );
				do_settings_sections( $tab );
				?>
			</div>
		</div>
	</div>
		<?php
	}

	/**
	 * Actions performed on loading plugin settings
	 *
	 * @since    1.0.9
	 * @access   public
	 * @author   Wbcom Designs
	 */
	public function wcap_init_plugin_settings() {
		$this->plugin_settings_tabs['woo-audio-preview-welcome'] = esc_html__( 'Welcome', 'wc-audio-preview' );
		register_setting( 'woo_audio_preview_admin_welcome_options', 'woo_audio_preview_admin_welcome_options' );
		add_settings_section( 'woo-audio-preview-welcome', ' ', array( $this, 'wcap_admin_welcome_content' ), 'woo-audio-preview-welcome' );

		$this->plugin_settings_tabs['woo-audio-preview-pro'] = esc_html__( 'General (PRO)', 'wc-audio-preview' );
		add_settings_section( 'woo-audio-preview-general-pro', ' ', array( $this, 'wcap_general_pro' ), 'woo-audio-preview-pro' );

		$this->plugin_settings_tabs['woo-audio-preview-faq'] = esc_html__( 'FAQ', 'wc-audio-preview' );
		register_setting( 'woo_audio_preview_general_options', 'woo_audio_preview_general_options' );
		add_settings_section( 'woo-audio-preview-faq', ' ', array( $this, 'wcap_general_options_content' ), 'woo-audio-preview-faq' );

	}

	/**
	 * Actions performed to create tabs on the sub menu page.
	 */
	public function wcap_plugin_settings_tabs() {
		$current_tab = filter_input( INPUT_GET, 'tab' ) ? filter_input( INPUT_GET, 'tab' ) : 'woo-audio-preview-welcome';
		// xprofile setup tab.
		echo '<div class="wbcom-tabs-section"><div class="nav-tab-wrapper"><div class="wb-responsive-menu"><span>' . esc_html( 'Menu' ) . '</span><input class="wb-toggle-btn" type="checkbox" id="wb-toggle-btn"><label class="wb-toggle-icon" for="wb-toggle-btn"><span class="wb-icon-bars"></span></label></div><ul>';
		foreach ( $this->plugin_settings_tabs as $tab_key => $tab_caption ) {
			$active = $current_tab === $tab_key ? 'nav-tab-active' : '';
			echo '<li class="' . esc_attr( $tab_key ) . '"><a class="nav-tab ' . esc_attr( $active ) . '" id="' . esc_attr( $tab_key ) . '-tab" href="?page=woo-audio-preview-settings&tab=' . esc_attr( $tab_key ) . '">' . esc_attr( $tab_caption ) . '</a></li>';
		}
		echo '</div></ul></div>';
	}

	/**
	 * Audio Preview for WooCommerce admin welcome tab content.
	 *
	 * @return void
	 */
	public function wcap_admin_welcome_content() {
		include plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/woo-audio-preview-welcome-page.php';
	}

	/**
	 * Audio Preview for WooCommerce admin general tab content.
	 *
	 * @return void
	 */
	public function wcap_general_options_content() {
		include plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/woo-audio-preview-faq.php';
	}

	/**
	 * Audio Preview for WooCommerce admin general pro tab content.
	 *
	 * @return void
	 */
	public function wcap_general_pro() {
		include plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/woo-audio-preview-general-pro.php';
	}

	/**
	 * Actions performed on loading admin_menu.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @author   Wbcom Designs
	 */
	public function wcap_views_add_admin_settings() {
		if ( empty( $GLOBALS['admin_page_hooks']['wbcomplugins'] ) ) {
			add_menu_page( esc_html__( 'WB Plugins', 'wc-audio-preview' ), esc_html__( 'WB Plugins', 'wc-audio-preview' ), 'manage_options', 'wbcomplugins', array( $this, 'wcap_admin_options_page' ), 'dashicons-lightbulb', 59 );
			add_submenu_page( 'wbcomplugins', esc_html__( 'Welcome', 'wc-audio-preview' ), esc_html__( 'Welcome', 'wc-audio-preview' ), 'manage_options', 'wbcomplugins' );

		}
		add_submenu_page( 'wbcomplugins', esc_html__( 'Audio Preview for WooCommerce', 'wc-audio-preview' ), esc_html__( 'Audio Preview for WooCommerce', 'wc-audio-preview' ), 'manage_options', 'woo-audio-preview-settings', array( $this, 'wcap_admin_options_page' ) );
	}


	/**
	 * Update edit form enctype.
	 */
	public function wcap_update_edit_form() {
		echo ' enctype="multipart/form-data"';
	}

	/**
	 * Register meta box(es).
	 */
	public function wcap_register_meta_boxes() {
		global $post;
		$label_text = sprintf(
			__( 'Audio Preview Item %s', 'wc-audio-preview' ),
			'<span class="wcap-required-span">' . __( '( MP3, WAV, OGG, M4A allowed. External URLs supported. )', 'wc-audio-preview' ) . '</span>'
		);

		add_meta_box(
			'wc-preview-audio-mata-id',
			$label_text,
			array( $this, 'wcap_display_callback' ),
			'product'
		);

	}

	/**
	 * Meta box display callback.
	 *
	 * @param WP_Post $post Current post object.
	 */
	public function wcap_display_callback( $post ) {
		// Add nonce for security and authentication.
		wp_nonce_field( 'wcap_nonce_action', 'wcap_nonce' );

		$wcap_audio = get_post_meta( $post->ID, 'wcap_audio', true );
		?>
		<div class="form-field preview_files">
			<table class="widefat woo-audio-preview-table" id="wcap-audio-table">
				<thead>
					<tr>
						<th class="sort">&nbsp;</th>
						<th><?php esc_attr_e( 'Name', 'wc-audio-preview' ); ?> <span class="woocommerce-help-tip"><span class="tooltiptext">Enter the Title of audio file</span>
					</span></th>
						<th colspan="2"><?php esc_attr_e( 'Audio URL', 'wc-audio-preview' ); ?> <span class="woocommerce-help-tip"><span class="tooltiptext">Enter the path to the audio file</span>
					</span></th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody class="ui-sortable wcap_preview-tr">
				<p class="wcap-del-msg"><?php esc_attr_e( 'Error', 'wc-audio-preview' ); ?></p>
				<?php
				$preview_data = get_post_meta( $post->ID, 'wcap_preview_attachment', true );

				?>
				<?php if ( ! empty( $wcap_audio ) ) : ?>
						<?php foreach ( $wcap_audio['wcap_audio_names'] as $key => $value ) : ?>
							<tr class="wcap-audio-file">
								<td class="sort"></td>
								<td class="file_name"><input class="input_text" placeholder="Audio Name" name="wcap_audio[wcap_audio_names][]" value="<?php echo isset( $wcap_audio['wcap_audio_names'][ $key ] ) ? esc_attr( $wcap_audio['wcap_audio_names'][ $key ] ) : ''; ?>" type="text" ></td>
								<td class="file_url"><input class="input_text" placeholder="http://" id="wcap_audio_urls" name="wcap_audio[wcap_audio_urls][]" value="<?php echo isset( $wcap_audio['wcap_audio_urls'][ $key ] ) ? esc_attr( $wcap_audio['wcap_audio_urls'][ $key ] ) : ''; ?>" type="text"></td>
								<td class="file_url_choose" width="1%"><input type="file" id="wcap_preview_attachment" name="wcap_audio[wcap_preview_attachment][]" value="<?php echo isset( $preview_data['file'] ) ? esc_attr( $preview_data['file'] ) : ''; ?>" size="25"/></td>
								<td width="15%">
								<a href="javascript:void(0)"  class="tooltip wcap-add-audio-cl button button-primary button-small"><?php esc_html_e( 'Add', 'wc-audio-preview' ); ?><span class="tooltiptext">Add a new audio file</span></a>&nbsp;
								<?php if ( count( $wcap_audio['wcap_audio_names'] ) > 1 ) : ?>
								<a href="javascript:void(0)" data-p_id="<?php echo esc_attr( $post->ID ); ?>" data-file="<?php echo isset( $preview_data['file'] ) ? esc_attr( $preview_data['file'] ) : ''; ?>"class="tooltip wcap-delete-audio-cl button button-primary button-small" id="wcap-delete-audio-id"><?php esc_html_e( 'Remove', 'wc-audio-preview' ); ?><span class="tooltiptext">Remove this audio file</span></a></td>
								<?php endif; ?>
							</tr>
						<?php endforeach; ?>
					<?php else : ?>
						<tr class="wcap-audio-file">
							<td class="sort"></td>
							<td class="file_name"><input class="input_text" placeholder="Audio Name" name="wcap_audio[wcap_audio_names][]" value="<?php echo isset( $preview_data['name'] ) ? esc_attr( $preview_data['name'] ) : ''; ?>" type="text" ></td>
							<td class="file_url"><input class="input_text" placeholder="http://" id="wcap_audio_urls" name="wcap_audio[wcap_audio_urls][]" value="<?php echo isset( $preview_data['url'] ) ? esc_attr( $preview_data['url'] ) : ''; ?>" type="text"></td>
							<td class="file_url_choose" width="1%"><input type="file" id="wcap_preview_attachment" name="wcap_audio[wcap_preview_attachment][]" value="<?php echo isset( $preview_data['file'] ) ? esc_attr( $preview_data['file'] ) : ''; ?>" size="25"/></td>
							<td width="15%">
							<a href="javascript:void(0)" class="tooltip wcap-add-audio-cl button button-primary button-small"><?php esc_html_e( 'Add', 'wc-audio-preview' ); ?><span class="tooltiptext">Add a new audio file</span></a>&nbsp;
						</tr>
					<?php endif; ?>
					</tbody>
				</table>
		</div>
		<?php
	}

	/**
	 * Save meta box content.
	 *
	 * @param int $post_id Post ID Get a Post ID.
	 */
	public function wcap_save_meta_box( $post_id ) {
		// Add nonce for security and authentication.
		$nonce_name   = isset( $_POST['wcap_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['wcap_nonce'] ) ) : '';
		$nonce_action = 'wcap_nonce_action';
		// Check if nonce is set.
		if ( ! isset( $nonce_name ) ) {
			return;
		}

		// Check if nonce is valid.
		if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
			return;
		}

		// Check if user has permissions to save data.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Check if not an autosave.
		if ( wp_is_post_autosave( $post_id ) ) {
			return;
		}
		// For admin settings:
		if (!current_user_can('manage_woocommerce')) {
			wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'wc-audio-preview'));
		}

		// Check if not a revision.
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		if ( isset( $_POST['post_type'] ) && 'product' === $_POST['post_type'] ) {
			if ( isset( $_POST['wcap_audio'] ) && ! empty( $_POST['wcap_audio'] ) ) {
				if ( isset( $_FILES['wcap_audio']['name'] ) && ! empty( $_FILES['wcap_audio']['name'] ) ) {
					$audio_data = array();
					$supported_types = array('mp3', 'wav', 'ogg', 'm4a');
					$supported_mimes = array(
						'mp3' => array('audio/mpeg', 'audio/mp3', 'audio/mpeg3', 'audio/x-mpeg-3'),
						'wav' => array('audio/wav', 'audio/x-wav', 'audio/wave'),
						'ogg' => array('audio/ogg', 'application/ogg'),
						'm4a' => array('audio/mp4', 'audio/x-m4a')
					);
					$wcap_upload_audio = map_deep( wp_unslash( $_FILES['wcap_audio'] ), 'sanitize_text_field' );
					foreach ( $wcap_upload_audio['name']['wcap_preview_attachment'] as $key => $value ) {

						if ( empty( $value ) || empty( $wcap_upload_audio['tmp_name']['wcap_preview_attachment'][$key] ) ) {
							continue;
						}

						$uploadedfile = array(
							'name'     => $wcap_upload_audio['name']['wcap_preview_attachment'][ $key ],
							'type'     => $wcap_upload_audio['type']['wcap_preview_attachment'][ $key ],
							'tmp_name' => $wcap_upload_audio['tmp_name']['wcap_preview_attachment'][ $key ],
							'error'    => $wcap_upload_audio['error']['wcap_preview_attachment'][ $key ],
							'size'     => $wcap_upload_audio['size']['wcap_preview_attachment'][ $key ],
						);
						$file_ext = strtolower( pathinfo( $uploadedfile['name'], PATHINFO_EXTENSION ) );
						$file_type = wp_check_filetype_and_ext( $uploadedfile['tmp_name'], $uploadedfile['name'] );
						
						if ( ! in_array( $file_ext, $supported_types ) || !$file_type['type'] ) {
							wp_die( __( 'Invalid audio file. Allowed formats: MP3, WAV, OGG, M4A', 'wc-audio-preview' ) );
						}

						if ( $uploadedfile['size'] > 10485760 ) {
							wp_die( __( 'File size exceeds 10MB limit', 'wc-audio-preview' ) );
						}
						// Use the WordPress API to upload the file.
						if ( ! function_exists( 'wp_handle_upload' ) ) {
							require_once ABSPATH . 'wp-admin/includes/file.php';
						}
						$upload_overrides         = array( 'test_form' => false );

						add_filter( 'upload_dir', array( $this, 'wcap_set_upload_dir' ) );
						$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
						remove_filter( 'upload_dir', array( $this, 'wcap_set_upload_dir' ) );
						$_POST['wcap_audio']['wcap_audio_urls'][ $key ] = $movefile['url'];

					}
					if (isset($_POST['wcap_audio']['wcap_audio_names']) && is_array($_POST['wcap_audio']['wcap_audio_names'])) {
						// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Values are sanitized inside the loop
						foreach (wp_unslash($_POST['wcap_audio']['wcap_audio_names']) as $key => $name) {
							$audio_data['wcap_audio_names'][$key] = sanitize_text_field(wp_unslash($name));
						}
					}

					// Sanitize URLs
					if (isset($_POST['wcap_audio']['wcap_audio_urls']) && is_array($_POST['wcap_audio']['wcap_audio_urls'])) {
						// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Values are sanitized inside the loop
						foreach (wp_unslash($_POST['wcap_audio']['wcap_audio_urls']) as $key => $url) {
							if (!empty($url)) {
								$cleaned_url = esc_url_raw(wp_unslash($url));
								$file_ext = strtolower(pathinfo(parse_url($cleaned_url, PHP_URL_PATH), PATHINFO_EXTENSION));
								if (!in_array($file_ext, array('mp3', 'wav', 'ogg', 'm4a'))) {
									wp_die(__('External URL must point to a valid audio file (MP3, WAV, OGG, M4A)', 'wc-audio-preview'));
								}
								if (!empty($cleaned_url) && filter_var($cleaned_url, FILTER_VALIDATE_URL)) {
									$audio_data['wcap_audio_urls'][$key] = $cleaned_url;
									$audio_data['wcap_audio_source'][$key] = 'external';
								} else if (!empty($url)) {
									wp_die(__('Invalid URL provided', 'wc-audio-preview'));
								}
							}elseif (!empty($_FILES['wcap_audio']['name']['wcap_preview_attachment'][$key])) {
								// File uploaded - handle upload
								// ... existing upload code ...
								$audio_data['wcap_audio_source'][$key] = 'local';
							}
						}
					}
					// Update post meta with sanitized data
    				update_post_meta($post_id, 'wcap_audio', $audio_data);
				}
			}

			if ( isset( $_POST['wcap_audio_names'] ) ) {
				$wcap_preview     = isset( $_FILES['wcap_preview_attachment'] ) ? map_deep( wp_unslash( $_FILES['wcap_preview_attachment'] ), 'sanitize_text_field' ) : '';
				$wcap_audio_names = sanitize_text_field( wp_unslash( $_POST['wcap_audio_names'] ) );
				if ( '' == $wcap_audio_names && ! empty( $wcap_preview['name'] )) {
					$file_name                 = explode( '.', $wcap_preview['name'] );
					$_POST['wcap_audio_names'] = $file_name[0];
				}
				if ( isset( $_POST['wcap_audio_names'] ) && ! empty( $_POST['wcap_audio_names'] ) ) {
					$supported_types = array('mp3', 'wav', 'ogg', 'm4a');
					$supported_mimes = array(
						'mp3' => array('audio/mpeg', 'audio/mp3', 'audio/mpeg3', 'audio/x-mpeg-3'),
						'wav' => array('audio/wav', 'audio/x-wav', 'audio/wave'),
						'ogg' => array('audio/ogg', 'application/ogg'),
						'm4a' => array('audio/mp4', 'audio/x-m4a')
					);

					// Make sure the file array isn't empty.
					if ( ! empty( $_FILES['wcap_preview_attachment']['name'] ) ) {
						$uploadedfile = $wcap_preview;

						$file_ext = strtolower( pathinfo( $uploadedfile['name'], PATHINFO_EXTENSION ) );
						$file_type = wp_check_filetype_and_ext( $uploadedfile['tmp_name'], $uploadedfile['name'] );
						
						if ( ! in_array( $file_ext, $supported_types ) || !$file_type['type'] ) {
							wp_die( __( 'Invalid audio file. Allowed formats: MP3, WAV, OGG, M4A', 'wc-audio-preview' ) );
						}

						if ( $uploadedfile['size'] > 10485760 ) {
							wp_die( __( 'File size exceeds 10MB limit', 'wc-audio-preview' ) );
						}

						
						if ( ! function_exists( 'wp_handle_upload' ) ) {
							require_once ABSPATH . 'wp-admin/includes/file.php';
						}
							$upload_overrides = array( 'test_form' => false );

							add_filter( 'upload_dir', array( $this, 'wcap_set_upload_dir' ) );
							$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
							remove_filter( 'upload_dir', array( $this, 'wcap_set_upload_dir' ) );

							if ( $movefile && ! isset( $movefile['error'] ) ) {
								$movefile['name'] = map_deep( wp_unslash( $_POST['wcap_audio_names'] ), 'sanitize_text_field' );
								add_post_meta( $post_id, 'wcap_preview_attachment', $movefile );
								update_post_meta( $post_id, 'wcap_preview_attachment', $movefile );
							} else {

								echo wp_kses_post( $movefile['error'] );
								$this->wcap_log_error($movefile['error']);
								add_settings_error('wcap_audio', 'upload_error', 'Error uploading audio file: ' . ($movefile['error'] ?? 'Unknown error'), 'error');
								return;
							}
					} else {
						if ( isset( $_POST['wcap_audio_urls'] ) && ! empty( $_POST['wcap_audio_urls'] ) ) {
							$upload_file   = map_deep( wp_unslash( $_POST['wcap_audio_urls'] ), 'sanitize_text_field' );
							$file_ext      = strtolower( pathinfo( $upload_file, PATHINFO_EXTENSION ) );
							$file_type     = wp_check_filetype( $upload_file );
							
							if ( in_array( $file_ext, $supported_types ) ) {
								$mp3url         = array();
								$mp3url['name'] = map_deep( wp_unslash( $_POST['wcap_audio_names'] ), 'sanitize_text_field' );
								$mp3url['url']  = map_deep( wp_unslash( $_POST['wcap_audio_urls'] ), 'sanitize_text_field' );
								add_post_meta( $post_id, 'wcap_preview_attachment', $mp3url );
								update_post_meta( $post_id, 'wcap_preview_attachment', $mp3url );
							}
						}
					}
				}
			}
			if ( isset( $_POST['wcap_display_audio_players'] ) ) {
				update_post_meta( $post_id, 'wcap_display_audio_players', 'yes' );
			} else {
				update_post_meta( $post_id, 'wcap_display_audio_players', 'no' );
			}
		}
	}

	/**
	 * Function contains the audio delete functionality.
	 *
	 * @return void
	 */
	public function wcap_delete_audio_ajax() {
		if (!check_ajax_referer('ajax-nonce', 'nonce', false)) {
			wp_send_json_error('Invalid security token');
			exit;
		}
		if (!current_user_can('edit_posts')) {
			wp_send_json_error('Insufficient permissions');
			exit;
		}
		
		$post_id       = isset( $_POST['p_id'] ) ? absint( wp_unslash( $_POST['p_id'] ) ) : '';
		$fileurl       = isset( $_POST['file_url'] ) ? esc_url_raw( wp_unslash( $_POST['file_url'] ) ) : '';
		if (!$post_id || !$fileurl) {
			wp_send_json_error('Missing required parameters');
			exit;
    	}
		 // Verify user can edit this specific post
		if (!current_user_can('edit_post', $post_id)) {
			wp_send_json_error('Cannot edit this product');
			exit;
		}

		 // Verify the file belongs to this product
		$attachment_meta = get_post_meta($post_id, 'wcap_preview_attachment', true);
		if (empty($attachment_meta) || $attachment_meta['url'] !== $fileurl) {
			wp_send_json_error('File verification failed');
			exit;
		}
			
		$filename      = basename( $fileurl );
		$upload_dir    = wp_upload_dir();
		$upload_path   = $upload_dir['basedir'];
		$uploaded_file = $upload_path . '/wcap_files/' . $filename;
		if (file_exists($uploaded_file) && is_writable($uploaded_file)) { //phpcs:ignore
			$result = wp_delete_file($uploaded_file);
			if ($result !== false) {
				update_post_meta($post_id, 'wcap_preview_attachment', '');
				wp_send_json_success('File deleted successfully');
			} else {
				$this->wcap_log_error('Failed to delete file: ' . $uploaded_file);
				wp_send_json_error('Could not delete file');
			}
		} else {
			// File doesn't exist or isn't writable, just update the meta
			update_post_meta($post_id, 'wcap_preview_attachment', '');
			wp_send_json_success('Metadata updated');
		}
		exit;
	}

	/**
	 * Set Upload Directory
	 *
	 * Sets the upload dir to edd. This function is called from
	 * wcap_change_audio_upload_dir()
	 *
	 * @since 1.0
	 * @return array Upload directory information
	 */
	public function wcap_set_upload_dir( $upload ) {
		$upload['subdir'] = '/wcap_files';
		$upload['path']   = $upload['basedir'] . $upload['subdir'];
		$upload['url']    = $upload['baseurl'] . $upload['subdir'];
		return $upload;
	}

	/**
	 * Display admin errors
	 *
	 */
	function wcap_display_admin_errors() {
		$screen = get_current_screen();
		
		// Only show on our plugin pages
		if ($screen && (strpos($screen->id, 'woo-audio-preview') !== false || $screen->id === 'product')) {
			$errors = get_option('wcap_admin_errors', array());
			
			if (!empty($errors)) {
				echo '<div class="notice notice-error is-dismissible">';
				foreach ($errors as $error) {
					echo '<p>' . esc_html($error) . '</p>';
				}
				echo '</div>';
				
				// Clear errors after displaying
				update_option('wcap_admin_errors', array());
			}
		}
	}

	/**
	 * Log plugin errors for debugging
	 *
	 * @param string $message Error message to log
	 * @param string $level   Log level (error, warning, info)
	*/
	function wcap_log_error($message, $level = 'error') {
		if (defined('WP_DEBUG') && WP_DEBUG === true) {
			// For debug mode, output to debug.log
			if (defined('WP_DEBUG_LOG') && WP_DEBUG_LOG === true) {
				error_log('[Audio Preview for WooCommerce] ' . $level . ': ' . $message); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			}
			
			// For admin UI, maybe store errors to be displayed
			if (is_admin() && $level === 'error') {
				$errors = get_option('wcap_admin_errors', array());
				$errors[] = $message;
				// Keep only last 10 errors
				$errors = array_slice($errors, -10);
				update_option('wcap_admin_errors', $errors);
			}
		}
	}

}
