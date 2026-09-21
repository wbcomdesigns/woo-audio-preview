# Features

What the free plugin does, verified against the plugin code.

## Audio player

- Play/pause button with a progress bar and elapsed/total time display.
- Neutral styling that inherits your theme's colours (no hardcoded palette).
- Audio is not fetched until the visitor presses play (`preload="none"`).
- Multiple previews on one product render as a stacked list under an "Audio
  Previews" heading.
- Google Drive and SoundCloud links render in an embedded iframe player;
  everything else uses the native HTML5 `<audio>` element.

## Per-product previews

- Up to 3 previews per product in the free version.
- Each preview is a name plus a source (a Media Library upload or a URL).
- The player appears on the single product page, by default just before the
  Add to Cart form.

## Admin experience

- A dedicated "Audio Preview Items" meta box in the product editor.
- Upload from the WordPress Media Library or paste an external URL.
- The recognised cloud/streaming service is detected and shown next to the URL
  field.
- URLs are validated when you save; rejected rows are reported back with the
  reason instead of being silently dropped.

## Performance and accessibility

- Minified CSS and JavaScript are shipped and used automatically (source files
  load when `SCRIPT_DEBUG` is on).
- RTL stylesheets are included.
- Player buttons carry `aria-label`s naming the track.
- Responsive layout with touch-friendly controls on small screens.

See the following pages for the exact format and service support:

- Audio formats
- Cloud and streaming services
