# Audio Preview for WooCommerce - User Guide

## Table of Contents
1. [Getting Started](#getting-started)
2. [Installation](#installation)
3. [Adding Audio Previews](#adding-audio-previews)
4. [Using CDN Services](#using-cdn-services)
5. [Managing Audio Files](#managing-audio-files)
6. [Frontend Display](#frontend-display)
7. [Best Practices](#best-practices)
8. [Troubleshooting](#troubleshooting)
9. [Pro Version Features](#pro-version-features)

## Getting Started

Audio Preview for WooCommerce allows you to add audio samples to your WooCommerce products, perfect for:
- 🎵 Music stores
- 📚 Audiobook shops
- 🎙️ Podcast platforms
- 🎼 Sound effect libraries
- 🎸 Musical instrument demos

### Key Features (Free Version)
- ✅ Up to 3 audio previews per product
- ✅ Support for MP3, WAV, OGG, M4A, AAC, FLAC, WMA, WEBM
- ✅ CDN and streaming service integration
- ✅ Mobile-responsive audio player
- ✅ Theme-neutral design
- ✅ Media Library integration

## Installation

### Requirements
- WordPress 5.0 or higher
- WooCommerce 3.0 or higher
- PHP 7.2 or higher

### Installation Steps

1. **Download the Plugin**
   - Download from WordPress.org or
   - Purchase from WbcomDesigns

2. **Upload to WordPress**
   - Go to **Plugins → Add New → Upload Plugin**
   - Choose the downloaded ZIP file
   - Click **Install Now**

3. **Activate the Plugin**
   - Click **Activate Plugin** after installation
   - You'll be redirected to the welcome page

4. **Verify Installation**
   - Go to **WB Plugins → Audio Preview for WooCommerce**
   - Check that the plugin appears in your menu

## Adding Audio Previews

### Step-by-Step Guide

1. **Edit Your Product**
   - Navigate to **WooCommerce → Products**
   - Click **Edit** on any product

2. **Find Audio Preview Section**
   - Scroll down to find **"Audio Preview Items"** meta box
   - It appears below the product description

3. **Add Audio Files**

   ![Audio Preview Meta Box](audio-preview-metabox.png)

   For each audio preview (up to 3):
   
   **a) Enter Audio Name**
   - Type a descriptive name (e.g., "Intro Preview", "Chorus Sample")
   - This name appears to customers

   **b) Add Audio URL**
   - **Option 1**: Click **Media Library** button
     - Select existing audio or upload new
     - Supports drag & drop
   
   - **Option 2**: Paste External URL
     - Direct audio file URLs
     - CDN links (Google Drive, Dropbox, etc.)
     - Streaming service links

4. **Save Your Product**
   - Click **Update** or **Publish**
   - Preview changes on frontend

### Field Details

| Field | Description | Required | Example |
|-------|-------------|----------|---------|
| Audio Name | Display name for the preview | Yes (if URL provided) | "Full Track Preview" |
| Audio URL | Link to audio file | Yes (if name provided) | "https://example.com/sample.mp3" |

### Tips for Audio Names
- Be descriptive: "Verse 1 Preview" instead of "Audio 1"
- Keep it short: Maximum 50 characters recommended
- Use consistent naming across products

## Using CDN Services

### Supported CDN Services

#### 1. Google Drive
Perfect for large audio collections.

**How to use Google Drive links:**
1. Upload audio to Google Drive
2. Right-click file → **Get link**
3. Change sharing to **"Anyone with the link"**
4. Copy and paste the link

**Supported URL formats:**
```
https://drive.google.com/file/d/FILE_ID/view
https://drive.google.com/open?id=FILE_ID
```

#### 2. Dropbox
Great for easy sharing and management.

**How to use Dropbox links:**
1. Upload to Dropbox
2. Click **Share** → **Create link**
3. Copy the sharing link
4. Paste in audio URL field

**Supported URL formats:**
```
https://www.dropbox.com/s/XXXXX/filename.mp3?dl=0
https://dl.dropboxusercontent.com/s/XXXXX/filename.mp3
```

#### 3. SoundCloud
Ideal for music and podcast previews.

**How to use SoundCloud:**
1. Upload track to SoundCloud
2. Click **Share** on your track
3. Copy the track URL
4. Paste in audio URL field

**Note**: Track must be public or have link sharing enabled.

#### 4. Amazon S3 / CloudFront
Professional CDN solution for high traffic.

**Supported formats:**
```
https://s3.amazonaws.com/bucket/audio.mp3
https://bucket.s3.region.amazonaws.com/audio.mp3
https://xxxxx.cloudfront.net/audio.mp3
```

### CDN Benefits
- ⚡ Faster loading times
- 💾 Save server bandwidth
- 🌍 Global content delivery
- 📊 Better performance for international customers

## Managing Audio Files

### Media Library Method

1. **Upload Audio Files**
   - Go to **Media → Add New**
   - Drag and drop audio files
   - Wait for upload completion

2. **Organize Your Audio**
   - Use descriptive filenames
   - Create folders using plugins
   - Add alt text for accessibility

3. **Best Practices**
   - Compress audio files (128-192 kbps for previews)
   - Use consistent file naming
   - Keep preview duration 30-60 seconds

### Direct URL Method

1. **Prepare Your URLs**
   - Ensure URLs are publicly accessible
   - Test URLs in browser first
   - Use HTTPS for security

2. **URL Requirements**
   - Must be direct audio file links
   - No authentication required
   - Proper file extension (.mp3, .wav, etc.)

### File Optimization

| Format | Best For | File Size | Browser Support |
|--------|----------|-----------|-----------------|
| MP3 | Universal use | Small | All browsers |
| WAV | High quality | Large | All browsers |
| OGG | Open source | Small | Not iOS |
| M4A | Apple devices | Medium | Modern browsers |
| WEBM | Web optimized | Small | Modern browsers |

**Recommended Settings:**
- **Bitrate**: 128-192 kbps for previews
- **Sample Rate**: 44.1 kHz
- **Duration**: 30-60 seconds
- **Format**: MP3 for maximum compatibility

## Frontend Display

### Player Appearance

The audio player appears:
- Above the "Add to Cart" button
- In a styled container matching your theme
- With play/pause controls and progress bar

### Player Features

1. **Single Audio Preview**
   - Clean, button-style player
   - Integrated progress bar
   - Time display

2. **Multiple Audio Previews**
   - List format with individual controls
   - Clear track names
   - CDN service badges

### Customer Experience

**What customers see:**
```
🎵 Audio Previews
─────────────────
▶ Track 1 Preview
▶ Track 2 Preview [Google Drive]
▶ Track 3 Preview [SoundCloud]
```

**Player States:**
- **Ready**: Play button visible
- **Loading**: Spinner animation
- **Playing**: Pause button, progress bar active
- **Error**: Error message (temporary)

### Mobile Experience
- Touch-friendly controls
- Responsive design
- Same features as desktop
- Optimized for small screens

## Best Practices

### Content Guidelines

1. **Preview Length**
   - Optimal: 30-60 seconds
   - Maximum: 90 seconds
   - Showcase the best parts

2. **Audio Quality**
   - Use lower quality for previews (128-192 kbps)
   - Save bandwidth and protect full versions
   - Ensure clear audio without distortion

3. **File Naming**
   ```
   Good: track01-preview-verse1.mp3
   Bad: audio.mp3
   ```

4. **Descriptions**
   - "Intro and First Verse"
   - "Chorus Preview (0:45-1:15)"
   - "Chapter 1 Excerpt"

### Security Tips

1. **Protect Full Versions**
   - Never use full tracks as previews
   - Store full versions securely
   - Use different URLs for previews

2. **Watermarking** (Pro feature)
   - Add voice-overs
   - Include audio watermarks
   - Fade in/out protection

3. **CDN Security**
   - Use expiring links when possible
   - Monitor usage
   - Set access permissions

### SEO Optimization

1. **Use Descriptive Names**
   - Include keywords in audio names
   - Help search engines understand content

2. **Add Schema Markup**
   ```html
   <div itemscope itemtype="http://schema.org/AudioObject">
     <meta itemprop="name" content="Track Preview">
     <meta itemprop="duration" content="PT1M30S">
   </div>
   ```

3. **Page Load Speed**
   - Use CDN for faster delivery
   - Compress audio files
   - Lazy load audio (preload="none")

## Troubleshooting

### Common Issues

#### Audio Not Playing

**Symptoms**: Click play but nothing happens

**Solutions**:
1. Check browser console for errors
2. Verify URL is accessible
3. Test in different browser
4. Check file format compatibility

#### CDN Links Not Working

**Google Drive Issues**:
- Ensure file is shared publicly
- Check if sharing permissions changed
- Try regenerating the share link

**Dropbox Issues**:
- Verify link hasn't expired
- Check file still exists
- Ensure proper URL format

**SoundCloud Issues**:
- Confirm track is public
- Check if track was deleted
- Verify embed permissions

#### Styling Problems

**Player looks broken**:
1. Clear browser cache
2. Check theme conflicts
3. Disable caching plugins temporarily
4. Contact theme support

#### Upload Errors

**"Invalid file type" error**:
- Check file extension
- Verify MIME type
- Ensure file isn't corrupted
- Try different format

### Error Messages

| Error | Meaning | Solution |
|-------|---------|----------|
| "Invalid audio file type" | Unsupported format | Use MP3, WAV, OGG, etc. |
| "Audio URL required" | Empty URL field | Add file URL |
| "Audio name required" | Empty name field | Add descriptive name |
| "Error loading audio" | File unreachable | Check URL accessibility |

### Performance Issues

**Slow Loading**:
1. Compress audio files
2. Use CDN services
3. Enable browser caching
4. Reduce preview length

**Mobile Problems**:
1. Test on real devices
2. Check data usage
3. Verify touch controls work
4. Ensure responsive design

### Getting Help

1. **Documentation**
   - Check FAQ section
   - Read setup guides
   - Watch video tutorials

2. **Support Channels**
   - WordPress.org forums (free)
   - WbcomDesigns support (pro)
   - Community Facebook group

3. **Before Contacting Support**
   - WordPress version
   - WooCommerce version
   - Plugin version
   - Error messages
   - Browser/device info

## Pro Version Features

### Comparison Table

| Feature | Free | Pro |
|---------|------|-----|
| Audio previews per product | 3 (fixed) | Unlimited |
| Add/remove buttons | ❌ | ✅ |
| CDN support | ✅ | ✅ |
| Multi-vendor support | ❌ | ✅ |
| Custom player themes | ❌ | ✅ |
| Preview duration control | ❌ | ✅ |
| Audio watermarking | ❌ | ✅ |
| Bulk import | ❌ | ✅ |
| Priority support | ❌ | ✅ |

### Pro Benefits

1. **Unlimited Previews**
   - Perfect for albums
   - Great for audiobooks
   - Essential for sound libraries

2. **Dynamic Management**
   - Add/remove buttons
   - Drag-and-drop reordering
   - Bulk operations

3. **Advanced Protection**
   - Watermark overlays
   - Time limits
   - Download prevention

4. **Multi-vendor Support**
   - Dokan integration
   - WCFM compatibility
   - WC Vendors support

### Upgrading to Pro

1. **Purchase License**
   - Visit WbcomDesigns.com
   - Choose license type
   - Complete purchase

2. **Install Pro Version**
   - Download Pro plugin
   - Deactivate Free version
   - Install and activate Pro

3. **Enter License**
   - Go to settings
   - Enter license key
   - Activate license

### Migration Notes
- All settings preserved
- Audio files remain intact
- No data loss
- Seamless transition

## Tips for Success

### For Music Stores
1. Preview chorus sections
2. Include intro and outro
3. Show variety in albums
4. Use consistent preview length

### For Audiobooks
1. Preview first chapter
2. Include narrator introduction
3. Highlight dramatic moments
4. Mention total duration

### For Podcasts
1. Preview best segments
2. Include host introductions
3. Show episode highlights
4. Tease upcoming content

### For Sound Effects
1. Show full effect once
2. Include variations
3. Demonstrate quality
4. Provide context

## Conclusion

Audio Preview for WooCommerce makes it easy to showcase your audio products professionally. Whether you're selling music, audiobooks, or sound effects, this plugin provides the tools you need to give customers a preview of what they're buying.

**Key Takeaways:**
- Easy to set up and use
- Flexible CDN integration
- Mobile-friendly design
- Secure preview system
- Professional presentation

For additional help, visit:
- [Documentation](https://docs.wbcomdesigns.com/)
- [Support Forum](https://wordpress.org/support/plugin/woo-audio-preview/)
- [Video Tutorials](https://youtube.com/wbcomdesigns)

Thank you for choosing Audio Preview for WooCommerce!