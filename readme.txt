=== Audio Preview for WooCommerce ===
Contributors: wbcomdesigns, vapvarun
Donate link: https://wbcomdesigns.com/
Tags: woocommerce, audio, preview, music, soundcloud
Requires at least: 5.0
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.5.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Add professional audio previews to your WooCommerce products. Let customers listen before they buy with support for all major audio formats and CDN services.

== Description ==

**Audio Preview for WooCommerce** transforms your online store by adding professional audio preview functionality to any WooCommerce product. Perfect for music stores, audiobook shops, sound effect libraries, and any business selling digital or physical audio content.

### Key Features

* **Multiple Audio Formats**: Full support for MP3, WAV, OGG, M4A, AAC, FLAC, WMA, and WEBM files
* **CDN Integration**: Direct support for Google Drive, SoundCloud, Dropbox, Amazon S3, and CloudFront
* **Clean Interface**: Modern, responsive audio player that adapts to any theme
* **Easy Management**: Simple 3-field layout for quick audio preview setup
* **Mobile Optimized**: Touch-friendly controls and responsive design
* **Smart Validation**: Real-time URL validation with automatic CDN detection

### Perfect For

* **Music Stores**: Preview tracks before purchase
* **Audiobook Shops**: Sample chapters and excerpts
* **Sound Libraries**: Demo sound effects and loops
* **Educational Content**: Course previews and lessons
* **Podcast Stores**: Episode samples and teasers

### How It Works

1. Edit any WooCommerce product
2. Find the "Audio Preview Items" section
3. Add up to 3 audio previews with names and URLs
4. Save your product - previews appear automatically on the frontend

The plugin supports both direct file uploads through the WordPress Media Library and external URLs from popular services like Google Drive, SoundCloud, and Dropbox.

### Audio Sources Supported

* **Local Files**: Upload directly to WordPress Media Library
* **Google Drive**: Share audio files from your Google Drive
* **SoundCloud**: Embed tracks directly from SoundCloud
* **Dropbox**: Use shared Dropbox links for audio hosting
* **Amazon S3**: Professional cloud storage integration
* **CloudFront**: CDN-optimized audio delivery
* **Direct URLs**: Any publicly accessible audio file

### Technical Features

* **Lazy Loading**: Audio files load only when needed for better performance
* **Progress Tracking**: Visual progress bars with time display
* **Error Handling**: Graceful fallback for unsupported formats
* **Accessibility**: Screen reader support and keyboard navigation
* **Security**: XSS protection and input validation
* **Theme Compatible**: Neutral design works with any WordPress theme

### Pro Version Available

Upgrade to **Audio Preview Pro** for advanced features:

* Unlimited audio previews per product
* Multi-vendor marketplace support (Dokan, WCFM, WC Vendors)
* Audio watermarking and protection
* Custom player themes and colors
* Bulk import functionality
* Preview duration control
* Advanced analytics and tracking
* Priority support

