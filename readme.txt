=== Audio Preview for WooCommerce ===
Contributors: wbcomdesigns, vapvarun
Tags: audio, woocommerce, preview, music, audio player
Requires at least: 5.0
Tested up to: 6.9.1
Requires PHP: 7.4
Stable tag: 1.5.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Add audio previews to WooCommerce products. Let customers listen before they buy with support for all major audio formats and CDN services.

== Description ==

**Audio Preview for WooCommerce** adds a professional audio player directly to WooCommerce product pages, letting customers listen to audio samples before making a purchase. Whether you sell music tracks, audiobooks, sound effects, or podcasts, giving customers a preview significantly reduces hesitation and increases conversions.

The free version supports up to 3 audio previews per product. Audio files can be uploaded from the WordPress Media Library or linked from popular CDN services including Google Drive, SoundCloud, and Dropbox.

**How It Works**

1. Edit any WooCommerce product in your admin
2. Scroll down to the "Audio Preview Items" meta box
3. Add up to 3 audio previews — enter a name and either upload a file or paste a URL
4. Save the product
5. The audio player appears on the product page automatically, just before the Add to Cart button

= Key Features =

**Audio Format Support**

* MP3 — universal compatibility, recommended for broadest device support
* WAV — high quality, uncompressed audio
* OGG — open format with good compression (note: not supported on iOS devices)
* M4A — Apple's audio format, excellent quality
* AAC — Advanced Audio Coding, widely supported
* FLAC — lossless audio compression for audiophile stores
* WMA — Windows Media Audio
* WEBM — web-optimised audio format

**CDN and Streaming Service Support**

* Google Drive — link shared audio files directly from your Drive
* SoundCloud — embed tracks via the SoundCloud Widget API with native controls
* Dropbox — shared Dropbox links are automatically converted to direct download URLs
* Amazon S3 — professional cloud storage, served directly
* CloudFront — CDN-optimised delivery from AWS CloudFront distributions
* OneDrive — Microsoft OneDrive shared links
* Box.com — Box shared file links
* Any direct public URL — paste any publicly accessible audio file URL

**Audio Player**

* Modern, responsive player with play/pause toggle and progress bar
* Visual playback progress with elapsed time display
* Automatic CDN service detection with optimised playback per service
* Neutral design that automatically adapts to your active theme colours
* SoundCloud tracks use the SoundCloud Widget API for native-quality playback
* Google Drive audio served via iframe player

**Admin Experience**

* Simple 3-field layout in the product editor meta box (name + URL per track)
* Upload audio directly from the WordPress Media Library
* Paste external URLs — CDN service is detected automatically
* Real-time URL validation with error display in the admin
* Settings page under WB Plugins > Audio Preview for WooCommerce

**Performance and Accessibility**

* Audio files are not loaded until the user interacts with the player
* Minified CSS and JavaScript assets included
* RTL stylesheet support for right-to-left languages
* Mobile and tablet optimised — touch-friendly controls on all screen sizes

= Perfect For =

* Music stores selling individual tracks or albums
* Audiobook shops offering chapter excerpts
* Sound effect libraries with demo clips
* Educational platforms with course audio previews
* Podcast stores with episode teasers
* Any WooCommerce store selling digital audio content

= Pro Version =

Upgrade to **Audio Preview for WooCommerce Pro** for advanced features:

* Unlimited audio previews per product (dynamic add/remove)
* Multi-vendor marketplace support — Dokan, WCFM, WC Vendors, WC Marketplace
* Audio watermarking and voice-over protection
* Custom player themes and colour schemes
* Preview duration control (time-limited previews)
* Bulk import functionality
* Priority support

