# Audio Preview for WooCommerce - Developer Guide

## Table of Contents
1. [Plugin Architecture](#plugin-architecture)
2. [File Structure](#file-structure)
3. [Core Classes](#core-classes)
4. [Hooks and Filters](#hooks-and-filters)
5. [JavaScript API](#javascript-api)
6. [CDN Integration](#cdn-integration)
7. [Database Structure](#database-structure)
8. [Security Considerations](#security-considerations)
9. [Extending the Plugin](#extending-the-plugin)
10. [Debugging](#debugging)

## Plugin Architecture

The Audio Preview for WooCommerce plugin follows WordPress coding standards and uses an object-oriented architecture with the following design patterns:

- **MVC Pattern**: Separates logic (classes), presentation (templates), and data handling
- **Singleton Pattern**: Used for main plugin initialization
- **Hook-based Architecture**: Leverages WordPress action and filter hooks

### Core Components

```
┌─────────────────┐     ┌──────────────────┐     ┌─────────────────┐
│   Plugin Core   │────▶│  Admin Classes   │────▶│ Frontend Classes│
└─────────────────┘     └──────────────────┘     └─────────────────┘
         │                       │                         │
         ▼                       ▼                         ▼
┌─────────────────┐     ┌──────────────────┐     ┌─────────────────┐
│     Loader      │     │   Meta Boxes     │     │  Audio Players  │
└─────────────────┘     └──────────────────┘     └─────────────────┘
```

## File Structure

```
woo-audio-preview/
├── admin/
│   ├── class-wc-audio-preview-admin.php    # Admin functionality
│   ├── class-admin-review.php              # Review prompt system
│   ├── css/                                # Admin stylesheets
│   ├── js/                                 # Admin JavaScript
│   └── partials/                           # Admin view templates
├── includes/
│   ├── class-wc-audio-preview.php          # Main plugin class
│   ├── class-wc-audio-preview-loader.php   # Hook loader
│   └── class-wc-audio-preview-i18n.php     # Internationalization
├── public/
│   ├── class-wc-audio-preview-public.php   # Frontend functionality
│   ├── css/                                # Frontend stylesheets
│   └── js/                                 # Frontend JavaScript
├── languages/                              # Translation files
└── woo-product-audio-preview.php           # Main plugin file
```

## Core Classes

### 1. Main Plugin Class (`Wc_Audio_Preview`)

Located in `includes/class-wc-audio-preview.php`

```php
class Wc_Audio_Preview {
    protected $loader;
    protected $plugin_name;
    protected $version;
    
    public function __construct() {
        $this->plugin_name = 'wc-audio-preview';
        $this->version = '1.5.0';
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }
}
```

### 2. Admin Class (`Wc_Audio_Preview_Admin`)

Key methods and properties:

```php
class Wc_Audio_Preview_Admin {
    // File type validation
    private $allowed_file_types = array('mp3', 'wav', 'ogg', 'm4a', 'aac', 'flac', 'wma', 'webm');
    
    // CDN pattern detection
    private $cdn_patterns = array(
        'google_drive' => [...],
        'soundcloud' => [...],
        'dropbox' => [...]
    );
    
    // Meta box registration
    public function wcap_register_meta_boxes() {...}
    
    // Save meta data
    public function wcap_save_meta_box($post_id) {...}
    
    // Validate audio URLs
    private function validate_audio_url($url) {...}
}
```

### 3. Public Class (`Wc_Audio_Preview_Public`)

Handles frontend display:

```php
class Wc_Audio_Preview_Public {
    // Display audio previews
    public function wcap_add_preview_field() {...}
    
    // Render different player types
    private function render_audio_player($key, $name, $audio_url, $is_cdn) {...}
    private function render_google_drive_player($key, $name, $audio_url) {...}
    private function render_sound_cloud_player($key, $name, $audio_url) {...}
}
```

## Hooks and Filters

### Actions

```php
// Admin hooks
add_action('admin_enqueue_scripts', array($plugin_admin, 'enqueue_styles'));
add_action('admin_enqueue_scripts', array($plugin_admin, 'enqueue_scripts'));
add_action('add_meta_boxes', array($plugin_admin, 'wcap_register_meta_boxes'));
add_action('save_post', array($plugin_admin, 'wcap_save_meta_box'));

// Frontend hooks
add_action('wp_enqueue_scripts', array($plugin_public, 'enqueue_styles'));
add_action('wp_enqueue_scripts', array($plugin_public, 'enqueue_scripts'));
add_action('woocommerce_before_add_to_cart_form', array($plugin_public, 'wcap_add_preview_field'), 0);

// AJAX hooks
add_action('wp_ajax_wcap_delete_audio_ajax', array($plugin_admin, 'wcap_delete_audio_ajax'));
add_action('wp_ajax_nopriv_wcap_delete_audio_ajax', array($plugin_admin, 'wcap_delete_audio_ajax'));
```

### Filters

```php
// Custom filters you can use
apply_filters('wcap_allowed_audio_extensions', $allowed_file_types);
apply_filters('wcap_audio_mime_types', $mime_types);
```

### Adding Custom Hooks

```php
// Add custom file type support
add_filter('wcap_allowed_audio_extensions', function($types) {
    $types[] = 'opus';
    return $types;
});

// Modify MIME types
add_filter('wcap_audio_mime_types', function($mime_types) {
    $mime_types['opus'] = 'audio/opus';
    return $mime_types;
});
```

## JavaScript API

### Admin JavaScript (`wc-audio-preview-admin.js`)

```javascript
const WCAP = {
    config: {
        allowedTypes: ['mp3', 'wav', 'ogg', 'm4a', 'aac', 'flac', 'wma', 'webm'],
        maxFileSize: 50 * 1024 * 1024, // 50MB
        isFixedMode: true // Free version with 3 fixed fields
    },
    
    // Initialize
    init: function() {
        this.bindEvents();
        this.validateExistingFiles();
        this.initializeCdnDetection();
    },
    
    // CDN detection
    getCdnServiceInfo: function(url) {
        // Returns service info if CDN detected
    },
    
    // Media library integration
    openMediaLibrary: function(e) {
        // WordPress media uploader
    }
};
```

### Frontend JavaScript (`wc-audio-preview-public.js`)

```javascript
const WCAPPlayer = {
    currentAudio: null,
    players: new Map(),
    
    // Initialize player
    init: function() {
        this.bindEvents();
        this.initializeAudioElements();
    },
    
    // Play audio
    playAudio: function(audioId) {
        // Handle playback
    },
    
    // Stop all players
    wcapStopAllPlayers: function(excludeKey) {
        // Stops HTML5, Google Drive, and SoundCloud players
    }
};
```

### JavaScript Events

```javascript
// Custom events
$(document).trigger('wcap:reinit'); // Reinitialize players

// Player events
audio.addEventListener('loadstart', callback);
audio.addEventListener('canplay', callback);
audio.addEventListener('play', callback);
audio.addEventListener('pause', callback);
audio.addEventListener('ended', callback);
audio.addEventListener('timeupdate', callback);
audio.addEventListener('error', callback);
```

## CDN Integration

### Supported CDN Services

1. **Google Drive**
   - Pattern: `/drive\.google\.com\/file\/d\/([a-zA-Z0-9-_]+)/`
   - Conversion: Uses iframe embed for playback

2. **Dropbox**
   - Pattern: `/dropbox\.com\/s\/([a-zA-Z0-9_-]+)/`
   - Conversion: Changes to direct download link

3. **SoundCloud**
   - Pattern: `/soundcloud\.com\/[a-zA-Z0-9-_]+\/[a-zA-Z0-9-_]+/`
   - Uses SoundCloud Widget API

4. **Amazon S3**
   - Pattern: `/s3\.amazonaws\.com/` or custom S3 domains
   - Direct playback support

### Adding Custom CDN Support

```php
// In admin class
private function is_cdn_url($url) {
    // Add your CDN pattern
    $custom_patterns = array(
        'my_cdn' => array(
            '/mycdn\.com\/audio\/([a-zA-Z0-9]+)/i'
        )
    );
    
    // Check patterns...
}

// Convert CDN URL for playback
private function convert_cdn_url($url) {
    if (strpos($url, 'mycdn.com') !== false) {
        // Convert to playable format
        return str_replace('share', 'stream', $url);
    }
    return $url;
}
```

## Database Structure

### Post Meta Storage

Audio data is stored in WordPress post meta:

```php
// Meta key: 'wcap_audio'
$audio_data = array(
    'wcap_audio_names' => array(
        'Track 1 Preview',
        'Track 2 Preview',
        'Track 3 Preview'
    ),
    'wcap_audio_urls' => array(
        'https://example.com/audio1.mp3',
        'https://drive.google.com/file/d/xxx',
        'https://soundcloud.com/artist/track'
    ),
    'wcap_audio_source' => array(
        'direct',
        'cdn',
        'cdn'
    )
);

update_post_meta($post_id, 'wcap_audio', $audio_data);
```

### Retrieving Audio Data

```php
$wcap_audio = get_post_meta($post->ID, 'wcap_audio', true);

if (!empty($wcap_audio) && isset($wcap_audio['wcap_audio_urls'])) {
    foreach ($wcap_audio['wcap_audio_urls'] as $key => $url) {
        $name = $wcap_audio['wcap_audio_names'][$key];
        $source = $wcap_audio['wcap_audio_source'][$key];
        // Process audio...
    }
}
```

## Security Considerations

### 1. Nonce Verification

```php
// In save method
$nonce = $_POST['wcap_nonce'] ?? '';
if (!wp_verify_nonce($nonce, 'wcap_nonce_action')) {
    return;
}
```

### 2. Capability Checks

```php
if (!current_user_can('edit_post', $post_id)) {
    return;
}
```

### 3. Data Sanitization

```php
// Sanitize URLs
$audio_url = esc_url_raw($_POST['audio_url']);

// Sanitize text
$audio_name = sanitize_text_field($_POST['audio_name']);

// Escape output
echo esc_url($audio_url);
echo esc_html($audio_name);
```

### 4. AJAX Security

```php
// Check AJAX nonce
if (!check_ajax_referer('ajax-nonce', 'nonce', false)) {
    wp_send_json_error('Invalid security token');
    exit;
}
```

## Extending the Plugin

### 1. Add Custom Player Theme

```css
/* Add to your theme */
.wcap-audio-preview-container.custom-theme {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.wcap-audio-preview-container.custom-theme .wcap-preview-button {
    background: rgba(255, 255, 255, 0.1);
}
```

### 2. Add Custom Audio Source

```php
// Hook into the validation
add_filter('wcap_validate_audio_url', function($result, $url) {
    if (strpos($url, 'mycustomservice.com') !== false) {
        $result['success'] = true;
        $result['source'] = 'custom_service';
        $result['message'] = 'Custom service detected';
    }
    return $result;
}, 10, 2);
```

### 3. Modify Player Output

```php
// Override the display method
remove_action('woocommerce_before_add_to_cart_form', array($plugin_public, 'wcap_add_preview_field'), 0);
add_action('woocommerce_before_add_to_cart_form', 'my_custom_audio_display', 0);

function my_custom_audio_display() {
    global $post;
    $audio_data = get_post_meta($post->ID, 'wcap_audio', true);
    // Custom display logic
}
```

### 4. Add Analytics Tracking

```javascript
// Track play events
$(document).on('wcap:play', function(e, data) {
    gtag('event', 'audio_play', {
        'event_category': 'engagement',
        'event_label': data.audioName
    });
});
```

## Debugging

### 1. Enable Debug Mode

```php
// In wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

### 2. Plugin Debug Functions

```php
// Log errors (in admin class)
private function wcap_log_error($message, $level = 'error') {
    if (defined('WP_DEBUG') && WP_DEBUG === true) {
        error_log('[Audio Preview for WooCommerce] ' . $level . ': ' . $message);
    }
}
```

### 3. JavaScript Debugging

```javascript
// Enable verbose logging
WCAP.config.debug = true;

// Log CDN detection
console.log('CDN Info:', WCAP.getCdnServiceInfo(url));

// Log player state
console.log('Players:', WCAPPlayer.players);
```

### 4. Common Issues

1. **Audio not playing**: Check browser console for CORS errors
2. **CDN not detected**: Verify URL patterns in `getCdnServiceInfo()`
3. **Styles not loading**: Check `get_asset_filename()` method
4. **AJAX failing**: Verify nonces and user capabilities

## Best Practices

1. **Always sanitize input**: Use WordPress sanitization functions
2. **Escape output**: Use appropriate escaping functions
3. **Check capabilities**: Verify user permissions before operations
4. **Use nonces**: Protect forms and AJAX requests
5. **Handle errors gracefully**: Provide user-friendly error messages
6. **Test CDN URLs**: Verify CDN patterns work with various URL formats
7. **Optimize performance**: Lazy load audio files
8. **Follow WordPress standards**: Use proper hooks and coding conventions

## API Reference

### PHP Functions

```php
// Get audio data
wcap_get_audio_data($post_id);

// Validate URL
wcap_validate_audio_url($url);

// Check if CDN
wcap_is_cdn_url($url);

// Convert CDN URL
wcap_convert_cdn_url_for_playback($url);
```

### JavaScript Functions

```javascript
// Initialize players
WCAP.init();
WCAPPlayer.init();

// Play audio
WCAPPlayer.playAudio(audioId);

// Stop all players
wcapStopAllPlayers(excludeKey);

// Validate URL
WCAP.validateSingleUrl();
```

### Available Hooks

```php
// Actions
do_action('wcap_before_audio_display', $post_id);
do_action('wcap_after_audio_display', $post_id);

// Filters
apply_filters('wcap_audio_player_html', $html, $audio_data);
apply_filters('wcap_allowed_services', $services);
```