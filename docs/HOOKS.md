# Developer Hooks Reference

Audio Preview for WooCommerce exposes 14 hooks (filters and actions) so the Pro
add-on and third-party developers can extend the plugin without forking it. This
page lists every one with its arguments, return value, and purpose.

Line numbers below refer to the current source tree; use the hook name to locate
them if the file has moved.

## Free-to-Pro seam filters

These four filters are the contract the Pro add-on is built on. They let Pro
replace the renderer, move where the player appears, widen where assets load, and
tune the SoundCloud embed - all without re-implementing the free player.

### `wcap_public_instance`

*Filter* - `includes/class-wc-audio-preview.php:139`

```php
apply_filters( 'wcap_public_instance', Wc_Audio_Preview_Public $instance );
```

- **`$instance`** *(Wc_Audio_Preview_Public)* - The public-facing renderer the
  free plugin created.

Return an object (typically a `Wc_Audio_Preview_Public` subclass) to take over
asset enqueuing and player rendering. Pro returns its own subclass so there is a
single renderer with premium behaviour. Must return an object exposing the same
public methods (`enqueue_styles`, `enqueue_scripts`, `wcap_add_preview_field`).

### `wcap_preview_hook`

*Filter* - `includes/class-wc-audio-preview.php:151`

```php
apply_filters( 'wcap_preview_hook', string $hook );
```

- **`$hook`** *(string)* - The WooCommerce action the player renders on. Default
  `woocommerce_before_add_to_cart_form`.

Return a different WooCommerce hook name to move where the preview player is
output on the product page. Pro returns the position the store owner selected.

### `wcap_should_load_assets`

*Filter* - `public/class-wc-audio-preview-public.php:86`

```php
apply_filters( 'wcap_should_load_assets', bool $should_load );
```

- **`$should_load`** *(bool)* - Whether preview CSS/JS should enqueue on the
  current request. Defaults to `is_product()`.

Return `true` to load assets on additional contexts (Pro widens this to shop and
category archives when its archive badge is enabled) or `false` to suppress them.

### `wcap_soundcloud_embed_url`

*Filter* - `public/class-wc-audio-preview-public.php:412`

```php
apply_filters( 'wcap_soundcloud_embed_url', string $embed_url, string $audio_url );
```

- **`$embed_url`** *(string)* - The SoundCloud widget URL about to be printed.
- **`$audio_url`** *(string)* - The original track URL entered by the owner.

Return a modified widget URL to add player parameters (colour, hidden related
tracks, and so on) without forking the renderer. The surrounding aria-label and
`data-soundcloud-key` markup stay intact.

## Product meta box hooks

Fired while the "Audio Preview Items" meta box renders and saves in the product
editor. Pro uses these to add rows, extra columns, per-row fields, and to persist
its own sub-keys.

### `wcap_metabox_max_rows`

*Filter* - `admin/class-wc-audio-preview-admin.php:517`

```php
apply_filters( 'wcap_metabox_max_rows', int $rows, int $saved );
```

- **`$rows`** *(int)* - Number of preview rows to render. Default `3`. Values
  below `1` fall back to `3`.
- **`$saved`** *(int)* - Number of rows already saved for this product.

Return a higher number to render more rows (Pro removes the three-row cap).

### `wcap_metabox_before_rows`

*Action* - `admin/class-wc-audio-preview-admin.php:523`

```php
do_action( 'wcap_metabox_before_rows', WP_Post $post );
```

- **`$post`** *(WP_Post)* - The product being edited.

Fires just before the audio fields table. Pro adds a player-theme control here.

### `wcap_metabox_row_headings`

*Action* - `admin/class-wc-audio-preview-admin.php:545`

```php
do_action( 'wcap_metabox_row_headings' );
```

No arguments. Fires inside the table `<thead>` row, after the Name and URL
column headings. Echo `<th>` cells to add columns (e.g. Preview length) that sit
over the per-row cells added via `wcap_metabox_row_fields`.

### `wcap_metabox_row_fields`