[Learn more about Pro features](https://wbcomdesigns.com/downloads/woo-audio-preview-pro/)

### Support & Documentation

* [Complete Documentation](https://docs.wbcomdesigns.com/doc_category/woo-audio-preview/)
* [Community Support Forum](https://wordpress.org/support/plugin/woo-audio-preview/)
* [Video Tutorials](https://wbcomdesigns.com/woo-audio-preview-tutorials/)

== Installation ==

### Automatic Installation

1. Go to your WordPress admin area
2. Navigate to Plugins > Add New
3. Search for "Audio Preview for WooCommerce"
4. Click "Install Now" and then "Activate"

### Manual Installation

1. Download the plugin zip file
2. Upload the `woo-audio-preview` folder to `/wp-content/plugins/`
3. Activate the plugin through the WordPress admin Plugins page

### Setup

1. Ensure WooCommerce is installed and activated
2. Edit any WooCommerce product
3. Scroll down to find the "Audio Preview Items" meta box
4. Add audio previews using either:
   * **Media Library**: Click "Media Library" to upload or select existing audio files
   * **External URLs**: Paste URLs from Google Drive, SoundCloud, Dropbox, etc.
5. Save the product
6. View the product on your store to see the audio previews

### Requirements

* WordPress 5.0 or higher
* WooCommerce 4.0 or higher
* PHP 7.4 or higher

== Frequently Asked Questions ==

= What audio formats are supported? =

The plugin supports all major audio formats including MP3, WAV, OGG, M4A, AAC, FLAC, WMA, and WEBM. It also works with CDN services like Google Drive, SoundCloud, and Dropbox.

= How many audio previews can I add per product? =

The free version allows up to 3 audio previews per product. The Pro version offers unlimited audio previews.

= Can I use audio files from Google Drive or SoundCloud? =

Yes! The plugin automatically detects and supports URLs from Google Drive, SoundCloud, Dropbox, Amazon S3, CloudFront, and other CDN services.

= Will this work with my theme? =

Yes, the plugin uses a neutral design that automatically adapts to your theme colors and styling. It's been tested with popular themes and follows WordPress best practices.

= Is the audio player mobile-friendly? =

Absolutely! The audio player is fully responsive and optimized for touch devices. It works great on phones, tablets, and desktop computers.

= Can customers download the audio files? =

No, the plugin is designed for preview purposes only. Customers can listen to the audio but cannot directly download the files through the player.

= Does this work with multi-vendor marketplaces? =

Multi-vendor support is available in the Pro version, with full compatibility for Dokan, WCFM, WC Vendors, and other marketplace plugins.

= How do I protect my audio content? =

For basic protection, use shorter preview clips and lower quality versions. The Pro version includes advanced protection features like watermarking and secure streaming.

= Can I customize the player appearance? =

The free version uses a neutral design that adapts to your theme. The Pro version offers multiple player themes, custom colors, and advanced styling options.

= Where can I get support? =

Free support is available through the WordPress.org support forum. Pro users receive priority email support with faster response times.

== Screenshots ==

1. Modern audio preview player on product page - shows clean interface with play controls
2. Admin interface for adding audio previews - simple 3-field layout with media library integration
3. CDN service detection - automatic recognition of Google Drive, SoundCloud, and other services
4. Mobile responsive design - touch-friendly controls that work on all devices
5. Welcome screen with setup instructions - guided tour for new users

== Changelog ==

= 1.5.0 =
* **Major Update: Enhanced CDN Support**
* Added: Google Drive integration with iframe player
* Added: SoundCloud native embed support
* Added: Enhanced Dropbox and Amazon S3 compatibility
* Added: Automatic CDN service detection with visual indicators
* Improved: Modern, responsive audio player design
* Improved: Fixed 3-field layout for streamlined management
* Improved: Real-time URL validation with smart error handling
* Improved: Mobile optimization with touch-friendly controls
* Added: Support for FLAC, WMA, and WEBM audio formats
* Enhanced: Security with XSS protection and input validation
* Optimized: Asset loading with intelligent minification
* Fixed: Audio player conflicts with multiple products
* Fixed: Progress bar accuracy on different screen sizes
* Fixed: Accessibility issues with screen readers

= 1.4.2 =
* Fixed: Compatibility issue with WooCommerce 8.0+
* Improved: Audio loading performance
* Fixed: CSS conflicts with some themes

= 1.4.1 =
* Fixed: Audio preview not showing on some themes
* Improved: Mobile responsiveness
* Updated: WordPress 6.4 compatibility

= 1.4.0 =
* Added: Drag and drop file upload support
* Improved: Admin interface with better UX
* Added: Audio format validation
* Fixed: Multiple audio players on same page

= 1.3.0 =
* Added: Support for external audio URLs
* Improved: Audio player controls
* Added: Progress bar functionality
* Enhanced: Mobile device compatibility

= 1.2.0 =
* Added: Multiple audio preview support
* Improved: Admin interface design
* Fixed: Audio loading issues on some servers
* Added: Better error handling

= 1.1.0 =
* Added: Audio preview functionality
* Improved: User interface
* Fixed: Initial release bugs

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.5.0 =
Major update with enhanced CDN support, modern player design, and improved mobile experience. Automatic upgrade with no data loss.

= 1.4.2 =
Important compatibility update for WooCommerce 8.0+. Recommended upgrade for all users.

= 1.4.0 =
New drag and drop upload feature and improved admin interface. Enhanced user experience for product management.

== Additional Info ==

### Browser Compatibility

* Chrome (recommended)
* Firefox
* Safari
* Edge
* Mobile browsers (iOS Safari, Chrome Mobile)

### Performance

* Lazy loading for optimal page speed
* CDN support for reduced server load
* Optimized JavaScript and CSS delivery
* Minimal database queries

### Security

* Input sanitization and validation
* XSS protection
* Nonce verification for AJAX requests
* Secure file handling

### Developers

The plugin follows WordPress coding standards and provides hooks for customization:

* `wcap_allowed_audio_extensions` - Filter audio file types
* `wcap_audio_mime_types` - Modify MIME type detection
* `wcap_before_audio_preview` - Action before preview display
* `wcap_after_audio_preview` - Action after preview display

For complete developer documentation, visit our [GitHub repository](https://github.com/wbcomdesigns/woo-audio-preview).

### Credits

Developed by [Wbcom Designs](https://wbcomdesigns.com/) - WordPress and BuddyPress specialists since 2015.

### Translations

The plugin is translation-ready and includes POT files. Help us translate into your language!

* English (default)
* Spanish - Coming soon
* French - Coming soon
* German - Coming soon

Contribute translations at [translate.wordpress.org](https://translate.wordpress.org/projects/wp-plugins/woo-audio-preview/)