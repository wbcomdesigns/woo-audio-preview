(function ($) {
  "use strict";

  /**
   * Enhanced Audio Preview Admin JavaScript
   * Supports both dynamic (Pro) and fixed 3 fields (Free) modes
   */

  const WCAP = {
    config: {
      allowedTypes: ['mp3', 'wav', 'ogg', 'm4a', 'aac', 'flac', 'wma', 'webm'],
      maxFileSize: 50 * 1024 * 1024, // 50MB in bytes
      errorContainer: '.wcap-error-messages',
      isFixedMode: true // Set to true for free version with 3 fixed fields
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
      this.initFaqAccordion();
    },

    /**
     * Bind all event handlers
     */
    bindEvents: function() {
      // Determine mode based on presence of fixed fields
      if ($('.wcap-fixed-audio-fields').length >0) {
        this.config.isFixedMode = true;
        this.bindFixedModeEvents();
      } else {
        this.config.isFixedMode = false;
        this.bindDynamicModeEvents();
      }
      
      // Common events for both modes
      $(document).on('click', '.wcap-media-button', this.openMediaLibrary);
      $('body.post-type-product form#post').on('submit', this.validateForm);
      $(document).on('blur', '.wcap_audio_urls, .wcap-audio-url', this.validateSingleUrl);
      $(document).on('input', '.wcap_audio_urls, .wcap-audio-url', this.debounce(this.detectCdnUrl, 500));
      $(document).on('blur', '.wcap-file-name, .wcap-audio-name', this.validateAudioName);
    },

    /**
     * Bind events for fixed mode (3 fields)
     */
    bindFixedModeEvents: function() {
      $(document).on('click', '.wcap-clear-button', this.clearField);
    },

    /**
     * Bind events for dynamic mode (Pro)
     */
    bindDynamicModeEvents: function() {
      $(document).on('click', '.wcap-add-audio-cl', this.addAudioRow);
      $(document).on('click', '.wcap-delete-audio-cl', this.removeAudioRow);
    },

    /**
     * Initialize CDN URL detection for existing fields
     */
    initializeCdnDetection: function() {
      $('.wcap_audio_urls, .wcap-audio-url').each(function() {
        WCAP.detectCdnUrl.call(this);
      });
    },

    /**
     * Initialize sortable functionality
     */
    initializeSortable: function() {
      if (typeof $.fn.sortable !== 'undefined' && !this.config.isFixedMode) {
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
     * Show an inline "X cleared - Undo" message under a row.
     *
     * Replaces a browser confirm() for actions that are reversible. The dialog blocked the
     * whole screen to ask permission for something the owner could simply undo, and could
     * not be styled, translated, or announced properly to a screen reader.
     *
     * @param {jQuery}   $row   Row the message belongs to.
     * @param {string}   name   Track name, for the message text.
     * @param {Function} onUndo Restores whatever was cleared.
     */
    showUndo: function ($row, name, onUndo) {
      const strings = (typeof wcap_ajax_object !== 'undefined' && wcap_ajax_object.i18n) ? wcap_ajax_object.i18n : {};
      const label = $.trim(name) || strings.this_track || 'This track';

      $row.find('.wcap-undo-notice').remove();

      // role=alert so the change is announced without stealing focus.
      const $notice = $(
        '<div class="wcap-undo-notice" role="alert">' +
          '<span class="wcap-undo-notice__text"></span>' +
          '<button type="button" class="button-link wcap-undo-notice__button"></button>' +
        '</div>'
      );

      $notice.find('.wcap-undo-notice__text').text((strings.cleared || '%s cleared.').replace('%s', label));
      $notice.find('.wcap-undo-notice__button').text(strings.undo || 'Undo');

      $notice.on('click', '.wcap-undo-notice__button', function () {
        $notice.remove();
        onUndo();
      });

      $row.append($notice);
    },

/**
     * Clear field (Fixed mode)
     */
    clearField: function(e) {
      e.preventDefault();

      const $button = $(this);
      const fieldIndex = $button.data('field-index');

      /*
       * No confirm() here. Clearing these two inputs changes nothing until the product is
       * saved, so a blocking dialog asked the owner to approve something already reversible -
       * and it did it in English regardless of the store's language, because the string was a
       * literal in this file. The fields clear immediately and an inline Undo puts them back.
       */
      const $row = $button.closest('.wcap-field-row');
      const $url = $(`#wcap_audio_url_${fieldIndex}`);
      const $name = $(`#wcap_audio_name_${fieldIndex}`);
      const previous = { url: $url.val(), name: $name.val() };

      if (!previous.url && !previous.name) {
        return;
      }

      $url.val('').trigger('change');
      $name.val('');
      $button.hide();
      $row.find('.wcap-service-indicator').remove();

      WCAP.showUndo($row, previous.name, function () {
        $url.val(previous.url).trigger('change');
        $name.val(previous.name);
        $button.show();
        $name.trigger('focus');
      });
    },

    /**
     * Add new audio row (Dynamic mode)
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
     * Create new audio row HTML (Dynamic mode)
     */
    createAudioRow: function() {
      const rowHtml = `
        <tr class="wcap-audio-file"><td class="sort" data-label="Sort"></td><td class="file_name" data-label="Name"><input class="input_text wcap-file-name" 
                   placeholder="Audio Name" 
                   name="wcap_audio[wcap_audio_names][]" 
                   value="" 
                   type="text" 
                   required></td><td class="file_url" data-label="Audio URL"><input class="input_text wcap_audio_urls" 
                   placeholder="Enter URL or choose from Media Library" 
                   name="wcap_audio[wcap_audio_urls][]" 
                   value="" 
                   type="url"></td><td class="file_url_choose" width="1%" data-label="Choose"><button type="button" class="button wcap-media-button">Media Library</button></td><td width="15%" data-label="Actions"><button type="button" class="wcap-add-audio-cl button button-primary button-small" title="Add a new audio file">Add
            </button><button type="button" class="wcap-delete-audio-cl button button-secondary button-small" title="Remove this audio file">Remove
            </button></td></tr>`;
      
      return $(rowHtml);
    },

    /**
     * Remove audio row (Dynamic mode)
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
     * Detect CDN URL
     */
    detectCdnUrl: function() {
      const $input = $(this);
      const url = $.trim($input.val());
      let $row, $nameField;
      
      if (WCAP.config.isFixedMode) {
        // Fixed mode
        const fieldIndex = $input.attr('id').replace('wcap_audio_url_', '');
        $row = $input.closest('.wcap-field-row');
        $nameField = $(`#wcap_audio_name_${fieldIndex}`);
        
        if (!url) {
          WCAP.removeCdnIndicator(fieldIndex);
          WCAP.showClearButton(fieldIndex);
          return;
        }
      } else {
        // Dynamic mode
        $row = $input.closest('tr');
        $nameField = $row.find('.wcap-file-name');
        
        if (!url) {
          WCAP.removeServiceIndicator($row);
          return;
        }
      }

      const serviceInfo = WCAP.getCdnServiceInfo(url);
      
      if (serviceInfo) {
        if (WCAP.config.isFixedMode) {
          const fieldIndex = $input.attr('id').replace('wcap_audio_url_', '');
          WCAP.showCdnIndicator(fieldIndex, serviceInfo);
        } else {
          WCAP.showServiceIndicator($row, serviceInfo);
        }
        
        $input.removeClass('error').addClass('cdn-detected');
        
        // Auto-fill name if empty
        if (!$nameField.val()) {
          const suggestedName = WCAP.generateAudioName(url, serviceInfo);
          if (suggestedName) {
            $nameField.val(suggestedName);
          }
        }
      } else {
        if (WCAP.config.isFixedMode) {
          const fieldIndex = $input.attr('id').replace('wcap_audio_url_', '');
          WCAP.removeCdnIndicator(fieldIndex);
        } else {
          WCAP.removeServiceIndicator($row);
        }
        $input.removeClass('cdn-detected');
      }
      
      // Show/hide clear button for fixed mode
      if (WCAP.config.isFixedMode) {
        const fieldIndex = $input.attr('id').replace('wcap_audio_url_', '');
        WCAP.showClearButton(fieldIndex);
      }
    },

    /**
     * Show/hide clear button (Fixed mode)
     */
    showClearButton: function(fieldIndex) {
      const $clearButton = $(`.wcap-clear-button[data-field-index="${fieldIndex}"]`);
      const $urlField = $(`#wcap_audio_url_${fieldIndex}`);
      
      if ($urlField.val()) {
        if ($clearButton.length === 0) {
          // Create clear button if it doesn't exist
          const clearBtn = `<button type="button" class="button wcap-clear-button" data-field-index="${fieldIndex}">Clear</button>`;
          $urlField.siblings('.wcap-media-button').after(' ').after(clearBtn);
        } else {
          $clearButton.show();
        }
      } else {
        $clearButton.hide();
      }
    },

    /**
     * Show CDN indicator (Fixed mode)
     */
    showCdnIndicator: function(fieldIndex, cdnInfo) {
      const $fieldRow = $(`#wcap_audio_url_${fieldIndex}`).closest('.wcap-field-row');
      
      // Remove existing indicator
      $fieldRow.find('.wcap-service-indicator').remove();
      
      // Add new indicator
      const indicator = `<div class="wcap-service-indicator">${cdnInfo.serviceName} link detected</div>`;
      $fieldRow.append(indicator);
    },

    /**
     * Remove CDN indicator (Fixed mode)
     */
    removeCdnIndicator: function(fieldIndex) {
      const $fieldRow = $(`#wcap_audio_url_${fieldIndex}`).closest('.wcap-field-row');
      $fieldRow.find('.wcap-service-indicator').remove();
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
          /dropbox\.com\/s\/([a-zA-Z0-9_-]+)\/(.+\.(mp3|wav|ogg|m4a|flac))(?:\?.*)?/i,
          /dropbox\.com\/scl\/fi\/([a-zA-Z0-9_-]+)\/(.+\.(mp3|wav|ogg|m4a|flac))(?:\?.*)?/i,
          /dl\.dropbox(?:usercontent)?\.com\/s\/([a-zA-Z0-9_-]+)\/(.+\.(mp3|wav|ogg|m4a|flac))(?:\?.*)?/i
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
        return nameWithoutExt.replace(/[-_]/g, ' ').replace(/\b\w/g, l =>l.toUpperCase());
      }
      
      return baseName;
    },

    /**
     * Show service indicator (Dynamic mode)
     */
    showServiceIndicator: function($row, serviceInfo) {
      WCAP.removeServiceIndicator($row);
      
      const indicator = `
        <div class="wcap-service-indicator wcap-service-${serviceInfo.service}"><span class="service-icon">${WCAP.getServiceIcon(serviceInfo.service)}</span><span class="service-text">${serviceInfo.serviceName} link detected</span><span class="service-status">Ready for preview</span></div>`;
      
      $row.find('.file_url').append(indicator);
      $row.addClass('wcap-cdn-row');
      
      // Show success message briefly
      WCAP.showFieldSuccess($row.find('.wcap_audio_urls'), 'CDN link detected!');
    },

    /**
     * Remove service indicator (Dynamic mode)
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
        soundcloud: '',
        spotify: '',
        amazon_s3: '',
        cloudfront: '',
        google_drive: '',
        dropbox: ''
      };
      return icons[service] || '';
    },

    /**
     * Open WordPress Media Library
     */
    openMediaLibrary: function(e) {
      e.preventDefault();
      
      const $button = $(this);
      let $row, $urlField, $nameField, rowIndex;
      
      if (WCAP.config.isFixedMode) {
        // Fixed mode
        const fieldIndex = $button.data('field-index');
        $urlField = $(`#wcap_audio_url_${fieldIndex}`);
        $nameField = $(`#wcap_audio_name_${fieldIndex}`);
        rowIndex = fieldIndex;
      } else {
        // Dynamic mode
        $row = $button.closest('tr');
        $urlField = $row.find('.wcap_audio_urls');
        $nameField = $row.find('.wcap-file-name');
        rowIndex = $row.index();
      }
      
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
            if (!$nameField.val()) {
              $nameField.val(attachment.title || attachment.filename);
            }
            
            // Remove any CDN indicators since this is a media library file
            if (WCAP.config.isFixedMode) {
              WCAP.removeCdnIndicator(rowIndex);
            } else {
              WCAP.removeServiceIndicator($row);
            }
            
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
      
      if (WCAP.config.isFixedMode) {
        // Fixed mode validation
        $('.wcap-audio-field-group').each(function(index) {
          const $nameField = $(this).find('.wcap-audio-name');
          const $urlField = $(this).find('.wcap-audio-url');
          const name = $.trim($nameField.val());
          const url = $.trim($urlField.val());
          
          // Clear previous errors
          WCAP.hideFieldError($nameField);
          WCAP.hideFieldError($urlField);
          $nameField.removeClass('error');
          $urlField.removeClass('error');
          
          // If either field has content, both are required
          if (name || url) {
            if (!name) {
              $nameField.addClass('error');
              WCAP.showFieldError($nameField, 'Audio name is required when URL is provided.');
              errors.push('Audio name is required for all files.');
            }
            
            if (!url) {
              $urlField.addClass('error');
              WCAP.showFieldError($urlField, 'Audio URL is required when name is provided.');
              errors.push('Audio URL is required when name is provided.');
            } else {
              hasValidAudio = true;
            }
          }
        });
      } else {
        // Dynamic mode validation
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
      }
      
      if (errors.length >0) {
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
        WCAP.showFieldSuccess($input, `${serviceInfo.serviceName} link detected! `);
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
      $('.wcap_audio_urls, .wcap-audio-url').each(function() {
        WCAP.validateSingleUrl.call(this);
      });
    },

    /**
     * Initialize FAQ accordion
     */
    initFaqAccordion: function() {
      const accordionElements = document.getElementsByClassName('wbcom-faq-accordion');
      
      for (let i = 0; i < accordionElements.length; i++) {
        accordionElements[i].onclick = function() {
          // Remove active class from all other accordions
          for (let j = 0; j < accordionElements.length; j++) {
            if (accordionElements[j] !== this) {
              accordionElements[j].classList.remove('active');
              const otherPanel = accordionElements[j].nextElementSibling;
              if (otherPanel) {
                otherPanel.style.maxHeight = null;
              }
            }
          }
          
          // Toggle this accordion
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
     * Utility functions
     */
    
    getFileExtension: function(url) {
      if (!url) return '';
      // Clean URL from query parameters and hash
      const cleanUrl = url.split('?')[0].split('#')[0];
      // Extract extension from the clean URL
      const parts = cleanUrl.split('.');
      return parts.length >1 ? parts.pop().toLowerCase() : '';
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
        const later = () =>{
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
      
      const errorHtml = errors.map(error =>`<p class="error-message">${error}</p>`).join('');
      
      $container.html(`
        <div class="wcap-error-box"><h4>Please fix the following errors:</h4>${errorHtml}
        </div>`).show();
    },

    showSuccess: function(message) {
      const $container = $('.wcap-error-messages');
      $container.html(`
        <div class="wcap-success-box"><p class="success-message">${message}</p></div>`).show();
      
      // Auto-hide success message after 3 seconds
      setTimeout(() =>{
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
      setTimeout(() =>{
        $field.siblings('.field-success').fadeOut();
      }, 3000);
    },

    hideFieldError: function($field) {
      $field.siblings('.field-error, .field-success').remove();
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
    const currentUrl = window.location.href;
    const isProductEditPage = $('body').hasClass('post-type-product');
    const isWCAPSettingsPage = currentUrl.includes('page=woo-audio-preview-settings');
    
    // Only initialize on product edit pages and admin settings
    if (isProductEditPage || isWCAPSettingsPage || $('.woo-audio-preview-table').length >0 || $('.wcap-fixed-audio-fields').length >0) {
      WCAP.init();
    }
  });

  // Export WCAP object for external access if needed
  window.WCAP = WCAP;

})(jQuery);

/* Enhanced CSS for CDN URL detection and fixed fields */
/**
 * Add and remove preview rows.
 *
 * The markup comes from a template the PHP renders with render_row(), so a row added here is the
 * row the server would have drawn - including cells contributed by an extension. Cloning the last
 * visible row would copy its values; building the markup in JS would mean two definitions of a row
 * drifting apart.
 */
( function ( $ ) {
	'use strict';

	function rows() {
		return document.querySelectorAll( '.wcap-audio-rows .wcap-audio-row' );
	}

	$( document ).on( 'click', '[data-wcap-add-row], #wcap-pro-add-more', function ( e ) {
		e.preventDefault();

		var template = document.getElementById( 'wcap-metabox-row-template' );
		var body     = document.querySelector( '.wcap-audio-rows' );

		if ( ! template || ! body ) {
			return;
		}

		body.insertAdjacentHTML( 'beforeend', template.innerHTML.split( '__INDEX__' ).join( rows().length ) );
	} );

	$( document ).on( 'click', '.wcap-remove-row', function ( e ) {
		e.preventDefault();

		var $row     = $( this ).closest( 'tr' );
		var $restore = $row.clone( true, true );
		var hasData  = $row.find( 'input' ).filter( function () { return $( this ).val(); } ).length > 0;

		/*
		 * A row the owner has typed into is worth an Undo. This used to drop it silently: one
		 * mis-click on a table of tracks and the name and URL were gone with no way back, and
		 * nothing on screen acknowledged it had happened. There is no confirm() dialog either -
		 * removing a row changes nothing until the product is saved, so blocking the screen to
		 * ask permission would be asking about something already reversible.
		 */
		if ( ! hasData ) {
			if ( rows().length > 1 ) {
				$row.remove();
			} else {
				$row.find( 'input' ).val( '' );
			}
			return;
		}

		var strings = ( typeof wcap_ajax_object !== 'undefined' && wcap_ajax_object.i18n ) ? wcap_ajax_object.i18n : {};
		var name    = $.trim( $row.find( '.wcap-audio-name' ).val() ) || strings.this_track || 'This track';

		var $notice = $(
			'<tr class="wcap-undo-row"><td colspan="5">' +
				'<div class="wcap-undo-notice" role="alert">' +
					'<span class="wcap-undo-notice__text"></span>' +
					'<button type="button" class="button-link wcap-undo-notice__button"></button>' +
				'</div>' +
			'</td></tr>'
		);

		$notice.find( '.wcap-undo-notice__text' ).text( ( strings.removed || '%s removed.' ).replace( '%s', name ) );
		$notice.find( '.wcap-undo-notice__button' ).text( strings.undo || 'Undo' );

		$notice.on( 'click', '.wcap-undo-notice__button', function () {
			$notice.replaceWith( $restore );
			$restore.find( '.wcap-audio-name' ).trigger( 'focus' );
		} );

		$row.replaceWith( $notice );
	} );
}( jQuery ) );
