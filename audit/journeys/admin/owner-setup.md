---
journey: owner-setup
plugin: woo-audio-preview
priority: critical
roles: [admin, anon]
covers: [metabox, accepted-urls, hub-icons, pro-options]
---

# The owner sets up audio previews

## Steps

### 1. Zero-config + hub icons
- **Action**: activate with WooCommerce; open WB Plugins hub (`admin.php?page=wbcomplugins`) with ONLY Audio Preview active.
- **Expect**: the hub icons render (not empty placeholders). `card:10299581099`
- **Check** (`states:hub-icons-render`): `assets/vendor/lucide.min.js` is present in the installed plugin and the enqueue does not 404.

### 2. Add a preview, each URL type
- **Action**: edit a product, open the Audio Preview metabox, add in turn: an uploaded file, a Media Library file, a direct external URL, and each advertised CDN/streaming service URL. Save.
- **Expect**: every URL type the player can actually play is accepted; a type the player cannot play is either accepted-and-playable or not advertised at all. No "Invalid audio file type" for a supported type. `card:10323207441`
- **Check** (`states:supported-url-saves`): the admin accepted-list and the public rendered-list match.

### 3. Pro options
- **Action**: Pro active - set watermark on, archive badge on, archive position = before_price.
- **Expect**: the settings screen exposes the archive position (not fixed at after-title); placeholder defaults match the resolver defaults. `card:10323210155`

### 4. 390 and dark
- **Action**: repeat the metabox and settings at 390px and dark.
- **Expect**: no overflow, controls usable.

## Pass criteria
1. Previews save for every advertised type; the hub icons render; Pro options are controllable.
