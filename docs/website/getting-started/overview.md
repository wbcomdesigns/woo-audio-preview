# Overview

Audio Preview for WooCommerce adds an audio player to WooCommerce single product
pages so customers can listen to a sample before they buy. It suits music
tracks, audiobook excerpts, sound effects, and podcast teasers.

## What it does

- Adds an "Audio Preview Items" meta box to the WooCommerce product editor.
- Lets you attach up to 3 audio previews per product in the free version (each a
  name plus a URL, either uploaded from the Media Library or pasted in).
- Renders a responsive play/pause player on the product page, by default just
  before the Add to Cart form.
- Accepts direct audio file URLs and links from several cloud and streaming
  services, choosing the right playback method per service.

## How it works

1. Edit a WooCommerce product and open the Audio Preview Items meta box.
2. For each preview, enter a name and either upload a file or paste a URL.
3. Save the product. The player appears on the product page automatically.

The player loads no audio until the visitor presses play (`preload="none"`), so
adding previews does not slow the initial product page load.

## Free and Pro

The free plugin covers up to 3 previews per product, all supported audio
formats, and the cloud/streaming URL support described in these docs. The
separate Audio Preview for WooCommerce Pro add-on extends the same player with
unlimited previews, multi-vendor support, player themes, preview duration
control, watermarking, and bulk import. Pro is built on the free plugin's
developer hooks (see the Developer Guide) rather than replacing it.
