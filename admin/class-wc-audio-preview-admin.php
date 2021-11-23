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
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wc-audio-preview-admin.css', array(), $this->version, 'all' );
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
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wc-audio-preview-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'wcap_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	}

	/**
	 * Actions performed to create a submenu page content.
	 *
	 * @since    1.0.0
	 * @access public
	 */
	public function woo_audio_preview_admin_options_page() {
		global $allowedposttags;
		$tab = filter_input( INPUT_GET, 'tab' ) ? filter_input( INPUT_GET, 'tab' ) : 'woo-audio-preview-welcome';
		?>
	<div class="wrap">
		<div class="wbcom-wrap">
			<div class="bupr-header">
				<?php echo do_shortcode( '[wbcom_admin_setting_header]' ); ?>
				<hr class="wp-header-end">
				<h1 class="wbcom-plugin-heading">
					<?php esc_html_e( 'Woo Audio Preview', 'wc-audio-preview' ); ?>
				</h1>
			</div>
			<div class="wbcom-admin-settings-page">
				<?php
				settings_errors();
				$this->woo_audio_preview_plugin_settings_tabs();
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
	public function woo_audio_preview_init_plugin_settings() {
		$this->plugin_settings_tabs['woo-audio-preview-welcome'] = esc_html__( 'Welcome', 'wc-audio-preview' );
		register_setting( 'woo_audio_preview_admin_welcome_options', 'woo_audio_preview_admin_welcome_options' );
		add_settings_section( 'woo-audio-preview-welcome', ' ', array( $this, 'woo_audio_preview_admin_welcome_content' ), 'woo-audio-preview-welcome' );

		$this->plugin_settings_tabs['woo-audio-preview-faq'] = esc_html__( 'FAQ', 'wc-audio-preview' );
		register_setting( 'woo_audio_preview_general_options', 'woo_audio_preview_general_options' );
		add_settings_section( 'woo-audio-preview-faq', ' ', array( $this, 'woo_audio_preview_general_options_content' ), 'woo-audio-preview-faq' );

		$this->plugin_settings_tabs['woo-audio-preview-pro'] = esc_html__( 'General (PRO)', 'wc-audio-preview' );
		add_settings_section( 'woo-audio-preview-general-pro', ' ', array( $this, 'woo_audio_preview_general_pro' ), 'woo-audio-preview-pro' );
	}

	/**
	 * Actions performed to create tabs on the sub menu page.
	 */
	public function woo_audio_preview_plugin_settings_tabs() {
		$current_tab = filter_input( INPUT_GET, 'tab' ) ? filter_input( INPUT_GET, 'tab' ) : 'woo-audio-preview-welcome';
		// xprofile setup tab.
		echo '<div class="wbcom-tabs-section"><div class="nav-tab-wrapper"><div class="wb-responsive-menu"><span>' . esc_html( 'Menu' ) . '</span><input class="wb-toggle-btn" type="checkbox" id="wb-toggle-btn"><label class="wb-toggle-icon" for="wb-toggle-btn"><span class="wb-icon-bars"></span></label></div><ul>';
		foreach ( $this->plugin_settings_tabs as $tab_key => $tab_caption ) {
			$active = $current_tab === $tab_key ? 'nav-tab-active' : '';
			echo '<li><a class="nav-tab ' . esc_attr( $active ) . '" id="' . esc_attr( $tab_key ) . '-tab" href="?page=woo-audio-preview-settings&tab=' . esc_attr( $tab_key ) . '">' . esc_attr( $tab_caption ) . '</a></li>';
		}
		echo '</div></ul></div>';
	}

	/**
	 * woo_audio_preview_admin_welcome_content
	 *
	 * @return void
	 */
	public function woo_audio_preview_admin_welcome_content() {
		include plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/woo-audio-preview-welcome-page.php';
	}

	/**
	 * woo_audio_preview_general_options_content
	 *
	 * @return void
	 */
	public function woo_audio_preview_general_options_content() {
		include plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/woo-audio-preview-faq.php';
	}

	/**
	 * woo_audio_preview_general_pro
	 *
	 * @return void
	 */
	public function woo_audio_preview_general_pro() {
		include plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/woo-audio-preview-general-pro.php';
	}

	/**
	 * Actions performed on loading admin_menu.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @author   Wbcom Designs
	 */
	public function woo_audio_preview_views_add_admin_settings() {
		if ( empty( $GLOBALS['admin_page_hooks']['wbcomplugins'] ) ) {
			add_menu_page( esc_html__( 'WB Plugins', 'wc-audio-preview' ), esc_html__( 'WB Plugins', 'wc-audio-preview' ), 'manage_options', 'wbcomplugins', array( $this, 'woo_audio_preview_admin_options_page' ), 'dashicons-lightbulb', 59 );
			add_submenu_page( 'wbcomplugins', esc_html__( 'Welcomw', 'wc-audio-preview' ), esc_html__( 'Welcome', 'wc-audio-preview' ), 'manage_options', 'wbcomplugins' );

		}
		add_submenu_page( 'wbcomplugins', esc_html__( 'Woo Audio Preview', 'wc-audio-preview' ), esc_html__( 'Woo Audio Preview', 'wc-audio-preview' ), 'manage_options', 'woo-audio-preview-settings', array( $this, 'woo_audio_preview_admin_options_page' ) );
	}


	/**
	 * Update edit form enctype.
	 */
	public function update_edit_form() {
		echo ' enctype="multipart/form-data"';
	}

	/**
	 * Register meta box(es).
	 */
	public function wcap_register_meta_boxes() {
		global $post;
		add_meta_box( 'wc-preview-audio-mata-id', __( 'Preview Item <span class="wcap-required-span"> ( Only MP3 allowed here. )</span>', 'wc-audio-preview' ), array( $this, 'wcap_display_callback' ), 'product' );
		add_meta_box( 'wc-display-audio-players', __( 'Display Audio Player', 'wc-audio-preview' ), array( $this, 'wcap_display_audio_player' ), 'product' );

	}

	/**
	 * Audio Player Meta box display callback.
	 *
	 * @param WP_Post $post Current post object.
	 */
	public function wcap_display_audio_player( $post ) {
		// Add nonce for security and authentication.
		wp_nonce_field( 'wcap_nonce_action', 'wcap_nonce' );
		$wcap_audio = get_post_meta( $post->ID, 'wcap_audio', true );
		?>
					<tr>
						<td class="sort"></td>
						<td>
						<input type="checkbox" name="wcap_audio[wcap_display_audio_players]" id="wcap-display-audio-player" value="yes" 
						<?php ( isset( $wcap_audio['wcap_display_audio_players'] ) ) ? checked( $wcap_audio['wcap_display_audio_players'], 'yes' ) : ''; ?>
							/>
						</td>
						<th scope="row">
							<label for="wcap-display-audio-playlist">
								<?php esc_html_e( 'Enable this option if you want to display player', 'wc-audio-preview' ); ?>
							</label>
						</th>
					</tr>
			<?php
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
						<th><?php esc_attr_e( 'Name', 'wc-audio-preview' ); ?> <span class="woocommerce-help-tip"></span></th>
						<th colspan="2"><?php esc_attr_e( 'Audio URL', 'wc-audio-preview' ); ?> <span class="woocommerce-help-tip"></span></th>
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
								<td class="file_name"><input class="input_text" placeholder="Mp3 Name" name="wcap_audio[wcap_audio_names][]" value="<?php echo isset( $wcap_audio['wcap_audio_names'][ $key ] ) ? esc_attr( $wcap_audio['wcap_audio_names'][ $key ] ) : ''; ?>" type="text" ></td>
								<td class="file_url"><input class="input_text" placeholder="http://" id="wcap_audio_urls" name="wcap_audio[wcap_audio_urls][]" value="<?php echo isset( $wcap_audio['wcap_audio_urls'][ $key ] ) ? esc_attr( $wcap_audio['wcap_audio_urls'][ $key ] ) : ''; ?>" type="text"></td>
								<td class="file_url_choose" width="1%"><input type="file" id="wcap_preview_attachment" name="wcap_audio[wcap_preview_attachment][]" value="<?php echo isset( $preview_data['file'] ) ? esc_attr( $preview_data['file'] ) : ''; ?>" size="25"/></td>
								<td width="15%">
								<a href="javascript:void(0)"  class="wcap-add-audio-cl"><?php esc_html_e( 'Add', 'wc-audio-preview' ); ?></a>&nbsp;
								<?php if ( count( $wcap_audio['wcap_audio_names'] ) > 1 ) : ?>
								<a href="javascript:void(0)" data-p_id="<?php echo esc_attr( $post->ID ); ?>" data-file="<?php echo isset( $preview_data['file'] ) ? esc_attr( $preview_data['file'] ) : ''; ?>"class="wcap-delete-audio-cl" id="wcap-delete-audio-id"><?php esc_html_e( 'Remove', 'wc-audio-preview' ); ?></a></td>
								<?php endif; ?>
							</tr>
						<?php endforeach; ?>
					<?php else : ?>
						<tr class="wcap-audio-file">
							<td class="sort"></td>
							<td class="file_name"><input class="input_text" placeholder="Mp3 Name" name="wcap_audio[wcap_audio_names][]" value="<?php echo isset( $preview_data['name'] ) ? esc_attr( $preview_data['name'] ) : ''; ?>" type="text" ></td>
							<td class="file_url"><input class="input_text" placeholder="http://" id="wcap_audio_urls" name="wcap_audio[wcap_audio_urls][]" value="<?php echo isset( $preview_data['url'] ) ? esc_attr( $preview_data['url'] ) : ''; ?>" type="text"></td>
							<td class="file_url_choose" width="1%"><input type="file" id="wcap_preview_attachment" name="wcap_audio[wcap_preview_attachment][]" value="<?php echo isset( $preview_data['file'] ) ? esc_attr( $preview_data['file'] ) : ''; ?>" size="25"/></td>
							<td width="15%">
							<a href="javascript:void(0)"  class="wcap-add-audio-cl"><?php esc_html_e( 'Add', 'wc-audio-preview' ); ?></a>&nbsp;
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
		$nonce_name   = isset( $_POST['wcap_nonce'] ) ? $_POST['wcap_nonce'] : '';
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

		// Check if not a revision.
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		if ( isset( $_POST['post_type'] ) && $_POST['post_type'] == 'product' ) {
			if ( isset( $_POST['wcap_audio'] ) && ! empty( $_POST['wcap_audio'] ) ) {
				if ( isset( $_FILES['wcap_audio']['name'] ) && ! empty( $_FILES['wcap_audio']['name'] ) ) {
					$supported_types = array( 'audio/mpeg', 'audio/mpeg3', 'audio/x-mpeg-3' );
					foreach ( $_FILES['wcap_audio']['name']['wcap_preview_attachment'] as $key => $value ) {
						$arr_file_type = wp_check_filetype( basename( $value ) );
						$uploaded_type = $arr_file_type['type'];
						if ( in_array( $uploaded_type, $supported_types ) ) {
							// Use the WordPress API to upload the file.
							if ( ! function_exists( 'wp_handle_upload' ) ) {
								require_once ABSPATH . 'wp-admin/includes/file.php';
							}

							$uploadedfile['name']     = $_FILES['wcap_audio']['name']['wcap_preview_attachment'][ $key ];
							$uploadedfile['type']     = $_FILES['wcap_audio']['type']['wcap_preview_attachment'][ $key ];
							$uploadedfile['tmp_name'] = $_FILES['wcap_audio']['tmp_name']['wcap_preview_attachment'][ $key ];
							$uploadedfile['error']    = $_FILES['wcap_audio']['error']['wcap_preview_attachment'][ $key ];
							$uploadedfile['size']     = $_FILES['wcap_audio']['size']['wcap_preview_attachment'][ $key ];
							$upload_overrides         = array( 'test_form' => false );

							add_filter( 'upload_dir', array( $this, 'wcap_set_upload_dir' ) );
							$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
							remove_filter( 'upload_dir', array( $this, 'wcap_set_upload_dir' ) );
							$_POST['wcap_audio']['wcap_audio_urls'][ $key ] = $movefile['url'];

						}
					}
					update_post_meta( $post_id, 'wcap_audio', $_POST['wcap_audio'] );
				}
			}

			if ( isset( $_POST['wcap_audio_names'] ) ) {
				$wcap_audio_names = sanitize_text_field( $_POST['wcap_audio_names'] );
				if ( '' == $wcap_audio_names ) {
					$file_name                 = explode( '.', $_FILES['wcap_preview_attachment']['name'] );
					$_POST['wcap_audio_names'] = $file_name[0];
				}
				if ( isset( $_POST['wcap_audio_names'] ) && ! empty( $_POST['wcap_audio_names'] ) ) {

					// Make sure the file array isn't empty.
					if ( ! empty( $_FILES['wcap_preview_attachment']['name'] ) ) {

						// Setup the array of supported file types. In this case, it's just PDF.
						$supported_types = array( 'audio/mpeg', 'audio/mpeg3', 'audio/x-mpeg-3' );

						// Get the file type of the upload.
						$arr_file_type = wp_check_filetype( basename( $_FILES['wcap_preview_attachment']['name'] ) );
						$uploaded_type = $arr_file_type['type'];
						// Check if the type is supported. If not, throw an error.
						if ( in_array( $uploaded_type, $supported_types ) ) {
							// Use the WordPress API to upload the file.
							if ( ! function_exists( 'wp_handle_upload' ) ) {
								require_once ABSPATH . 'wp-admin/includes/file.php';
							}
							$uploadedfile     = $_FILES['wcap_preview_attachment'];
							$upload_overrides = array( 'test_form' => false );

							add_filter( 'upload_dir', array( $this, 'wcap_set_upload_dir' ) );
							$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
							remove_filter( 'upload_dir', array( $this, 'wcap_set_upload_dir' ) );

							if ( $movefile && ! isset( $movefile['error'] ) ) {
								$movefile['name'] = sanitize_text_field( $_POST['wcap_audio_names'] );
								add_post_meta( $post_id, 'wcap_preview_attachment', $movefile );
								update_post_meta( $post_id, 'wcap_preview_attachment', $movefile );
							} else {
								/**
								 * Error generated by _wp_handle_upload()
								 *
								 * @see _wp_handle_upload() in wp-admin/includes/file.php
								 */
								echo $movefile['error']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							}
						} else {
							// Error Message.
						} // end if/else.
					} else {
						if ( isset( $_POST['wcap_audio_urls'] ) && ! empty( $_POST['wcap_audio_urls'] ) ) {
							$supported_types = array( 'audio/mpeg', 'audio/mpeg3', 'audio/x-mpeg-3' );
							$arr_file_type   = wp_check_filetype( $_POST['wcap_audio_urls'] );
							$uploaded_type   = $arr_file_type['type'];
							if ( in_array( $uploaded_type, $supported_types ) ) {
								$mp3url         = array();
								$mp3url['name'] = sanitize_text_field( $_POST['wcap_audio_names'] );
								$mp3url['url']  = sanitize_text_field( $_POST['wcap_audio_urls'] );
								add_post_meta( $post_id, 'wcap_preview_attachment', $mp3url );
								update_post_meta( $post_id, 'wcap_preview_attachment', $mp3url );
							} else {
								// Error Message.
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
		if ( isset( $_POST ) ) {
			$post_id       = sanitize_text_field( $_POST['p_id'] );
			$fileurl       = sanitize_text_field( $_POST['file_url'] );
			$filename      = basename( $fileurl );
			$upload_dir    = wp_upload_dir();
			$upload_path   = $upload_dir['basedir'];
			$uploaded_file = $upload_path . '/wcap_files/' . $filename;
			if ( file_exists( $uploaded_file ) ) {
				@unlink( $uploaded_file );
				update_post_meta( $post_id, 'wcap_preview_attachment', '' );
			}
		}
		die();
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

}
