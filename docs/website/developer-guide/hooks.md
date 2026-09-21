# Hooks reference

The plugin exposes 14 filters and actions so the Pro add-on and third-party
code can extend it without forking. Each is grouped by where it fires.

## Free-to-Pro seam filters

These four filters are the contract the Pro add-on builds on.

### `wcap_public_instance` (filter)

```php
apply_filters( 'wcap_public_instance', Wc_Audio_Preview_Public $instance );
```

Return an object (typically a `Wc_Audio_Preview_Public` subclass) to take over
asset enqueuing and player rendering. It must expose the same public methods
(`enqueue_styles`, `enqueue_scripts`, `wcap_add_preview_field`). Pro returns its
own subclass so there is a single renderer.

### `wcap_preview_hook` (filter)

```php
apply_filters( 'wcap_preview_hook', string $hook );
```

The WooCommerce action the player renders on. Default
`woocommerce_before_add_to_cart_form`. Return a different hook name to move
where the player appears.

### `wcap_should_load_assets` (filter)

```php
apply_filters( 'wcap_should_load_assets', bool $should_load );
```

Whether the preview CSS/JS enqueue on the current request. Defaults to
`is_product()`. Return `true` to load on more contexts (Pro widens this to shop
and category archives) or `false` to suppress.

### `wcap_soundcloud_embed_url` (filter)

```php
apply_filters( 'wcap_soundcloud_embed_url', string $embed_url, string $audio_url );
```

The SoundCloud widget URL about to be printed, plus the original track URL.
Return a modified widget URL to add player parameters. The surrounding
aria-label and `data-soundcloud-key` markup stay intact.

## Product meta box hooks

Fired while the "Audio Preview Items" meta box renders and saves.

### `wcap_metabox_max_rows` (filter)

```php
apply_filters( 'wcap_metabox_max_rows', int $rows, int $saved );
```

Number of preview rows to render. Default `3`; values below `1` fall back to
`3`. `$saved` is the number of rows already saved. Return a higher number to
render more rows (Pro removes the three-row cap).

### `wcap_metabox_before_rows` (action)

```php
do_action( 'wcap_metabox_before_rows', WP_Post $post );
```

Fires just before the audio fields table. Pro adds a player-theme control here.

### `wcap_metabox_row_headings` (action)

```php
do_action( 'wcap_metabox_row_headings' );
```

No arguments. Fires inside the table header row, after the Name and URL headings.
Echo `<th>` cells to add columns that sit over cells added via
`wcap_metabox_row_fields`.

### `wcap_metabox_row_fields` (action)

```php
do_action( 'wcap_metabox_row_fields', int $i, string $audio_name, string $audio_url );
```

Fires inside each table row after the Name and URL cells. `$i` is the zero-based
row index. Echo `<td>` cells to add per-row inputs (Pro adds a preview-length
input here).

### `wcap_metabox_after_rows` (action)

```php
do_action( 'wcap_metabox_after_rows', WP_Post $post );
```

Fires after the fields table. Pro adds its add-another / bulk-import controls
here. The free plugin only shows its "Need more than 3 previews?" upsell when
nothing is hooked to this action (`has_action( 'wcap_metabox_after_rows' )`).

### `wcap_metabox_save` (action)

```php
do_action( 'wcap_metabox_save', int $post_id, array $processed_audio, array $kept_indices );
```

Fires after the box writes its names and URLs to the `wcap_audio` post meta.
`$processed_audio` is the compacted names/URLs just stored. `$kept_indices` is
the original submitted-row index of each kept row, in kept order, so a consumer
can read its own per-row `$_POST` values without them shifting when an earlier
row was empty or invalid. The box merges onto existing meta, so sub-keys it does
not manage survive a save from here.

## Front-end and validation hooks

### `wcap_allowed_audio_extensions` (filter)

```php
apply_filters( 'wcap_allowed_audio_extensions', array $extensions );
```

Allowed upload/URL extensions. Default
`['mp3','wav','ogg','m4a','aac','flac','wma','webm']`. The value is passed to the
admin JS for client-side validation. The server-side save also validates against
the class's own allowed-types list, so a permanent change should target both.

### `wcap_audio_mime_types` (filter)

```php
apply_filters( 'wcap_audio_mime_types', array $mime_types );
```

Map of extension to MIME type used when rendering the `<audio>` source. Includes
`mp3`, `wav`, `ogg`, `m4a`, `mp4`, `aac`, `flac`, `wma`, `webm`, `opus`, `oga`.
Unknown extensions fall back to `audio/mpeg`.

### `wcap_before_audio_preview` (action)

```php
do_action( 'wcap_before_audio_preview', int $product_id, array $wcap_audio, array $valid_audios );
```

Fires just before the preview container is output on the product page.
`$wcap_audio` is the raw meta; `$valid_audios` is the validated entries about to
render.

### `wcap_after_audio_preview` (action)

```php
do_action( 'wcap_after_audio_preview', int $product_id, array $wcap_audio, array $valid_audios );
```

Fires just after the preview container is output on the product page. Same
arguments as above.
