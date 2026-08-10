# Hooks — .

Generated from the source by `bin/generate-hook-docs.php`. Do not edit by hand: run the
script instead, or the reference drifts from the code the way the last one did.

18 extension points: 8 actions, 10 filters.

| Hook | Type | Declared in | What it is for |
|---|---|---|---|
| `wcap_after_audio_preview` | action | `public/class-wc-audio-preview-public.php:391` | Fires after the audio preview container is rendered. |
| `wcap_allowed_audio_extensions` | filter | `admin/class-wc-audio-preview-admin.php:223` | Filter the audio extensions the product box will accept. The admin-side half of adding a format: this decides what an owner can upload or paste. `wcap_audio_mime_types` is the other half, telling the  |
| `wcap_audio_mime_types` | filter | `public/class-wc-audio-preview-public.php:800` | Filter the extension-to-MIME map used for the <audio> source type. Add an entry here when teaching the player a new audio format, so the browser is told what it is being handed. Pair it with `wcap_all |
| `wcap_before_audio_preview` | action | `public/class-wc-audio-preview-public.php:335` | Fires before the audio preview container is rendered. |
| `wcap_metabox_after_rows` | action | `admin/class-wc-audio-preview-admin.php:551` | Render below the preview rows. Where the Pro plugin puts Add More and Bulk Import. |
| `wcap_metabox_before_rows` | action | `admin/class-wc-audio-preview-admin.php:507` | Render above the preview rows. The seam the Pro plugin uses to add the player theme control, instead of registering a second meta box beside this one. |
| `wcap_metabox_max_rows` | filter | `admin/class-wc-audio-preview-admin.php:350` | Filter the number of preview rows. |
| `wcap_metabox_row_fields` | action | `admin/class-wc-audio-preview-admin.php:462` | Render extra cells inside a preview row. Cells, not a block: the row is a table row, so an extension contributing here must emit <td>. The Pro plugin puts its per-track preview duration in one. |
| `wcap_metabox_row_headings` | action | `admin/class-wc-audio-preview-admin.php:526` | Render extra column headings. Paired with wcap_metabox_row_fields: an extension adding a cell to each row adds its heading here, so the columns line up. |
| `wcap_metabox_save` | action | `admin/class-wc-audio-preview-admin.php:688` | Save extra fields contributed to the preview rows. The seam the Pro plugin uses to store its durations and player theme, so there is one meta box with one save handler rather than two boxes racing eac |
| `wcap_metabox_title` | filter | `admin/class-wc-audio-preview-admin.php:388` | Filter the meta box title. A plain title. The supported-format list used to live in the heading, which made it three lines long on a narrow screen and read as a warning rather than as help - it is one |
| `wcap_preview_hook` | filter | `includes/class-wc-audio-preview.php:303` | Filter where the preview is rendered on the product page. The free plugin renders before the add-to-cart form. Pro lets the owner choose, and sets that choice here rather than registering a second ren |
| `wcap_product_audio` | filter | `includes/class-wcap-audio.php:91` | Filter a product's audio previews after normalisation. |
| `wcap_product_metabox_handled` | filter | `admin/class-wc-audio-preview-admin.php:373` | Whether something else is providing the product's audio preview box. The Pro plugin renders a richer box - unlimited rows, per-track duration, reordering - and returns true here so only one is shown.  |
| `wcap_public_instance` | filter | `includes/class-wc-audio-preview.php:274` | Filter the object that renders audio previews on the front end. This is the seam the Pro plugin uses to take over rendering: it returns a subclass of Wc_Audio_Preview_Public with the premium behaviour |
| `wcap_render_preview` | action | `includes/class-wcap-placement.php:81` | Render the preview for the current global product. Whichever tier owns the renderer answers this, so manual placement cannot drift from what the WooCommerce hook produces. |
| `wcap_should_load_assets` | filter | `public/class-wc-audio-preview-public.php:120` | Filter whether the preview assets load on this request. The seam for anything that renders a player outside a product page - a shortcode in a page, a block in a template, or Pro's shop-page badges. |
| `wcap_soundcloud_embed_url` | filter | `public/class-wc-audio-preview-public.php:553` | Filter the SoundCloud embed URL. The seam Pro uses to add its player parameters. Pro used to override this whole method to change one string, and the copy had already drifted: it lost the button's ari |
