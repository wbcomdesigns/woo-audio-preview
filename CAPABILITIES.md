# Audio Preview for WooCommerce — Capabilities

Functionality roll-up for the free plugin. Source of record: `master @ 1.5.1`. Machine-readable companion: `audit/manifest.json`.

## What it does

Adds an audio player to WooCommerce product pages so shoppers can listen to a sample before they buy. The store owner attaches up to 3 audio previews to any product — uploaded from the Media Library or linked from a CDN / streaming service — and the player renders automatically on the single-product page, just before the Add to Cart button.

## Capabilities

| Capability | Maturity | Notes |
|---|---|---|
| Product audio previews (up to 3) | Stable | Fixed 3-field metabox on the product editor; row 1 primary, rows 2-3 optional |
| Media Library upload | Stable | `wp_enqueue_media`, custom upload subdir `uploads/wcap_files/` |
| External / CDN URL input | Stable | Paste any public URL; service auto-detected and validated on save |
| HTML5 audio player | Stable | Play/pause, progress bar, elapsed time, `preload=none` (no load until interaction) |
| Google Drive playback | Stable | Rendered via Drive `/preview` iframe |
| SoundCloud playback | Stable | Rendered via SoundCloud Widget API (`soundcloud.min.js`) iframe |
| Dropbox / OneDrive / Box / S3 / CloudFront / MediaFire | Stable | URL rewritten to a direct-download form and played in the HTML5 element |
| URL / MIME validation | Stable | `validate_audio_url()`; supported extensions mp3, wav, ogg, m4a, aac, flac, wma, webm |
| RTL + minified assets | Stable | `get_asset_filename()` resolves min / RTL / fallback variants |
| Delete uploaded preview (AJAX) | Stable | `wcap_delete_audio_ajax`, nonce + capability gated |
| Extension hooks/filters | Stable | `wcap_before/after_audio_preview` actions; `wcap_allowed_audio_extensions`, `wcap_audio_mime_types` filters |
| Settings page | Informational | Welcome / General (PRO upsell) / FAQ tabs — no functional storefront options in free |

## Admin surfaces

- **WB Plugins > Audio Preview for WooCommerce** (`page=woo-audio-preview-settings`, `manage_options`) — shared wbcom settings shell; Welcome, General (PRO), FAQ tabs. Menu is suppressed when the Pro plugin is active.
- **Product editor metabox** "Audio Preview Items" (`wc-preview-audio-mata-id`) — the only functional admin surface: 3 name+URL rows, Media Library / Clear buttons, live CDN-detected indicator, nonce `wcap_nonce`.

## Frontend surfaces (player placement)

- **Single product page** — `woocommerce_before_add_to_cart_form` (priority 0), rendered by `Wc_Audio_Preview_Public::wcap_add_preview_field`. This is the only frontend placement in free (no shop-loop / archive rendering — that is Pro).

## Data stored

- `wcap_audio` **post meta** (array: `wcap_audio_names[]`, `wcap_audio_urls[]`, `wcap_audio_source[]`). **Shared contract with Pro** — Pro extends the same key with `wcap_audio_durations[]` and `wcap_audio_theme`.
- `wcap_preview_attachment` post meta — legacy/vestigial, only cleared by the delete AJAX.
- `wcap_admin_errors` option — debug error queue (autoload off).
- `_woo_audio_preview_is_new_install` transient — activation-redirect flag.
- Uploaded files under `uploads/wcap_files/`.

## Extension seams

- Actions: `wcap_before_audio_preview`, `wcap_after_audio_preview` (product_id, wcap_audio, valid_audios).
- Filters: `wcap_allowed_audio_extensions` (admin upload types), `wcap_audio_mime_types` (frontend extension→MIME map).

## Free vs Pro split

- **Free owns:** up to 3 previews, single-product player before Add to Cart, CDN detection + playback, Media Library upload, the shared `wcap_audio` meta contract.
- **Pro adds:** unlimited previews, per-track preview duration limits, audio watermarking, player themes (classic/dark/minimal/colorful), shop-loop / archive badge, bulk import, drag-to-reorder, configurable display position, and multivendor support (Dokan, WCFM, WC Vendors). When Pro is active it deactivates free and takes over the metabox + settings.
