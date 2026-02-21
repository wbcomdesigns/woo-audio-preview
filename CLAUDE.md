# Audio Preview for WooCommerce (woo-audio-preview)

## Plugin Identity
- **Plugin Name:** Audio Preview for WooCommerce
- **Slug:** woo-audio-preview
- **Text Domain:** wc-audio-preview
- **Version:** 1.5.1
- **Author:** Wbcom Designs
- **License:** GPL-2.0+
- **Requires WordPress:** 5.0+
- **Requires PHP:** 7.4+
- **Requires WooCommerce:** 4.0+
- **Tested up to:** WordPress 6.9
- **Pro Version:** woo-audio-preview-pro (separate plugin)

## What It Does
Adds audio preview players to WooCommerce product pages. Customers can listen to audio samples (music tracks, audiobook excerpts, sound effects) before purchasing. Supports up to 3 audio previews per product in the free version.

## Architecture

### Pattern
WordPress Plugin Boilerplate (loader pattern). Actions/filters are registered via `Wc_Audio_Preview_Loader` and executed on `run()`.

### Key Files

| File | Purpose |
|------|---------|
| `woo-product-audio-preview.php` | Main bootstrap file, dependency checks, activation redirect |
| `includes/class-wc-audio-preview.php` | Core class, loads dependencies, defines hooks |
| `includes/class-wc-audio-preview-loader.php` | Hook registration system (actions + filters) |
| `includes/class-wc-audio-preview-i18n.php` | Internationalization (empty - uses auto WP.org translations) |
| `admin/class-wc-audio-preview-admin.php` | Admin: meta boxes, settings page, URL validation, CDN detection |
| `admin/class-admin-review.php` | Review prompt notice (after 1 week) |
| `public/class-wc-audio-preview-public.php` | Frontend: audio player rendering, CDN URL conversion |
| `admin/wbcom/wbcom-admin-settings.php` | Shared Wbcom admin header/nav framework |
| `admin/partials/woo-audio-preview-welcome-page.php` | Welcome/getting started tab |
| `admin/partials/woo-audio-preview-general-pro.php` | Free vs Pro comparison tab |
| `admin/partials/woo-audio-preview-faq.php` | FAQ accordion tab |
| `uninstall.php` | Uninstall handler (currently empty/no cleanup) |

### JavaScript Files

| File | Purpose |
|------|---------|
| `admin/js/wc-audio-preview-admin.js` | Admin: media library, CDN detection, form validation, FAQ accordion |
| `public/js/wc-audio-preview-public.js` | Frontend: WCAPPlayer object, play/pause, progress, error handling |
| `public/js/soundcloud.min.js` | SoundCloud Widget API for iframe player control |

### CSS Files
- `admin/css/wc-audio-preview-admin.css` (+ .min.css)
- `admin/css-rtl/wc-audio-preview-admin.css` (RTL support)
- `public/css/wc-audio-preview-public.css` (+ .min.css)
- `public/css-rtl/wc-audio-preview-public.css` (RTL support)

## Constants

| Constant | Value |
|----------|-------|
| `WCAP_TEXT_VERSION` | `'1.5.1'` |
| `WCAP_TEXT_DOMAIN` | `'wc-audio-preview'` |
| `WCAP_PLUGIN_URI` | `plugin_dir_url(__FILE__)` |
| `WCAP_PLUGIN_DIR` | `plugin_dir_path(__FILE__)` |

## Hooks & Filters

