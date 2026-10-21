# Data storage

Where the plugin stores its data, for developers integrating with or migrating
from it.

## Post meta

Audio previews are stored in a single post meta key on the `product` post type.

| Meta key | Post type | Shape |
|---|---|---|
| `wcap_audio` | `product` | Array with parallel index arrays: `wcap_audio_names`, `wcap_audio_urls`, `wcap_audio_source`. |

- `wcap_audio_names` and `wcap_audio_urls` are aligned by index (row 0's name
  pairs with row 0's URL).
- `wcap_audio_source` records how each URL was classified on save (`direct` or
  `cdn`).
- The Pro add-on stores its per-track preview length under an additional
  `wcap_audio_durations` sub-key in the same array. The free save merges onto
  existing meta, so sub-keys it does not manage are preserved.

When a product has no valid rows, the `wcap_audio` meta is deleted entirely.

## Reading previews (shared reader)

Both the free renderer and Pro read stored audio through one class so they
interpret the meta identically:

```php
$entries = WCAP_Audio::get( $product_id );
// [] when none valid, otherwise a list of:
// [ 'key' => int, 'name' => string, 'url' => string, 'duration' => int ]
```

`WCAP_Audio` is defined at include time (before `plugins_loaded`) so Pro can
call it during its own early boot.

## Options

| Option | Purpose | Autoload |
|---|---|---|
| `wcap_admin_errors` | Last 10 admin error/validation messages, shown once then cleared. | No |
| `woo_audio_feedback_activation_date` | Review prompt: when the plugin was first activated. | Default |
| `woo_audio_feedback_no_bug` | Review prompt: set when the user dismisses the review notice. | Default |

The two `woo_audio_feedback_*` values are written with the site-option API
(`add_site_option` / `get_site_option`).

## AJAX

| Action | Access | Nonce | Capability |
|---|---|---|---|
| `wcap_delete_audio_ajax` | Logged-in only | `ajax-nonce` (posted as `nonce`) | `edit_post` on the target product |

It removes a previously uploaded file from `wp-content/uploads/wcap_files/`.

## Uninstall

`uninstall.php` deletes the three options above and removes all `wcap_audio`
post meta across products.

## No REST, blocks, or shortcodes

The plugin registers no REST API routes, no Gutenberg blocks, and no
shortcodes. The player is output through the WooCommerce product-page hook only
(see `wcap_preview_hook`).