*Action* - `admin/class-wc-audio-preview-admin.php:597`

```php
do_action( 'wcap_metabox_row_fields', int $i, string $audio_name, string $audio_url );
```

- **`$i`** *(int)* - Zero-based index of the current row.
- **`$audio_name`** *(string)* - The saved track name for this row.
- **`$audio_url`** *(string)* - The saved track URL for this row.

Fires inside each table row after the Name and URL cells. Echo `<td>` cells to
add per-row inputs (Pro adds the preview-length input here).

### `wcap_metabox_after_rows`

*Action* - `admin/class-wc-audio-preview-admin.php:606`

```php
do_action( 'wcap_metabox_after_rows', WP_Post $post );
```

- **`$post`** *(WP_Post)* - The product being edited.

Fires after the fields table. Pro adds its add-another / bulk-import controls
here. Note: the free plugin only shows its "Need more than 3 previews?" upsell
when nothing is hooked to this action (`has_action( 'wcap_metabox_after_rows' )`).

### `wcap_metabox_save`

*Action* - `admin/class-wc-audio-preview-admin.php:742`

```php
do_action( 'wcap_metabox_save', int $post_id, array $processed_audio, array $kept_indices );
```

- **`$post_id`** *(int)* - The product being saved.
- **`$processed_audio`** *(array)* - The names and URLs just stored (compacted),
  keyed `wcap_audio_names` and `wcap_audio_urls`.
- **`$kept_indices`** *(array)* - Original submitted-row index of each kept row,
  in kept order. Use it to read your own per-row `$_POST` values (e.g. durations)
  without them shifting when an earlier row was empty or failed validation.

Fires after the box writes its names and URLs to the `wcap_audio` post meta, so a
consumer can persist its own sub-keys into the same meta row from the same
request. The box merges onto the existing meta, so sub-keys it does not manage
(Pro's durations, player theme) survive a save from here.

## Front-end and validation hooks

### `wcap_allowed_audio_extensions`

*Filter* - `admin/class-wc-audio-preview-admin.php:215`

```php
apply_filters( 'wcap_allowed_audio_extensions', array $extensions );
```

- **`$extensions`** *(array)* - Allowed upload/URL file extensions. Default
  `['mp3','wav','ogg','m4a','aac','flac','wma','webm']`.

Return a modified list to allow or restrict file types. The value is passed to
the admin JS (as `allowedExtensions`) for client-side validation. Note: this only
affects the client-side check; the server-side save also validates against the
class's `$allowed_file_types` property.

### `wcap_audio_mime_types`

*Filter* - `public/class-wc-audio-preview-public.php:649`

```php
apply_filters( 'wcap_audio_mime_types', array $mime_types );
```

- **`$mime_types`** *(array)* - Map of file extension to MIME type used when
  rendering the `<audio>` source. Includes `mp3`, `wav`, `ogg`, `m4a`, `mp4`,
  `aac`, `flac`, `wma`, `webm`, `opus`, `oga`.

Return a modified map to add or override the MIME type for an extension.
Unknown extensions fall back to `audio/mpeg`.

### `wcap_before_audio_preview`

*Action* - `public/class-wc-audio-preview-public.php:197`

```php
do_action( 'wcap_before_audio_preview', int $product_id, array $wcap_audio, array $valid_audios );
```

- **`$product_id`** *(int)* - The product being displayed.
- **`$wcap_audio`** *(array)* - The raw audio preview meta for the product.
- **`$valid_audios`** *(array)* - The validated audio entries about to render.

Fires just before the preview container is output on the product page.

### `wcap_after_audio_preview`

*Action* - `public/class-wc-audio-preview-public.php:250`

```php
do_action( 'wcap_after_audio_preview', int $product_id, array $wcap_audio, array $valid_audios );
```

- **`$product_id`** *(int)* - The product being displayed.
- **`$wcap_audio`** *(array)* - The raw audio preview meta for the product.
- **`$valid_audios`** *(array)* - The validated audio entries that were rendered.

Fires just after the preview container is output on the product page.
