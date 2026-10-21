# Cloud and streaming services

Instead of uploading a file you can paste a URL. Some services get special
handling so they play correctly.

## Services that work end to end

These are recognised by the admin URL validator (so they save) and have a
matching playback path on the product page:

| Service | Playback |
|---|---|
| Google Drive | Embedded iframe player. The sharing link is used to build a Drive preview embed. |
| SoundCloud | Embedded iframe player using the SoundCloud Widget API. |
| Dropbox | Sharing links are converted to a direct-download URL and played in the native audio player. |
| Amazon S3 | Direct file URL played in the native audio player. |
| CloudFront | Direct file URL played in the native audio player. |
| Any direct public URL | Any publicly reachable file ending in a supported audio extension. |

When you paste a recognised URL, the admin shows a small "&lt;service&gt; link
detected" indicator next to the field, and the front-end player shows a service
badge on the preview.

## Services with limited support

The front-end renderer contains conversion or detection code for the services
below, but they do not reliably pass the admin URL validator, so pasting their
sharing links may be rejected on save with an "Invalid audio file type" error:

- OneDrive
- Box.com
- MediaFire
- Spotify (detected in admin only; there is no Spotify playback path, so a
  saved Spotify page URL will not play)

For these, upload the file to the Media Library or host it somewhere that
returns a direct audio file URL, or use one of the fully supported services
above. See Troubleshooting for details.

## Notes on cloud links

- The file must be publicly accessible. Private or permission-gated links will
  not play for your customers.
- Google Drive playback depends on the file being shared publicly; Drive may
  still gate very large files.
