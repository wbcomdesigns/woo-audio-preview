# Audio Preview - Surfaces (Free + Pro)

Where each behaviour lives. A card about one surface is checked on every surface
in its row. Pro keeps no second map; it points here. Method names are the
stable anchors; the grep re-finds them.

Surfaces: **Product metabox** (admin product edit), **Product page** (the player,
`woocommerce_before_add_to_cart_form` via `wcap_preview_hook`), **Shop/archive**
(Pro badge), **Settings** (WB Plugins > Audio Preview, shared Wbcom shell),
**Hub** (WB Plugins hub page), **AJAX**, **Uninstall**.

| Behaviour | Product metabox | Product page | Shop/archive | Settings | AJAX | Uninstall | Re-verify |
|---|---|---|---|---|---|---|---|
| Add / edit preview URLs (upload, Media Library, external, CDN) | `wcap_save_meta_box()` (`wcap_audio` meta) | - | - | - | `wcap_delete_audio_ajax` | - | `grep -rn "wcap_audio\|validate_audio_url" admin --include=*.php` |
| Accepted service / CDN list agrees with playback | `validate_audio_url()` | renderer services | Pro badge | supported-services UI | - | - | `grep -rn "get_supported_services_info\|cdn_patterns\|validate_audio_url" --include=*.php . ../woo-audio-preview-pro` |
| Player render | - | `wcap_add_preview_field()` | Pro `render_a...` | hook filter `wcap_preview_hook` | - | - | `grep -n "wcap_add_preview_field\|wcap_preview_hook" public includes` |
| Lucide hub icons | - | - | - | enqueues `assets/vendor/lucide.min.js` | - | - | `grep -n "lucide.min.js" lib/wbcom-settings/*.php; unzip -l dist zip` |
| Settings (defaults, labels) | - | reads options | reads options | `lib/wbcom-settings/` shell | - | - | `grep -rn "DEFAULTS\|get_option" ../woo-audio-preview-pro --include=*.php` |
| Pro: watermark overlay | - | overlay on play | - | watermark settings | - | - | `grep -n "watermark" ../woo-audio-preview-pro/public/*.php` |
| Pro: archive badge position | - | - | `archive_position` (after_title/before_price/after_add_to_cart) | position select | - | - | `grep -rn "archive_position" ../woo-audio-preview-pro --include=*.php` |
| Data safety (uninstall, file cleanup) | - | - | - | - | file delete guard | `uninstall.php` (site vs network options) | `grep -n "delete_option\|delete_site_option\|wcap_files" uninstall.php admin/*.php` |
