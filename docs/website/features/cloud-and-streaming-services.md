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
| Spotify | Embedded Spotify player. Paste a Spotify track link; logged-out visitors get the 30-second preview. |
| Dropbox | Sharing links are converted to a direct-download URL and played in the native audio player. |
| Amazon S3 | Direct file URL played in the native audio player. |
| CloudFront | Direct file URL played in the native audio player. |
| OneDrive | Sharing links are converted to a direct-download URL and played in the native audio player. |
| Box.com | Sharing links are converted to a direct-download URL and played in the native audio player. |
| Any direct public URL | Any publicly reachable file ending in a supported audio extension. |

When you paste a recognised URL, the admin shows a small "&lt;service&gt; link
detected" indicator next to the field, and the front-end player shows a service
badge on the preview.

## Notes on cloud links

- The file must be publicly accessible. Private or permission-gated links will
  not play for your customers.
- Google Drive playback depends on the file being shared publicly; Drive may
  still gate very large files.
