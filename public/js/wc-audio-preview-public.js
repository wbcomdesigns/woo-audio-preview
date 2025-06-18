(function($) {
    'use strict';

    /**
     * Modern Audio Preview Player
     */
    const WCAPPlayer = {
        currentAudio: null,
        currentItem: null,
        players: new Map(),

        init: function() {
            this.bindEvents();
            this.initializeAudioElements();
        },

        bindEvents: function() {
            // Preview button clicks
            $(document).on('click', '.wcap-preview-button', this.handlePreviewClick.bind(this));
            
            // Progress bar clicks
            $(document).on('click', '.wcap-progress-bar', this.handleProgressClick.bind(this));
            
            // Prevent form submission on button clicks
            $(document).on('click', '.wcap-preview-button', function(e) {
                e.preventDefault();
                e.stopPropagation();
            });
        },

        initializeAudioElements: function() {
            $('.wcap-audio-element').each(function() {
                const audio = this;
                const $item = $(audio).closest('.wcap-preview-item');
                const audioId = audio.id;
                
                WCAPPlayer.players.set(audioId, {
                    audio: audio,
                    item: $item,
                    button: $item.find('.wcap-preview-button'),
                    progressBar: $item.find('.wcap-progress-fill'),
                    timeDisplay: $item.find('.wcap-time'),
                    isPlaying: false
                });

                // Audio event listeners
                audio.addEventListener('loadstart', () => WCAPPlayer.onLoadStart(audioId));
                audio.addEventListener('canplay', () => WCAPPlayer.onCanPlay(audioId));
                audio.addEventListener('play', () => WCAPPlayer.onPlay(audioId));
                audio.addEventListener('pause', () => WCAPPlayer.onPause(audioId));
                audio.addEventListener('ended', () => WCAPPlayer.onEnded(audioId));
                audio.addEventListener('timeupdate', () => WCAPPlayer.onTimeUpdate(audioId));
                audio.addEventListener('error', (e) => WCAPPlayer.onError(audioId, e));
            });
        },

        handlePreviewClick: function(e) {
            e.preventDefault();
            const $button = $(e.currentTarget);
            const $item = $button.closest('.wcap-preview-item');
            const audioId = $item.data('audio-id');
            const player = this.players.get(audioId);
            
            if (!player) return;

            if (player.isPlaying) {
                this.pauseAudio(audioId);
            } else {
                this.playAudio(audioId);
            }
        },

        handleProgressClick: function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const $progressBar = $(e.currentTarget);
            const $item = $progressBar.closest('.wcap-preview-item');
            const audioId = $item.data('audio-id');
            const player = this.players.get(audioId);
            
            if (!player || !player.audio.duration) return;

            const rect = $progressBar[0].getBoundingClientRect();
            const x = e.clientX - rect.left;
            const percentage = x / rect.width;
            const newTime = percentage * player.audio.duration;
            
            player.audio.currentTime = Math.max(0, Math.min(newTime, player.audio.duration));
        },

        playAudio: function(audioId) {
            const player = this.players.get(audioId);
            if (!player) return;

            // Pause any currently playing audio
            if (this.currentAudio && this.currentAudio !== player.audio) {
                const currentId = this.currentAudio.id;
                this.pauseAudio(currentId);
            }

            this.currentAudio = player.audio;
            this.currentItem = player.item;

            // Start loading if not already loaded
            if (player.audio.readyState === 0) {
                player.audio.load();
            }

            player.audio.play().catch(error => {
                console.error('Audio playback failed:', error);
                this.onError(audioId, error);
            });
        },

        pauseAudio: function(audioId) {
            const player = this.players.get(audioId);
            if (!player) return;

            player.audio.pause();
        },

        onLoadStart: function(audioId) {
            const player = this.players.get(audioId);
            if (!player) return;

            player.item.addClass('loading');
            player.button.find('.wcap-play-icon').hide();
            player.button.find('.wcap-pause-icon').hide();
            player.button.find('.wcap-loading-spinner').show();
        },

        onCanPlay: function(audioId) {
            const player = this.players.get(audioId);
            if (!player) return;

            player.item.removeClass('loading');
            player.button.find('.wcap-loading-spinner').hide();
            
            if (player.isPlaying) {
                player.button.find('.wcap-pause-icon').show();
            } else {
                player.button.find('.wcap-play-icon').show();
            }
        },

        onPlay: function(audioId) {
            const player = this.players.get(audioId);
            if (!player) return;

            player.isPlaying = true;
            player.item.addClass('playing');
            player.button.find('.wcap-play-icon').hide();
            player.button.find('.wcap-loading-spinner').hide();
            player.button.find('.wcap-pause-icon').show();
            player.button.find('.wcap-progress-container').slideDown(200);
        },

        onPause: function(audioId) {
            const player = this.players.get(audioId);
            if (!player) return;

            player.isPlaying = false;
            player.item.removeClass('playing');
            player.button.find('.wcap-pause-icon').hide();
            player.button.find('.wcap-play-icon').show();
        },

        onEnded: function(audioId) {
            const player = this.players.get(audioId);
            if (!player) return;

            player.isPlaying = false;
            player.item.removeClass('playing');
            player.button.find('.wcap-pause-icon').hide();
            player.button.find('.wcap-play-icon').show();
            player.audio.currentTime = 0;
            player.progressBar.css('width', '0%');
            player.button.find('.wcap-progress-container').slideUp(200);
        },

        onTimeUpdate: function(audioId) {
            const player = this.players.get(audioId);
            if (!player || !player.audio.duration) return;

            const currentTime = player.audio.currentTime;
            const duration = player.audio.duration;
            const percentage = (currentTime / duration) * 100;
            
            player.progressBar.css('width', percentage + '%');
            player.timeDisplay.text(this.formatTime(currentTime) + ' / ' + this.formatTime(duration));
        },

        onError: function(audioId, error) {
            const player = this.players.get(audioId);
            if (!player) return;

            console.error('Audio error:', error);
            player.item.removeClass('loading playing').addClass('error');
            player.button.find('.wcap-loading-spinner').hide();
            player.button.find('.wcap-pause-icon').hide();
            player.button.find('.wcap-play-icon').show();
            
            // Show error message
            const errorText = wcap_public.error_text || 'Error loading audio';
            player.button.find('.wcap-preview-name').text(errorText).css('color', '#dc3545');
            
            // Reset after 3 seconds
            setTimeout(() => {
                player.item.removeClass('error');
                const originalName = player.audio.dataset.name;
                player.button.find('.wcap-preview-name').text(originalName).css('color', '');
            }, 3000);
        },

        formatTime: function(seconds) {
            if (isNaN(seconds)) return '0:00';
            
            const minutes = Math.floor(seconds / 60);
            const secs = Math.floor(seconds % 60);
            return minutes + ':' + (secs < 10 ? '0' : '') + secs;
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        if ($('.wcap-audio-preview-container').length > 0) {
            WCAPPlayer.init();
        }
    });

    // Handle dynamic content (AJAX)
    $(document).on('wcap:reinit', function() {
        WCAPPlayer.init();
    });

})(jQuery);