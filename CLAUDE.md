# Audio Preview for WooCommerce (woo-audio-preview)

## Plugin Identity
- **Plugin Name:** Audio Preview for WooCommerce
- **Slug:** woo-audio-preview
- **Text Domain:** woo-audio-preview
- **Version:** 1.5.1
- **Author:** Wbcom Designs
- **License:** GPL-2.0+
- **Requires WordPress:** 5.0+
- **Requires PHP:** 7.4+
- **Requires WooCommerce:** 4.0+
- **Tested up to:** WordPress 6.9
- **Pro Version:** woo-audio-preview-pro (separate plugin)
- **Basecamp:** https://3.basecamp.com/5798509/projects/42374833

## Names & Identity

Every surface this product is known by. When these drift, a site owner reports a bug under one name and support searches for another.

| Surface | Value |
|---|---|
| Plugin Name (what the site owner sees) | `Audio Preview for WooCommerce` |
| Install slug (`wp-content/plugins/`) | `woo-audio-preview` |
| Git repo | `woo-audio-preview` |
| Text domain | `woo-audio-preview` |
| readme.txt title | `Audio Preview for WooCommerce` |
| Basecamp board | `Audio Preview for WooCommerce` (42374833) |
| Basecamp URL | https://3.basecamp.com/5798509/projects/42374833 |

## What It Does
Adds audio preview players to WooCommerce product pages. Customers can listen to audio samples (music tracks, audiobook excerpts, sound effects) before purchasing. Supports up to 3 audio previews per product in the free version.

## Functionality catalog (check the plugin from the repo alone)

Trust order: `audit/manifest.json` (generated) > `CAPABILITIES.md` > code. Read
these before adding a hook, service or helper; reuse what exists.

| File | What it answers |
|---|---|
| `audit/manifest.json` | Structural inventory. Regenerate: `node ~/.mcp-servers/wp-plugin-qa-mcp-server/bin/write-manifest.mjs . --force` |
| `CAPABILITIES.md` | What the plugin does, supported services, data, lifecycle |
| `audit/ROLE_MATRIX.md` | Who can see or do what |
| `audit/journeys/` | Runnable walkthroughs QA and the developer both execute |
| `docs/qa/CORE_PATHS.md` | The flows that carry most real use, walked before every release |
| `docs/qa/SURFACES.md` | Where each behaviour lives (one map for Free and Pro, in Free) |
| `docs/qa/OWNER_INVENTORY.md` | What the site owner can see (wp-card-qa extractor) |
| `plan/INVARIANTS.yaml` | Rules every change must keep |
| `audit/escapes.json` | Every bug that reached QA or a customer, and the check that replays it |

## Working standard (every task, every card)

1. **A card is an entry point, not the scope.** Fix the behaviour on every surface in its `SURFACES.md` row (product metabox, product page, shop/archive, settings, hub).
2. **Reuse before writing.** The accepted-service/CDN list, the player render and the settings defaults each have one owner; a new service goes in the one place all surfaces read. A new helper is allowed only when nothing does the job, and the code it replaces is deleted in the same commit.
3. **The advertised, accepted-on-save and rendered service lists always agree** (plan/INVARIANTS.yaml A2): a service the player cannot play is not advertised or accepted.
4. **WordPress practice.** Nonce + capability on writes; escape late; i18n every string; PHP 8.1-8.5. Packaging: `assets/vendor/lucide.min.js` must ship (A1) - keep the `.distignore` `vendor` rule anchored to `/vendor`.
5. **Presentable on every section, no prototype work.** The player and settings are checked at 1440 / 1024 / 390, light and dark, on Storefront, BuddyX, Reign and Twenty Twenty-Five.
6. **Data safety.** Network options use `delete_site_option` on uninstall; file cleanup never deletes outside `wp_upload_dir()` and leaves Media Library files to WordPress (A3).
7. **Close-out.** Refresh `audit/manifest.json` + `CAPABILITIES.md`, walk `docs/qa/CORE_PATHS.md`, add the regression check and its `audit/escapes.json` row, then move the batch to Ready for Testing. Whoever wrote a fix never verifies it.

## Branching

One development branch named for the next version (Free `1.5.5`, Pro `2.2.4`).
Commit fixes straight to it; PR to the default branch only at release, tag
`vX.Y.Z`, then delete the development branch. Free and Pro release independently.

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
| `WCAP_TEXT_DOMAIN` | `'woo-audio-preview'` |
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
The advertised list (`get_supported_services_info`), the accepted-on-save list
(admin `$cdn_patterns`) and the public renderer (`wcap_is_cdn_url`) agree on one
set. Supports URL detection and playback for:
- Google Drive (iframe player)
- SoundCloud (iframe player with Widget API)
- Spotify (embedded Spotify player; 30-second preview for logged-out visitors)
- Dropbox (URL conversion to direct download)
- Amazon S3 (direct URL)
- CloudFront (direct URL)
- OneDrive (URL conversion)
- Box.com (URL conversion)

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
