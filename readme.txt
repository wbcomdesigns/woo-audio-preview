=== Audio Preview for WooCommerce ===
Contributors: wbcomdesigns, vapvarun
Tags: audio, woocommerce, preview, music, audio player
Requires at least: 5.0
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.5.1
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

Free support is available through the WordPress.org support forum at wordpress.org/support/plugin/woo-audio-preview/. Documentation is available at docs.wbcomdesigns.com. Pro users receive priority email support with faster response times.

== Screenshots ==

1. **Audio player on the product page**: The responsive audio player displayed on a WooCommerce single product page, showing track name, play/pause button, and progress bar — positioned before the Add to Cart form.
2. **Audio Preview Items meta box — admin**: The product editor meta box showing the 3-field layout with track name inputs, URL fields, and Media Library upload buttons for each preview slot.
3. **CDN URL detection**: The admin interface automatically detecting a pasted Google Drive or SoundCloud URL and confirming the service type with a visual indicator.
4. **Multiple previews on the product page**: A product page displaying all three configured audio previews, each with its own player, allowing customers to sample different sections.
5. **Welcome and setup screen**: The plugin welcome page showing the Quick Start Guide, Key Features summary, and tips for best practices, displayed after activation.
6. **Mobile view of the audio player**: The player displayed on a mobile device, showing the responsive layout with touch-friendly controls that adapt to smaller screen sizes.

== Changelog ==

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
* New: Google Drive integration with iframe-based audio player
* New: SoundCloud native embed using the SoundCloud Widget API
* New: Enhanced Dropbox integration with automatic URL conversion to direct download links
* New: Amazon S3 and CloudFront direct URL support
* New: Automatic CDN service detection with visual confirmation in admin
* New: Support for FLAC, WMA, and WEBM audio formats
* New: OneDrive and Box.com URL detection and conversion
* Improved: Modern, responsive audio player design with play/pause and progress bar
* Improved: Fixed 3-field layout for streamlined product-level management
* Improved: Real-time URL validation with clear error messages in admin
* Improved: Mobile optimisation with touch-friendly player controls
* Fixed: Audio player conflicts when multiple products appear on the same page
* Fixed: Progress bar accuracy across different screen sizes and resolutions
* Fixed: Accessibility issues with screen readers and keyboard navigation

= 1.4.2 =
* Fixed: Compatibility issue with WooCommerce 8.0+ hooks
* Improved: Audio file loading performance
* Fixed: CSS conflicts with certain third-party themes

= 1.4.1 =
* Fixed: Audio preview not displaying on some themes due to hook priority
* Improved: Mobile responsiveness for the player container
* Tested: WordPress 6.4 compatibility confirmed

= 1.4.0 =
* Added: Drag and drop file upload support in the meta box
* Improved: Admin interface with improved UX and field layout
* Added: Audio format validation with user-facing error messages
* Fixed: Multiple audio players on the same page interfering with each other

= 1.3.0 =
* Added: Support for external audio URLs in addition to Media Library uploads
* Improved: Audio player controls design
* Added: Progress bar with elapsed time display
* Improved: Mobile device compatibility

= 1.2.0 =
* Added: Support for multiple audio previews per product
* Improved: Admin interface design and usability
* Fixed: Audio loading failures on certain server configurations
* Improved: Error handling for unsupported formats

= 1.1.0 =
* Added: Core audio preview functionality
* Improved: Initial user interface

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.5.1 =
Security, quality, and theme compatibility release. Fixes page builder conflicts (Elementor, Divi), moves inline scripts to bundled JS, adds developer hooks, and improves uninstall cleanup. Safe to upgrade with no data changes.

= 1.5.0 =
Major update adding CDN support for Google Drive, SoundCloud, Dropbox, and more, along with a redesigned audio player and improved mobile experience. No data loss — existing audio configurations are preserved. Safe to upgrade.

= 1.4.2 =
Important compatibility fix for WooCommerce 8.0+. Recommended for all stores running WooCommerce 8.x or higher.
