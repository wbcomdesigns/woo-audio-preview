# Audio Preview for WooCommerce - FAQ Documentation

## Table of Contents
1. [General Questions](#general-questions)
2. [Installation & Setup](#installation--setup)
3. [Features & Functionality](#features--functionality)
4. [CDN & External Services](#cdn--external-services)
5. [Troubleshooting](#troubleshooting)
6. [Security & Protection](#security--protection)
7. [Compatibility](#compatibility)
8. [Pro Version](#pro-version)
9. [Technical Questions](#technical-questions)
10. [Support & Resources](#support--resources)

---

## General Questions

### What is Audio Preview for WooCommerce?
Audio Preview for WooCommerce is a plugin that allows you to add audio samples to your WooCommerce products. Customers can listen to previews before purchasing, making it perfect for music stores, audiobook shops, podcast platforms, and any business selling audio content.

### Who should use this plugin?
This plugin is ideal for:
- 🎵 Music producers and record labels
- 📚 Audiobook publishers and authors  
- 🎙️ Podcast creators and networks
- 🎼 Sound effect and sample libraries
- 🎸 Musical instrument stores (demos)
- 🎓 Online course creators (preview lessons)
- 🎬 Voice-over artists and studios

### What's the difference between Free and Pro versions?

| Feature | Free | Pro |
|---------|------|-----|
| Audio previews per product | 3 (fixed fields) | Unlimited |
| Dynamic add/remove buttons | ❌ | ✅ |
| CDN support | ✅ | ✅ |
| Multi-vendor support | ❌ | ✅ |
| Custom player themes | ❌ | ✅ |
| Preview duration control | ❌ | ✅ |
| Audio watermarking | ❌ | ✅ |
| Bulk import features | ❌ | ✅ |
| Priority support | Community | Priority Email |

### Is there a demo available?
Yes! You can see the plugin in action at our demo site. Visit the documentation page for demo links showing both free and pro versions.

---

## Installation & Setup

### What are the minimum requirements?
- WordPress 5.0 or higher
- WooCommerce 3.0 or higher  
- PHP 7.2 or higher
- MySQL 5.6 or higher
- 128MB memory limit (256MB recommended)

### How do I install the plugin?

**Method 1: WordPress Admin**
1. Go to Plugins → Add New
2. Search for "Audio Preview for WooCommerce"
3. Click Install Now → Activate

**Method 2: Upload**
1. Download the plugin ZIP file
2. Go to Plugins → Add New → Upload Plugin
3. Choose file → Install Now → Activate

### Why isn't the plugin working after activation?
Common causes:
1. **WooCommerce not active**: The plugin requires WooCommerce
2. **Caching issues**: Clear your browser and site cache
3. **Theme conflicts**: Try switching to a default theme
4. **PHP version**: Ensure PHP 7.2 or higher

### Where do I find the audio preview fields?
1. Edit any WooCommerce product
2. Scroll down below the product description
3. Look for "Audio Preview Items" meta box
4. If not visible, check Screen Options at the top

### Can I change where the audio player appears?
Yes, the player appears before the Add to Cart button by default. To change this:
```php
// Remove default position
remove_action('woocommerce_before_add_to_cart_form', array($plugin_public, 'wcap_add_preview_field'), 0);

// Add to new position
add_action('woocommerce_after_single_product_summary', array($plugin_public, 'wcap_add_preview_field'), 15);
```

---

## Features & Functionality

### What audio formats are supported?
The plugin supports all major audio formats:
- **MP3** - Universal compatibility (recommended)
- **WAV** - High quality, larger files
- **OGG** - Open format, good compression (not iOS)
- **M4A** - Apple's format, good quality
- **AAC** - Advanced audio coding
- **FLAC** - Lossless compression
- **WMA** - Windows Media Audio
- **WEBM** - Web-optimized format

### How many audio previews can I add per product?
- **Free Version**: Up to 3 audio previews (fixed fields)
- **Pro Version**: Unlimited audio previews with dynamic add/remove

### Can I use the WordPress Media Library?
Yes! Click the "Media Library" button to:
- Select existing audio files
- Upload new audio files
- Use WordPress's built-in media manager
- Organize files in folders (with plugins)

### What's the recommended preview length?
We recommend:
- **Optimal**: 30-60 seconds
- **Maximum**: 90 seconds  
- **Minimum**: 15 seconds

Shorter previews protect your content while giving customers enough to make a decision.

### Can I reorder audio previews?
- **Free Version**: No, the order is fixed (1, 2, 3)
- **Pro Version**: Yes, drag-and-drop reordering available

### Does it work on mobile devices?
Yes! The player is fully responsive and works on:
- iOS devices (iPhone, iPad)
- Android phones and tablets
- All modern mobile browsers
- Touch-friendly controls

**Note**: OGG format doesn't work on iOS devices. Use MP3 for universal compatibility.

---

## CDN & External Services

### What CDN services are supported?

1. **Google Drive**
   - Free storage up to 15GB
   - Easy sharing options
   - Reliable streaming

2. **Dropbox**  
   - Simple file management
   - Direct link support
   - Good for teams

3. **SoundCloud**
   - Built for audio streaming
   - Social features
   - Embed player support

4. **Amazon S3**
   - Professional CDN
   - Scalable solution
   - Pay-per-use pricing

5. **CloudFront**
   - Global CDN network
   - Fast delivery
   - Works with S3

6. **Box.com**
   - Business-focused
   - Secure sharing
   - Collaboration tools

### How do I use Google Drive for audio files?

1. **Upload your audio file** to Google Drive
2. **Right-click** the file → "Get link"  
3. **Change sharing** to "Anyone with the link can view"
4. **Copy the link** and paste in the plugin

**Important**: The file must be publicly accessible. Private files won't work.

### How do I use Dropbox links?

1. **Upload** audio to Dropbox
2. Click **Share** → **Create link**
3. **Copy** the sharing link (ends with ?dl=0)
4. **Paste** directly - plugin handles conversion

The plugin automatically converts Dropbox sharing links to direct playback links.

### Why should I use a CDN?

**Benefits**:
- ⚡ Faster loading times globally
- 💾 Save your server bandwidth  
- 📊 Better performance metrics
- 🌍 Improved international delivery
- 💰 Reduce hosting costs
- 🔧 Easier file management

### What if my CDN link stops working?

Common solutions:
1. **Check sharing permissions** - May have been changed
2. **Verify file exists** - Not deleted or moved
3. **Test in browser** - Ensure link is accessible
4. **Regenerate link** - Create new sharing link
5. **Check CDN quotas** - Some services have bandwidth limits

---

## Troubleshooting

### Audio won't play - what should I check?

1. **Browser Console** (F12)
   - Look for red error messages
   - Check for CORS errors
   - Verify file is loading

2. **URL Accessibility**
   ```
   - Open URL directly in browser
   - Should download or play file
   - Check for 404 errors
   ```

3. **File Format**
   - Confirm supported format
   - Try converting to MP3
   - Check file isn't corrupted

4. **Browser Compatibility**
   - Test in different browser
   - Clear cache and cookies
   - Disable extensions

### "Invalid audio file type" error
This means:
- Unsupported file format
- Missing file extension in URL
- Incorrect MIME type

**Solutions**:
- Use supported formats (MP3, WAV, etc.)
- Ensure URL has proper extension
- Check CDN is serving correct MIME type

### Player looks broken/unstyled

1. **Clear all caches**:
   - Browser cache (Ctrl+F5)
   - WordPress cache plugins
   - Server cache
   - CDN cache

2. **Check for conflicts**:
   ```
   - Deactivate other plugins
   - Switch to default theme
   - Look for JavaScript errors
   ```

3. **Verify files loaded**:
   - Check Network tab in browser
   - Look for 404 errors on CSS/JS
   - Ensure proper file permissions

### Google Drive player shows "No preview available"
- File might be too large
- Unsupported format for preview
- Sharing permissions issue
- Try downloading directly first

### SoundCloud embed not working
- Track must be public or have secret link enabled
- Check if embedding is allowed for the track
- Verify SoundCloud account is active
- Try using the share URL, not the API URL

---

## Security & Protection

### How can I protect my audio files from downloading?

**Free Version strategies**:
1. Use short preview clips (30-60 seconds)
2. Upload lower quality versions (128kbps)
3. Use streaming services like SoundCloud
4. Add spoken watermarks manually

**Pro Version features**:
- 🛡️ Automatic audio watermarking
- 🛡️ Voice-over protection overlay
- 🛡️ Time-limited preview controls
- 🛡️ Right-click disable option
- 🛡️ Secure streaming methods

### Are my full audio files safe?

**Best practices**:
- Never use full tracks as previews
- Store complete files separately  
- Use different URLs for previews vs. purchases
- Implement download protection for sold files
- Consider DRM for high-value content

### Can users download the preview files?

While we implement protection measures, determined users can potentially download previews. That's why we recommend:
- Using edited preview versions
- Keeping previews short
- Adding watermarks (Pro)
- Using lower quality files

### Is CDN storage secure?

CDN security depends on the service:
- **Google Drive**: Use link sharing carefully
- **Dropbox**: Enable password protection (Pro accounts)
- **S3**: Implement bucket policies
- **SoundCloud**: Use secret links for private tracks

---

## Compatibility

### Which themes work with the plugin?

The plugin works with any properly coded WordPress theme, including:
- All default WordPress themes
- Popular themes like Astra, OceanWP, GeneratePress
- WooCommerce-specific themes
- Custom themes following WordPress standards

### Does it work with page builders?

Yes, compatible with:
- Elementor
- WPBakery (Visual Composer)
- Beaver Builder  
- Divi Builder
- Gutenberg blocks
- Classic Editor

### Which browsers are supported?

**Desktop**:
- Chrome (latest 2 versions)
- Firefox (latest 2 versions)
- Safari (latest 2 versions)
- Edge (latest 2 versions)
- Opera (latest version)

**Mobile**:
- iOS Safari (iOS 12+)
- Chrome Mobile
- Samsung Internet
- Firefox Mobile

### Does it work with caching plugins?

Yes, compatible with:
- WP Rocket
- W3 Total Cache
- WP Super Cache
- LiteSpeed Cache
- Cloudflare

**Note**: Clear cache after adding/updating audio files.

### Multi-vendor marketplace compatibility?

- **Free Version**: Basic WooCommerce only
- **Pro Version**: Full support for:
  - Dokan Multivendor
  - WCFM Marketplace  
  - WC Vendors
  - WC Marketplace
  - MultivendorX

---

## Pro Version

### What extra features does Pro include?

1. **Unlimited Audio Previews**
   - Add as many as needed
   - Perfect for albums/collections
   - Dynamic add/remove interface

2. **Advanced Protection**
   - Audio watermarking system
   - Voice-over overlays
   - Download prevention
   - Time-based restrictions

3. **Enhanced Interface**
   - Drag-drop reordering
   - Bulk upload support
   - Playlist-style display
   - Custom player themes

4. **Multi-vendor Support**
   - Vendor dashboard integration
   - Per-vendor audio limits
   - Commission compatibility
   - Separate vendor uploads

### How do I upgrade from Free to Pro?

1. **Purchase Pro license** from WbcomDesigns
2. **Download** Pro version files
3. **Deactivate** Free version (don't delete)
4. **Install** and activate Pro version
5. **Enter license key** in settings

**Note**: All your audio files and settings are preserved during upgrade.

### Is there a trial version?
No trial version available, but we offer:
- 30-day money-back guarantee
- Extensive demo site
- Video walkthroughs
- Developer documentation

### Can I use Pro on multiple sites?

License options:
- **Single Site**: 1 website
- **5 Sites**: Up to 5 websites
- **Unlimited Sites**: Unlimited websites

All licenses include 1 year of updates and support.

### What happens when Pro license expires?

- Plugin continues working
- No new updates available
- No priority support access  
- Can renew at discount rate
- All features remain active

---

## Technical Questions

### How is audio data stored?

Audio information is stored as WordPress post meta:
```php
// Meta key: 'wcap_audio'
array(
    'wcap_audio_names' => array('Name 1', 'Name 2', 'Name 3'),
    'wcap_audio_urls' => array('url1.mp3', 'url2.mp3', 'url3.mp3'),
    'wcap_audio_source' => array('direct', 'cdn', 'cdn')
)
```

### Can I access the audio data programmatically?

Yes, use WordPress functions:
```php
// Get audio data
$audio_data = get_post_meta($product_id, 'wcap_audio', true);

// Check if has audio
if (!empty($audio_data['wcap_audio_urls'])) {
    foreach ($audio_data['wcap_audio_urls'] as $key => $url) {
        $name = $audio_data['wcap_audio_names'][$key];
        // Process...
    }
}
```

### Available hooks and filters?

**Actions**:
```php
do_action('wcap_before_audio_display', $post_id);
do_action('wcap_after_audio_display', $post_id);
```

**Filters**:
```php
apply_filters('wcap_allowed_audio_extensions', $types);
apply_filters('wcap_audio_mime_types', $mime_types);
apply_filters('wcap_audio_player_html', $html, $audio_data);
```

### Can I customize the player styling?

Yes, through CSS:
```css
/* Container */
.wcap-audio-preview-container {
    background: #f5f5f5;
    padding: 20px;
}

/* Play button */
.wcap-preview-button {
    background: #007cba;
    color: white;
}

/* Progress bar */
.wcap-progress-fill {
    background: #00a0d2;
}
```

### How do I add custom CDN support?

Filter example:
```php
add_filter('wcap_validate_audio_url', function($result, $url) {
    if (strpos($url, 'mycdn.com') !== false) {
        $result['success'] = true;
        $result['source'] = 'custom_cdn';
    }
    return $result;
}, 10, 2);
```

### Performance impact?

Minimal impact:
- No database queries on frontend (uses post meta)
- Lazy loading (preload="none")
- Efficient JavaScript (event delegation)
- CDN usage reduces server load
- Compressed assets in production

---

## Support & Resources

### How do I get support?

**Free Version**:
- WordPress.org support forum
- Community Facebook group
- Documentation site
- Video tutorials

**Pro Version**:
- Priority email support
- Direct ticket system
- Live chat (business hours)
- Phone support (enterprise)

### What information should I provide for support?

Always include:
1. WordPress version
2. WooCommerce version  
3. Plugin version
4. PHP version
5. Error messages (exact text)
6. Browser/device information
7. Steps to reproduce issue
8. Screenshot if visual issue

### Where can I find documentation?

- **Official Docs**: https://docs.wbcomdesigns.com/
- **Video Tutorials**: YouTube channel
- **Code Examples**: GitHub repository
- **API Reference**: Developer portal

### How do I report bugs?

1. **Verify** it's a bug (not configuration issue)
2. **Search** existing bug reports
3. **Submit** detailed report with:
   - Clear description
   - Steps to reproduce
   - Expected vs actual behavior
   - System information
   - Screenshots/videos

### Can I contribute to the plugin?

Yes! We welcome:
- Bug reports and fixes
- Feature suggestions
- Documentation improvements
- Translations
- Code contributions (GitHub)

### How do I request new features?

1. Check if already requested
2. Submit detailed request including:
   - Use case explanation
   - Business value
   - Similar examples
   - Mockups (if applicable)

Popular requests may be added to Pro version first.

### Where do I submit translations?

- Use WordPress.org translation platform
- Submit .po/.mo files via support
- Join our translation team
- Get credit as contributor

### Is there an API?

Not a REST API, but the plugin provides:
- PHP functions for developers
- JavaScript methods
- WordPress hooks/filters
- Integration points

### Can I white-label the plugin?

- **Free Version**: No white-labeling
- **Pro Version**: Developer license includes white-label rights

---

## Still Have Questions?

If your question isn't answered here:

1. **Search Documentation**: https://docs.wbcomdesigns.com/
2. **Watch Video Tutorials**: Covers common tasks
3. **Contact Support**: 
   - Free: WordPress.org forums
   - Pro: support@wbcomdesigns.com
4. **Join Community**: Facebook group for tips and discussions

**Response Times**:
- Community Support: 24-48 hours
- Pro Support: 4-8 business hours
- Enterprise Support: 1-2 hours

Thank you for using Audio Preview for WooCommerce!