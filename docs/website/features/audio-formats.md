# Audio formats

## Supported formats

Uploaded files and direct URLs are accepted in these formats:

| Format | Notes |
|---|---|
| MP3 | Universal compatibility. Recommended for the broadest device support, including iOS. |
| WAV | High quality, uncompressed. |
| OGG | Open format, good compression. Not supported on iOS (iPhone and iPad). |
| M4A | Apple audio format. |
| AAC | Advanced Audio Coding. |
| FLAC | Lossless compression. |
| WMA | Windows Media Audio. |
| WEBM | Web-optimised audio. |

These eight extensions are the ones the admin URL validator accepts for direct
file URLs (`mp3`, `wav`, `ogg`, `m4a`, `aac`, `flac`, `wma`, `webm`).

## How the format is determined

- For a direct URL, the extension is read from the URL (query strings are
  stripped first) and matched against the list above.
- The player sets the `<source>` MIME type from the extension. Extensions it
  does not recognise fall back to `audio/mpeg`.
- Developers can add or override extensions and MIME types with the
  `wcap_allowed_audio_extensions` and `wcap_audio_mime_types` filters. See the
  Developer Guide.

## Tips

- Use MP3 for the widest compatibility.
- Keep preview clips short (roughly 30 to 60 seconds).
- Serve lower-bitrate preview files and reserve full-quality audio for buyers.
