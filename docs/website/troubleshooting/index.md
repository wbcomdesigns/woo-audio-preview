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
  Spotify, Dropbox, Amazon S3, CloudFront, OneDrive, Box.com).

## A cloud or streaming link will not play

Confirm the link is from a supported service - Google Drive, SoundCloud,
Spotify, Dropbox, Amazon S3, CloudFront, OneDrive, or Box.com - and that the
file or track is publicly accessible. Spotify links render Spotify's own
embedded player (a 30-second preview for logged-out visitors). For any other
host, use a direct audio file URL, upload the file to the Media Library, or
switch to one of the supported services. MediaFire is not supported.

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