[Learn more about the Pro version](https://wbcomdesigns.com/downloads/woo-audio-preview-pro/)

== Installation ==

1. Upload the `woo-audio-preview` folder to the `/wp-content/plugins/` directory, or install directly through Dashboard > Plugins > Add New.
2. Activate the plugin through the Plugins screen in WordPress.
3. WooCommerce must be installed and active. The plugin will deactivate and show an admin notice if WooCommerce is missing.
4. After activation you will be redirected to the plugin's settings page automatically.
5. Go to **WB Plugins > Audio Preview for WooCommerce** to review the welcome guide and FAQ.
6. Edit any WooCommerce product:
   * Scroll down to find the **Audio Preview Items** meta box below the product description
   * For each preview track, enter a descriptive name (e.g. "Intro Sample", "Chorus Preview")
   * Either click **Media Library** to upload or select an audio file, or paste an external URL
   * Leave a field set empty to skip it — you do not need to use all three fields
7. Click **Update** to save the product. The audio player will appear on the product page immediately.

**Tips for Best Results**

* Use MP3 format for the broadest device compatibility, including iOS
* Keep preview clips between 30 and 60 seconds to showcase quality without giving away the full content
* Use lower quality (128 kbps) preview files and reserve high-quality files for paying customers
* Host large audio files on a CDN service to reduce server bandwidth and improve page load times

== Frequently Asked Questions ==

= What audio formats does this plugin support? =

The plugin supports MP3, WAV, OGG, M4A, AAC, FLAC, WMA, and WEBM audio formats. MP3 is recommended for the best cross-device compatibility. Note: OGG format is not supported on iOS devices (iPhone and iPad), so if your audience is primarily mobile, use MP3.

= How many audio previews can I add per product? =

The free version supports up to 3 audio previews per product. If you need more than 3 — for example, for albums, full audiobook chapter lists, or sound packs — the Pro version offers unlimited previews with dynamic add/remove functionality.

= Can I use audio files hosted on Google Drive, SoundCloud, or Dropbox? =

Yes. The plugin automatically detects URLs from Google Drive, SoundCloud, Dropbox, Amazon S3, CloudFront, OneDrive, and Box.com, and uses the appropriate playback method for each service. Simply paste the sharing URL from any of these services into the URL field — no extra configuration is needed.

= Will the audio player work with my theme? =

Yes. The player uses a neutral design that inherits your theme's colour scheme automatically. It has been tested with major WooCommerce-compatible themes and follows WordPress best practices for script and style enqueuing. RTL stylesheets are also included for right-to-left language themes.

= Is the audio player mobile-friendly? =

Yes. The player is fully responsive and optimised for touch devices. It works on iOS (iPhone and iPad), Android phones and tablets, and all modern desktop browsers. The play/pause and progress bar controls are touch-friendly on smaller screens.

= What is the difference between the free and Pro versions? =

Free version: up to 3 audio previews per product, all major audio formats, CDN service support, responsive player, media library upload.

Pro version adds: unlimited audio previews per product, dynamic add/remove tracks in admin, multi-vendor marketplace support (Dokan, WCFM, WC Vendors, WC Marketplace), audio watermarking, voice-over protection, time-limited preview duration control, custom player themes and colour schemes, bulk import, and priority support.

= Does this plugin work with multi-vendor marketplaces like Dokan? =

Multi-vendor support is a Pro feature. The Pro version includes full support for Dokan Multivendor Marketplace, WCFM Marketplace, WC Vendors, and WC Marketplace, allowing individual vendors to manage their own audio previews from their vendor dashboard.

= How can I protect my audio files from being downloaded? =

For basic protection in the free version: use short preview clips (30–60 seconds), upload lower-quality versions specifically for preview, and host audio through streaming services like SoundCloud which limit direct download access.

The Pro version adds advanced protection features: audio watermarking, voice-over protection, time-limited previews, and right-click protection.

= Can I customise the audio player's appearance? =

In the free version the player uses a neutral style that adapts to your theme automatically. The Pro version adds multiple player themes, custom colour schemes, progress bar style options, and custom CSS support for fully branded players.

= Where can I get support? =

Free support is available through the WordPress.org support forum at wordpress.org/support/plugin/woo-audio-preview/. Pro users receive priority email support with faster response times.

= Are there developer hooks I can use? =

Yes. The plugin exposes 14 filters and actions for developers and for the Pro add-on, including the Free-to-Pro seam filters (`wcap_public_instance`, `wcap_preview_hook`, `wcap_should_load_assets`, `wcap_soundcloud_embed_url`), the product meta box hooks, and the front-end render hooks. Each one is documented with its arguments and purpose in `docs/HOOKS.md` in the GitHub repository at github.com/wbcomdesigns/woo-audio-preview.

== Screenshots ==

1. **Audio player on the product page**: The responsive audio player displayed on a WooCommerce single product page, showing track name, play/pause button, and progress bar — positioned before the Add to Cart form.
2. **Audio Preview Items meta box — admin**: The product editor meta box showing the 3-field layout with track name inputs, URL fields, and Media Library upload buttons for each preview slot.
3. **CDN URL detection**: The admin interface automatically detecting a pasted Google Drive or SoundCloud URL and confirming the service type with a visual indicator.
4. **Multiple previews on the product page**: A product page displaying all three configured audio previews, each with its own player, allowing customers to sample different sections.
5. **Welcome and setup screen**: The plugin welcome page showing the Quick Start Guide, Key Features summary, and tips for best practices, displayed after activation.
6. **Mobile view of the audio player**: The player displayed on a mobile device, showing the responsive layout with touch-friendly controls that adapt to smaller screen sizes.

== Changelog ==

= 1.5.3 - September 2026 =

* Improve  - Admin settings radio options no longer wrap mid-label and stack cleanly on phones.
* Dev      - Added a developer hooks reference, refreshed the translation template, and removed duplicated internal code.

= 1.5.2 - September 2026 =

Restores the free/Pro seams the Pro add-on depends on, so Pro settings take effect on the front end.

* New      - Filters wcap_public_instance, wcap_preview_hook, wcap_should_load_assets and wcap_soundcloud_embed_url, plus a should_load_assets() method, so the Pro add-on supplies its own player, asset loading and render position through the free plugin.
* Improve  - Preview assets load on product pages by default and can be widened to shop and category archives by the Pro add-on.
* Fix      - Player renderer helpers are protected so the Pro subclass extends one renderer instead of drawing a second player.
* Compat   - Aligned with Audio Preview Pro 2.2.2. Install both updates together.

= 1.5.1 =
* Fixed: WordPress Coding Standards (WPCS) compliance issues
* Fixed: Plugin Check tool flagged issues resolved
* Fixed: Input sanitization and output escaping across admin and frontend
* Fixed: Removed debug error_log() statements from production code
* Fixed: Replaced `global $post` with WooCommerce-safe product retrieval for page builder compatibility (Elementor, Divi, Beaver Builder)
* Fixed: Inline JavaScript for Google Drive and SoundCloud players moved to main JS file to prevent function redefinition per track
* Fixed: Removed `!important` from container padding to avoid theme conflicts
* New: Added `wcap_before_audio_preview` and `wcap_after_audio_preview` action hooks for developer extensibility
* Improved: Proper data cleanup on plugin uninstall (options and post meta)
* Improved: Option autoload set to false for non-critical data
* Improved: Google Drive and SoundCloud player styles moved from inline to main stylesheet
* Improved: Main JavaScript loaded in footer for better page performance
* Tested: Confirmed compatibility with WordPress 6.9.1 and popular themes (Storefront, Astra, OceanWP, Kadence, GeneratePress)

= 1.5.0 =
* Added: Support for multiple audio formats, including external URLs.
* Improved: Frontend audio player and admin labels for better format handling.
* Added: Secure file upload validation and enhanced input sanitization.
* Fixed: AJAX security vulnerabilities and error handling during file uploads.
* Fixed: Debug log issues and PHP warnings on plugin activation.
* Updated: JavaScript validation logic to support various file types.
* Improved: FAQ section with clearer file format details.
* Optimized: Table layout responsiveness and overall performance.
* Cleaned: Removed unused code and resolved PHPCS issues.
* Improved: Language strings and code structure for better maintainability.
* Added: Minified CSS/JS and RTL compatibility for improved loading and accessibility.

= 1.4.5 =
* Fix: (#26) Compatibility check with PHP 8.0
* Fix: (#27) Fixed check for dependency plugin 
* Fix: (#28) Update Faq
* Fix: (#29) Fixed added tooltip for meta boxes  
* Fix: Compatibility check with WordPress 6.5.0  

= 1.4.4 =
* Managed: (#24)  Frontend audio player UI
* Managed: (#24) Preview item button UI
* Fix: Plugin redirect issue when multiple plugins activate at the same time

= 1.4.3 =
* Fix - (#22)Fixed audio listing with Audio Preview for WooCommerce Pro plugins
* Fix - Update compatibility with WooCommerce latest version

= 1.4.2 =
* Fix - updated admin ui

= 1.4.1 =
* Fix - phpcs fixes
* Fix - Updated name

= 1.4.0 =
* Fix - phpcs fixes
* Fix - Removed install plugin button from wrapper

= 1.3.0 =
* Fix - Update frontend UI without playlist
* Fix - #Fix audio player clickable issue

= 1.2.0 =
* Fix - Fixed #12 - Add link is not clickable
* Fix - Fixed #14 - Notices and warnings

= 1.1.0 =
* Fix - WooCommerce v4.0.0 Compatible.

= 1.0.4 =
* Fix - WooCommerce v3.6.2 Compatible.

= 1.0.3 =
* Fix - WooCommerce v3.5.2 Compatible.

= 1.0.2 =
* Fix - Compatible with latest WordPress.

= 1.0.1 =
* Plugin Testing with current version of the WordPress and WooCommerce.

= 1.0.0 =
* first version.

== Upgrade Notice ==

= 1.5.1 =
Security, quality, and theme compatibility release. Fixes page builder conflicts (Elementor, Divi), moves inline scripts to bundled JS, adds developer hooks, and improves uninstall cleanup. Safe to upgrade with no data changes.

= 1.5.0 =
Major update adding CDN support for Google Drive, SoundCloud, Dropbox, and more, along with a redesigned audio player and improved mobile experience. No data loss — existing audio configurations are preserved. Safe to upgrade.

= 1.4.2 =
Important compatibility fix for WooCommerce 8.0+. Recommended for all stores running WooCommerce 8.x or higher.
