(function ($) {
  "use strict";

  /**
   * Enhanced Audio Preview Admin JavaScript
   * Now supports Media Library integration and CDN URL detection
   */

  // Enhanced configuration and constants
  const WCAP = {
    config: {
      allowedTypes: ['mp3', 'wav', 'ogg', 'm4a', 'aac', 'flac', 'wma', 'webm'],
      maxFileSize: 50 * 1024 * 1024, // 50MB in bytes
      errorContainer: '.wcap-error-messages'
    },

    // CDN patterns from PHP
    cdnPatterns: wcap_ajax_object.cdn_patterns || {},

    // Store media uploader instances
    mediaUploaders: new Map(),

    /**
     * Initialize the plugin
     */
    init: function() {
      this.bindEvents();
      this.validateExistingFiles();
      this.initializeCdnDetection();
      this.initializeSortable();
    },

    /**
     * Bind all event handlers
     */
    bindEvents: function() {
      // Add new audio row
      $(document).on('click', '.wcap-add-audio-cl', this.addAudioRow);
      
      // Remove audio row
      $(document).on('click', '.wcap-delete-audio-cl', this.removeAudioRow);
      
      // Media library button
      $(document).on('click', '.wcap-media-button', this.openMediaLibrary);
      
      // Form validation
      $('body.post-type-product form#post').on('submit', this.validateForm);
      
      // Real-time validation with CDN detection
      $(document).on('blur', '.wcap_audio_urls', this.validateSingleUrl);
      $(document).on('input', '.wcap_audio_urls', this.debounce(this.detectCdnUrl, 500));
      $(document).on('blur', '.wcap-file-name', this.validateAudioName);
      
      // FAQ accordion
      this.initFaqAccordion();
    },

    /**
     * Initialize CDN URL detection for existing fields
     */
    initializeCdnDetection: function() {
      $('.wcap_audio_urls').each(function() {
        WCAP.detectCdnUrl.call(this);
      });
    },

    /**
     * Initialize sortable functionality
     */
    initializeSortable: function() {
      if (typeof $.fn.sortable !== 'undefined') {
        $('.wcap_preview-tr').sortable({
          handle: '.sort',
          placeholder: 'wcap-sort-placeholder',
          helper: 'clone',
          start: function(e, ui) {
            ui.placeholder.height(ui.item.height());
          },
          update: function(e, ui) {
            WCAP.showSuccess('Audio order updated!');
          }
        });
      }
    },

    /**
     * Detect CDN URL
     */
    detectCdnUrl: function() {
      const $input = $(this);
      const url = $.trim($input.val());
      const $row = $input.closest('tr');
      
      if (!url) {
        WCAP.removeServiceIndicator($row);
        return;
      }

      const serviceInfo = WCAP.getCdnServiceInfo(url);
      
      if (serviceInfo) {
        WCAP.showServiceIndicator($row, serviceInfo);
        $input.removeClass('error').addClass('cdn-detected');
        
        // Auto-fill name if empty
        const $nameField = $row.find('.wcap-file-name');
        if (!$nameField.val()) {
          const suggestedName = WCAP.generateAudioName(url, serviceInfo);
          if (suggestedName) {
            $nameField.val(suggestedName);
          }
        }
      } else {
        WCAP.removeServiceIndicator($row);
        $input.removeClass('cdn-detected');
      }
    },

    /**
     * Get CDN service info
     */
    getCdnServiceInfo: function(url) {
      // Predefined patterns for CDN services
      const patterns = {
        google_drive: [
          /drive\.google\.com\/file\/d\/([a-zA-Z0-9-_]+)(?:\/view)?(?:\?.*)?/i,
          /drive\.google\.com\/uc\?(?:.*&)?id=([a-zA-Z0-9-_]+)(?:&.*)?/i,
          /drive\.google\.com\/open\?id=([a-zA-Z0-9-_]+)/i
        ],
        soundcloud: [
          /soundcloud\.com\/[a-zA-Z0-9-_]+\/[a-zA-Z0-9-_]+/i,
          /api\.soundcloud\.com\/tracks\/[0-9]+/i
        ],
        spotify: [
          /open\.spotify\.com\/track\/[a-zA-Z0-9]+/i,
          /spotify:track:[a-zA-Z0-9]+/i
        ],
        amazon_s3: [
          /s3\.amazonaws\.com\/[^\/]+\/.+\.(mp3|wav|ogg|m4a)/i,
          /[a-zA-Z0-9-]+\.s3\.[a-zA-Z0-9-]+\.amazonaws\.com\/.+\.(mp3|wav|ogg|m4a)/i
        ],
        cloudfront: [
          /[a-zA-Z0-9]+\.cloudfront\.net\/.+\.(mp3|wav|ogg|m4a)/i
        ],
        dropbox: [
          /dropbox\.com\/s\/([a-zA-Z0-9_-]+)\/([^?]+\.(mp3|wav|ogg|m4a))/i,
          /dl\.dropbox(?:usercontent)?\.com\/s\/([a-zA-Z0-9_-]+)\/([^?]+)/i
        ]
      };
      
      for (const [service, servicePatterns] of Object.entries(patterns)) {
        for (const pattern of servicePatterns) {
          if (pattern.test(url)) {
            return {
              service: service,
              serviceName: this.getServiceDisplayName(service),
              isCdn: true
            };
          }
        }
      }
      
      return null;
    },

    /**
     * Get service display name
     */
    getServiceDisplayName: function(service) {
      const serviceNames = wcap_ajax_object.supported_services || {
        soundcloud: 'SoundCloud',
        spotify: 'Spotify',
        amazon_s3: 'Amazon S3',
        cloudfront: 'CloudFront',
        google_drive: 'Google Drive',
        dropbox: 'Dropbox'
      };
      return serviceNames[service] || service;
    },

    /**
     * Generate suggested audio name
     */
    generateAudioName: function(url, serviceInfo) {
      const baseName = serviceInfo.serviceName + ' Audio';
      
      // Try to extract meaningful name from URL
      const urlParts = url.split('/');
      const filename = urlParts[urlParts.length - 1];
      
      if (filename && filename.includes('.')) {
        const nameWithoutExt = filename.split('.')[0];
        return nameWithoutExt.replace(/[-_]/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
      }
      
      return baseName;
    },

    /**
     * Show service indicator
     */
    showServiceIndicator: function($row, serviceInfo) {
      WCAP.removeServiceIndicator($row);
      
      const indicator = `
        <div class="wcap-service-indicator wcap-service-${serviceInfo.service}">
          <span class="service-icon">${WCAP.getServiceIcon(serviceInfo.service)}</span>
          <span class="service-text">${serviceInfo.serviceName} link detected</span>
          <span class="service-status">✓ Ready for preview</span>
        </div>
      `;
      
      $row.find('.file_url').append(indicator);
      $row.addClass('wcap-cdn-row');
      
      // Show success message briefly
      WCAP.showFieldSuccess($row.find('.wcap_audio_urls'), 'CDN link detected!');
    },

    /**
     * Remove service indicator
     */
    removeServiceIndicator: function($row) {
      $row.find('.wcap-service-indicator').remove();
      $row.removeClass('wcap-cdn-row');
    },

    /**
     * Get service icon
     */
    getServiceIcon: function(service) {
      const icons = {
        soundcloud: '☁️',
        spotify: '🎵',
        amazon_s3: '📦',
        cloudfront: '⚡',
        google_drive: '📁',
        dropbox: '📦'
      };
      return icons[service] || '🔗';
    },

    /**
     * Add new audio row
     */
    addAudioRow: function(e) {
      e.preventDefault();
      
      const newRow = WCAP.createAudioRow();
      $('.woo-audio-preview-table tbody').append(newRow);
      
      // Focus on the new file name input
      newRow.find('.wcap-file-name').focus();
      
      // Initialize sortable for new row
      WCAP.initializeSortable();
    },

    /**
     * Create new audio row HTML
     */
    createAudioRow: function() {
      const rowHtml = `
        <tr class="wcap-audio-file">
          <td class="sort" data-label="Sort"></td>
          <td class="file_name" data-label="Name">
            <input class="input_text wcap-file-name" 
                   placeholder="Audio Name" 
                   name="wcap_audio[wcap_audio_names][]" 
                   value="" 
                   type="text" 
                   required>
          </td>
          <td class="file_url" data-label="Audio URL">
            <input class="input_text wcap_audio_urls" 
                   placeholder="Enter URL or choose from Media Library" 
                   name="wcap_audio[wcap_audio_urls][]" 
                   value="" 
                   type="url">
          </td>
          <td class="file_url_choose" width="1%" data-label="Choose">
            <button type="button" class="button wcap-media-button">Media Library</button>
          </td>
          <td width="15%" data-label="Actions">
            <button type="button" class="wcap-add-audio-cl button button-primary button-small" title="Add a new audio file">
              Add
            </button>
            <button type="button" class="wcap-delete-audio-cl button button-secondary button-small" title="Remove this audio file">
              Remove
            </button>
          </td>
        </tr>`;
      
      return $(rowHtml);
    },

    /**
     * Remove audio row
     */
    removeAudioRow: function(e) {
      e.preventDefault();
      
      const $row = $(this).closest('tr');
      const $table = $row.closest('table');
      
      // Confirm deletion if there's content
      const audioName = $row.find('.wcap-file-name').val();
      const audioUrl = $row.find('.wcap_audio_urls').val();
      
      if (audioName || audioUrl) {
        if (!confirm('Are you sure you want to remove this audio file?')) {
          return;
        }
      }
      
      // If this row has a file URL, make AJAX call to delete
      if (audioUrl) {
        const postId = $(this).data('p_id');
        const data = {
          action: 'wcap_delete_audio_ajax',
          file_url: audioUrl,
          p_id: postId,
          nonce: wcap_ajax_object.nonce
        };
        
        $.post(wcap_ajax_object.ajax_url, data, function(response) {
          console.log('Audio file removed from server');
        });
      }
      
      $row.fadeOut(300, function() {
        $(this).remove();
        
        // Ensure at least one row exists
        if ($table.find('.wcap-audio-file').length === 0) {
          WCAP.addAudioRow(e);
        }
      });
    },

    /**
     * Open WordPress Media Library
     */
    openMediaLibrary: function(e) {
      e.preventDefault();
      
      const $button = $(this);
      const $row = $button.closest('tr');
      const $urlField = $row.find('.wcap_audio_urls');
      const rowIndex = $row.index();
      
      let mediaUploader = WCAP.mediaUploaders.get(rowIndex);
      
      // Create new media uploader if it doesn't exist
      if (!mediaUploader) {
        mediaUploader = wp.media({
          title: 'Select Audio File',
          button: {
            text: 'Use this audio'
          },
          multiple: false,
          library: {
            type: 'audio'
          }
        });
        
        // Handle file selection
        mediaUploader.on('select', function() {
          const attachment = mediaUploader.state().get('selection').first().toJSON();
          
          if (WCAP.validateAudioAttachment(attachment)) {
            $urlField.val(attachment.url).trigger('change');
            
            // Auto-fill name if empty
            const $nameField = $row.find('.wcap-file-name');
            if (!$nameField.val()) {
              $nameField.val(attachment.title || attachment.filename);
            }
            
            // Remove any CDN indicators since this is a media library file
            WCAP.removeServiceIndicator($row);
            
            WCAP.showSuccess('Audio file selected successfully!');
          }
        });
        
        WCAP.mediaUploaders.set(rowIndex, mediaUploader);
      }
      
      mediaUploader.open();
    },

    /**
     * Validate audio attachment from media library
     */
    validateAudioAttachment: function(attachment) {
      if (!attachment.url) {
        WCAP.showError('Invalid file selected.');
        return false;
      }
      
      const fileExtension = WCAP.getFileExtension(attachment.url);
      if (!WCAP.isValidAudioType(fileExtension)) {
        WCAP.showError(wcap_ajax_object.error_messages.invalid_file_type || 'Invalid audio file type.');
        return false;
      }
      
      return true;
    },

    /**
     * Validate form submission
     */
    validateForm: function(e) {
      const errors = [];
      let hasValidAudio = false;
      
      $('.wcap-audio-file').each(function() {
        const $row = $(this);
        const audioName = $.trim($row.find('.wcap-file-name').val());
        const audioUrl = $.trim($row.find('.wcap_audio_urls').val());
        
        // Skip completely empty rows
        if (!audioName && !audioUrl) {
          return true; // continue
        }
        
        // Validate audio name
        if (!audioName) {
          errors.push('Audio name is required for all files.');
          $row.find('.wcap-file-name').addClass('error');
        } else {
          $row.find('.wcap-file-name').removeClass('error');
        }
        
        // Validate audio URL
        if (!audioUrl) {
          errors.push('Audio URL is required when audio name is provided.');
          $row.find('.wcap_audio_urls').addClass('error');
        } else if (!WCAP.isValidUrl(audioUrl)) {
          errors.push('Please enter a valid URL for: ' + audioName);
          $row.find('.wcap_audio_urls').addClass('error');
        } else {
          // Check if it's CDN first, then check file extension
          const serviceInfo = WCAP.getCdnServiceInfo(audioUrl);
          
          if (serviceInfo) {
            // It's a valid CDN URL
            $row.find('.wcap_audio_urls').removeClass('error');
            hasValidAudio = true;
          } else {
            // For non-CDN URLs, check file extension
            const fileExtension = WCAP.getFileExtension(audioUrl);
            
            if (fileExtension && WCAP.isValidAudioType(fileExtension)) {
              $row.find('.wcap_audio_urls').removeClass('error');
              hasValidAudio = true;
            } else {
              errors.push('Invalid audio file type or unsupported URL for: ' + audioName);
              $row.find('.wcap_audio_urls').addClass('error');
            }
          }
        }
      });
      
      if (errors.length > 0) {
        e.preventDefault();
        WCAP.showErrors(errors);
        WCAP.scrollToErrors();
        return false;
      }
      
      WCAP.clearErrors();
      return true;
    },

    /**
     * Validate single URL
     */
    validateSingleUrl: function() {
      const $input = $(this);
      const url = $.trim($input.val());
      
      if (!url) {
        $input.removeClass('error success cdn-detected');
        WCAP.hideFieldError($input);
        return;
      }
      
      if (!WCAP.isValidUrl(url)) {
        $input.addClass('error').removeClass('success cdn-detected');
        WCAP.showFieldError($input, 'Please enter a valid URL.');
        return;
      }
      
      // Check if it's a CDN URL
      const serviceInfo = WCAP.getCdnServiceInfo(url);
      if (serviceInfo) {
        $input.removeClass('error').addClass('success cdn-detected');
        WCAP.hideFieldError($input);
        WCAP.showFieldSuccess($input, `${serviceInfo.serviceName} link detected! ✓`);
        return;
      }
      
      // For non-CDN URLs, check file extension
      const fileExtension = WCAP.getFileExtension(url);
      if (!fileExtension || !WCAP.isValidAudioType(fileExtension)) {
        $input.addClass('error').removeClass('success cdn-detected');
        WCAP.showFieldError($input, wcap_ajax_object.error_messages.invalid_file_type || 'Invalid audio file type.');
        return;
      }
      
      $input.removeClass('error cdn-detected').addClass('success');
      WCAP.hideFieldError($input);
    },

    /**
     * Validate audio name field
     */
    validateAudioName: function() {
      const $input = $(this);
      const name = $.trim($input.val());
      console.log($input);
      console.log(name);
      
      if (name && name.length < 3) {
        $input.addClass('error').removeClass('success');
        WCAP.showFieldError($input, 'Audio name should be at least 3 characters long.');
      } else {
        $input.removeClass('error');
        WCAP.hideFieldError($input);
        if (name) {
          $input.addClass('success');
          $input.val(name);
        }
      }
    },

    /**
     * Validate existing files on page load
     */
    validateExistingFiles: function() {
      $('.wcap_audio_urls').each(function() {
        WCAP.validateSingleUrl.call(this);
      });
    },

    /**
     * Utility functions
     */
    
    getFileExtension: function(url) {
      if (!url) return '';
      // Clean URL from query parameters and hash
      const cleanUrl = url.split('?')[0].split('#')[0];
      // Extract extension from the clean URL
      const parts = cleanUrl.split('.');
      return parts.length > 1 ? parts.pop().toLowerCase() : '';
    },

    isValidAudioType: function(extension) {
      return WCAP.config.allowedTypes.includes(extension);
    },

    isValidUrl: function(url) {
      try {
        new URL(url);
        return true;
      } catch {
        return false;
      }
    },

    /**
     * Debounce function
     */
    debounce: function(func, wait) {
      let timeout;
      return function executedFunction(...args) {
        const later = () => {
          clearTimeout(timeout);
          func.apply(this, args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
      };
    },

    /**
     * Error handling functions
     */
    
    showError: function(message) {
      WCAP.showErrors([message]);
    },

    showErrors: function(errors) {
      let $container = $(WCAP.config.errorContainer);
      
      if ($container.length === 0) {
        $('.preview_files').prepend('<div class="wcap-error-messages"></div>');
        $container = $(WCAP.config.errorContainer);
      }
      
      const errorHtml = errors.map(error => `<p class="error-message">⚠️ ${error}</p>`).join('');
      
      $container.html(`
        <div class="wcap-error-box">
          <h4>Please fix the following errors:</h4>
          ${errorHtml}
        </div>
      `).show();
    },

    showSuccess: function(message) {
      const $container = $('.wcap-error-messages');
      $container.html(`
        <div class="wcap-success-box">
          <p class="success-message">✅ ${message}</p>
        </div>
      `).show();
      
      // Auto-hide success message after 3 seconds
      setTimeout(() => {
        $container.fadeOut();
      }, 3000);
    },

    clearErrors: function() {
      $(WCAP.config.errorContainer).hide().empty();
      $('.error').removeClass('error');
    },

    scrollToErrors: function() {
      const $errorContainer = $(WCAP.config.errorContainer);
      if ($errorContainer.length && $errorContainer.is(':visible')) {
        $('html, body').animate({
          scrollTop: $errorContainer.offset().top - 100
        }, 500);
      }
    },

    showFieldError: function($field, message) {
      WCAP.hideFieldError($field);
      $field.after(`<span class="field-error">${message}</span>`);
    },

    showFieldSuccess: function($field, message) {
      WCAP.hideFieldError($field);
      $field.after(`<span class="field-success">${message}</span>`);
      
      // Auto-hide success message after 3 seconds
      setTimeout(() => {
        $field.siblings('.field-success').fadeOut();
      }, 3000);
    },

    hideFieldError: function($field) {
      $field.siblings('.field-error, .field-success').remove();
    },

    /**
     * Initialize FAQ accordion
     */
    initFaqAccordion: function() {
      const accordionElements = document.getElementsByClassName('wbcom-faq-accordion');
      
      for (let i = 0; i < accordionElements.length; i++) {
        accordionElements[i].onclick = function() {
          this.classList.toggle('active');
          const panel = this.nextElementSibling;
          
          if (panel.style.maxHeight) {
            panel.style.maxHeight = null;
          } else {
            panel.style.maxHeight = panel.scrollHeight + 'px';
          }
        };
      }
    },

    /**
     * Show/hide loading indicator
     */
    showLoader: function() {
      if ($('.wcap-loader').length === 0) {
        $('body').append('<div class="wcap-loader"><div class="spinner"></div></div>');
      }
      $('.wcap-loader').show();
    },

    hideLoader: function() {
      $('.wcap-loader').hide();
    }
  };

  // Initialize when document is ready
  $(function() {
    // Only initialize on product edit pages
    if ($('body').hasClass('post-type-product') && $('.woo-audio-preview-table').length > 0) {
      WCAP.init();
    }
  });

  // Export WCAP object for external access if needed
  window.WCAP = WCAP;

})(jQuery);

/* Enhanced CSS for CDN URL detection */
jQuery(document).ready(function($) {
  $('<style>')
    .text(`
      .wcap-error-messages {
        margin-bottom: 15px;
        display: none;
      }
      
      .wcap-error-box {
        background: #ffebee;
        border: 1px solid #f44336;
        border-radius: 4px;
        padding: 15px;
        margin-bottom: 15px;
        border-left: 4px solid #f44336;
      }
      
      .wcap-error-box h4 {
        margin: 0 0 10px 0;
        color: #d32f2f;
        font-size: 14px;
        font-weight: 600;
      }
      
      .error-message {
        margin: 5px 0;
        color: #d32f2f;
        font-size: 13px;
      }
      
      .wcap-success-box {
        background: #e8f5e8;
        border: 1px solid #4caf50;
        border-radius: 4px;
        padding: 10px 15px;
        margin-bottom: 15px;
        border-left: 4px solid #4caf50;
      }
      
      .success-message {
        margin: 0;
        color: #2e7d32;
        font-size: 13px;
      }
      
      .field-error {
        color: #dc3232;
        font-size: 12px;
        display: block;
        margin-top: 3px;
      }
      
      .field-success {
        color: #0073aa;
        font-size: 12px;
        display: block;
        margin-top: 3px;
      }
      
      .wcap-audio-file input.error {
        border-color: #dc3232;
        box-shadow: 0 0 2px rgba(220, 50, 50, 0.8);
      }
      
      .wcap-audio-file input.success {
        border-color: #4caf50;
        box-shadow: 0 0 2px rgba(76, 175, 80, 0.8);
      }
      
      .wcap-cdn-row {
        background-color: #f0f8ff !important;
        border-left: 3px solid #0073aa;
      }
      
      .wcap-service-indicator {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 5px;
        padding: 5px 10px;
        background: #e8f4f8;
        border: 1px solid #0073aa;
        border-radius: 4px;
        font-size: 12px;
        color: #0073aa;
      }
      
      .wcap-service-indicator .service-icon {
        font-size: 14px;
      }
      
      .wcap-service-indicator .service-status {
        margin-left: auto;
        font-weight: bold;
        color: #28a745;
      }
      
      /* Google Drive specific styling */
      .wcap-service-google_drive {
        background: #e8f5e9;
        border-color: #4caf50;
      }
      
      .wcap-service-google_drive .service-icon {
        color: #4caf50;
      }
      
      .wcap_audio_urls.cdn-detected {
        border-color: #0073aa;
        box-shadow: 0 0 0 1px #0073aa;
      }
      
      .wcap-sort-placeholder {
        background: #f0f0f0;
        border: 2px dashed #ccc;
        height: 50px;
      }
      
      .wcap-loader {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
      }
      
      .wcap-loader .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #0073aa;
        border-radius: 50%;
        animation: spin 1s linear infinite;
      }
      
      @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
      }
    `)
    .appendTo('head');
});