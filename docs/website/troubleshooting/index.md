# Troubleshooting

## The plugin deactivated itself on activation

It requires WooCommerce. Install and activate WooCommerce, then activate Audio
Preview for WooCommerce again.

## A row was not saved

The editor shows a notice explaining why:

- **Missing name.** A row needs both a name and a URL. Add a name and save
  again.
- **Invalid audio file type / unrecognised link.** The URL did not end in a
  supported extension (mp3, wav, ogg, m4a, aac, flac, wma, webm) and was not
  recognised as a supported service. Upload the file to the Media Library, or
  use a direct file URL, or a supported service (Google Drive, SoundCloud,
  Dropbox, Amazon S3, CloudFront).

## A OneDrive, Box.com, MediaFire, or Spotify link will not save or will not play

These services are not reliably supported. OneDrive, Box.com, and MediaFire
sharing links usually fail validation on save because they carry no audio file
extension. Spotify links may save but will not play, because there is no
Spotify playback path. Use one of the fully supported services, upload the file
to the Media Library, or host it somewhere that returns a direct audio file URL.

## The player does not appear on the product page

- Confirm the product has at least one row with both a name and a URL saved.
- The player renders on `woocommerce_before_add_to_cart_form` by default. If
  your theme or a page builder removes or replaces the Add to Cart area, the
  player may not have a hook to render on. A developer can move it with the
  `wcap_preview_hook` filter.

## Audio does not play, or plays on desktop but not on iOS

- Make sure the file/link is publicly accessible. Private cloud links will not
  play for customers.
- OGG does not play on iOS. Provide an MP3 instead.
- For Google Drive, the file must be shared publicly; very large files may be
  gated by Drive.

## Assets do not load / player looks unstyled

- The player CSS/JS load on single product pages by default. If your product is
  shown somewhere else (a custom template or archive), the assets may not
  enqueue there in the free version. A developer can widen this with the
  `wcap_should_load_assets` filter.

## Validation error notices keep showing

The last 10 admin messages are stored in the `wcap_admin_errors` option and are
cleared once shown on a plugin or product screen. Loading one of those screens
clears them.
