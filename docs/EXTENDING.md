# Extending Audio Preview

Read this before adding a feature to either tier. The reference of every hook is in
[HOOKS.md](HOOKS.md), which is generated from the source — run `php bin/generate-hook-docs.php`
after adding one, rather than editing it by hand.

## The shape of the pair

The free plugin is the base and owns everything a preview needs: the product box, the reader
that turns stored meta into rows, the renderer, the templates, the placement (hook, shortcode
and block) and the asset rules. Pro **subclasses** free's public renderer and contributes
through free's seams. It does not replace free, and it deactivates itself if free is missing.

That means a feature belongs in free unless it is something a customer pays for. If Pro needs
to change how something renders, the answer is a seam in free — not a second copy of the
method in Pro. Every duplicated renderer this pair has had drifted within one release.

## Adding a setting

Settings resolve through one class so the screen and the player cannot disagree:

1. Declare the default in `WCAP_Pro_Settings::DEFAULTS`.
2. Add the key to the right group in `group_for()` — the group decides which option row it
   lives in.
3. Add the control to the matching `admin/partials/settings-*-clean.php`.
4. If the player needs it, add it to `player_config()`.

Then it reaches the browser automatically: `player_config()` is serialised as JSON by
`wp_add_inline_script`, and the player's `applyConfig()` puts it onto the audio element.

**Two traps this pair has already fallen into.** Do not read the option row directly — a
setting stored as the string `'no'` is truthy, so a raw read means "off" behaves as "on".
And do not reach for `wp_localize_script`: it casts every value to a string, so booleans
arrive as `""` or `"1"` and `=== false` can never match. Both bugs shipped for months.

A setting that does not change what a shopper experiences is worse than a missing one. Wire
it end to end or leave it out.

## Adding an audio format

Two filters, both needed:

- `wcap_allowed_audio_extensions` — what the product box accepts.
- `wcap_audio_mime_types` — what the browser is told the file is.

## Changing where the player appears

- `wcap_preview_hook` moves it to a different WooCommerce action. The default fires inside the
  add-to-cart form, so a second registration on `woocommerce_single_product_summary` (priority
  35) catches products that have no form at all — grouped, external, out of stock, or a
  catalogue-mode store. It renders only when nothing has rendered for that product yet, so
  changing this filter does not produce two players.
- The `[audio_preview product="123"]` shortcode and the `woo-audio-preview/preview` block
  place it anywhere, including themes that never fire the WooCommerce hook.
- `wcap_should_load_assets` opts a page into the stylesheet and script. Assets load on
  product pages only; anything rendering a player elsewhere must answer this filter, or the
  player arrives unstyled.

## Changing what the player renders

- `wcap_public_instance` replaces the renderer object outright. This is how Pro takes over.
- `wcap_render_preview` renders the player for a product id — the shortcode and block fire
  it, so manual placement cannot drift from the hooked output.
- `wcap_before_audio_preview` / `wcap_after_audio_preview` wrap the container.
- `wcap_soundcloud_embed_url` adjusts the SoundCloud widget URL without re-rendering it.

## Changing the product box

The box belongs to free; Pro adds to it through seams rather than drawing a second box:

`wcap_metabox_title`, `wcap_metabox_max_rows`, `wcap_metabox_before_rows`,
`wcap_metabox_row_headings`, `wcap_metabox_row_fields`, `wcap_metabox_after_rows`,
`wcap_metabox_save`.

## Before you ship

- `composer test` and `vendor/bin/phpcs` — both must be clean.
- Turn on `WP_DEBUG_LOG` and load a product page: the storefront must log nothing.
- Check the setting you added actually changes what a shopper sees. Every settings bug in
  this plugin's history passed a code review and failed this step.