### Actions (Admin)
| Hook | Callback | Priority |
|------|----------|----------|
| `admin_enqueue_scripts` | `enqueue_styles` | 10 |
| `admin_enqueue_scripts` | `enqueue_scripts` | 10 |
| `add_meta_boxes` | `wcap_register_meta_boxes` | 10 |
| `save_post` | `wcap_save_meta_box` | 10 |
| `post_edit_form_tag` | `wcap_update_edit_form` | 10 |
| `wp_ajax_wcap_delete_audio_ajax` | `wcap_delete_audio_ajax` | 10 |
| `wp_ajax_nopriv_wcap_delete_audio_ajax` | `wcap_delete_audio_ajax` | 10 |
| `admin_init` | `wcap_init_plugin_settings` | 10 |
| `admin_menu` | `wcap_views_add_admin_settings` | 10 |
| `in_admin_header` | `wcap_hide_all_admin_notices_from_setting_page` | 10 |
| `admin_notices` | `wcap_display_admin_errors` | 10 |

### Actions (Public)
| Hook | Callback | Priority |
|------|----------|----------|
| `wp_enqueue_scripts` | `enqueue_styles` | 10 |
| `wp_enqueue_scripts` | `enqueue_scripts` | 10 |
| `woocommerce_before_add_to_cart_form` | `wcap_add_preview_field` | 0 |

### Developer Filters
| Filter | Purpose |
|--------|---------|
| `wcap_allowed_audio_extensions` | Modify allowed audio file types (default: mp3, wav, ogg, m4a, aac, flac, wma, webm) |
| `wcap_audio_mime_types` | Modify MIME type mapping for audio files |

### Developer Actions
| Action | Purpose |
|--------|---------|
| `wcap_before_audio_preview` | Fires before audio preview display (documented in readme, not yet implemented) |
| `wcap_after_audio_preview` | Fires after audio preview display (documented in readme, not yet implemented) |

## Data Storage

### Post Meta
| Meta Key | Post Type | Format |
|----------|-----------|--------|
| `wcap_audio` | `product` | Array with `wcap_audio_names[]`, `wcap_audio_urls[]`, `wcap_audio_source[]` |
| `wcap_preview_attachment` | `product` | Legacy attachment URL (cleared on delete) |

### Options
| Option | Purpose |
|--------|---------|
| `wcap_admin_errors` | Stores last 10 admin error messages for display |
| `woo_audio_feedback_activation_date` | Review prompt: activation timestamp |
| `woo_audio_feedback_no_bug` | Review prompt: dismissed flag |

## CDN/Service Support
Supports URL detection and playback for:
- Google Drive (iframe player)
- SoundCloud (iframe player with Widget API)
- Dropbox (URL conversion to direct download)
- Amazon S3 (direct URL)
- CloudFront (direct URL)
- OneDrive (URL conversion)
- Box.com (URL conversion)
- MediaFire (detection only)
- Spotify (detection only, not playable)

## Audio Format Support
MP3, WAV, OGG, M4A, AAC, FLAC, WMA, WEBM

## Admin Settings Pages
Settings are under WB Plugins > Audio Preview for WooCommerce (`?page=woo-audio-preview-settings`).

### Tabs
1. **Welcome** - Quick start guide, key features, tips
2. **General (PRO)** - Free vs Pro comparison table, upgrade CTA ($49 starting price)
3. **FAQ** - Accordion-style FAQ

## Pro Version Detection
The free version checks for `woo-audio-preview-pro/woo-audio-preview-pro.php` in active plugins. If Pro is active, the free version's admin menu is suppressed (Pro takes over the settings page).

## Build Tools
- Grunt (`gruntfile.js`) for CSS/JS minification
- npm (`package.json`) for build dependencies

## File Counts
- PHP files: 14 (excluding index.php files)
- JS files: 6 (3 source + 3 minified)
- CSS files: 10 (source, minified, RTL variants)
- Total non-git files: ~40

## Recent Changes

| Version | Changes |
|---------|---------|
| 1.5.1 | WPCS compliance, Plugin Check improvements, WP 6.9 compat |
| 1.5.0 | Major: CDN support (Google Drive, SoundCloud, Dropbox, S3), modern player, fixed 3-field layout, mobile optimization, new audio formats |
| 1.4.2 | WooCommerce 8.0+ compatibility fix |
