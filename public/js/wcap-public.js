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
            // this.wcapStopAllPlayers();
        },

        bindEvents: function() {
            // Preview button clicks (regular audio only — not GDrive/SoundCloud).
            $(document).on('click', '.wcap-preview-button:not(.wcap-gdrive-button):not(.wcap-soundcloud-button)', this.handlePreviewClick.bind(this));

            // Google Drive button clicks.
            $(document).on('click', '.wcap-gdrive-button', this.handleGDriveClick.bind(this));

            // SoundCloud button clicks.
            $(document).on('click', '.wcap-soundcloud-button', this.handleSoundCloudClick.bind(this));

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

            // Stop other players (including SoundCloud/Google)
            if (typeof wcapStopAllPlayers === 'function') {
                const key = audioId.replace('wcap-audio-', '');
                wcapStopAllPlayers(key);
            }

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

        handleGDriveClick: function(e) {
            e.preventDefault();
            var $item = $(e.currentTarget).closest('.wcap-gdrive-item');
            var key = $item.data('gdrive-key');

            wcapStopAllPlayers(key);

            var player = document.getElementById('wcap-gdrive-' + key);
            var iframe = player.querySelector('iframe');
            var playIcon = document.getElementById('wcap-play-' + key);
            var pauseIcon = document.getElementById('wcap-pause-' + key);

            // Close all other GDrive players.
            document.querySelectorAll('.wcap-gdrive-player').forEach(function(p) {
                if (p.id !== 'wcap-gdrive-' + key && p.style.display !== 'none') {
                    var otherIframe = p.querySelector('iframe');
                    var otherKey = p.id.replace('wcap-gdrive-', '');
                    p.style.display = 'none';
                    p.previousElementSibling.classList.remove('playing');
                    if (otherIframe) otherIframe.src = '';
                    var otherPlay = document.getElementById('wcap-play-' + otherKey);
                    var otherPause = document.getElementById('wcap-pause-' + otherKey);
                    if (otherPlay) otherPlay.style.display = 'block';
                    if (otherPause) otherPause.style.display = 'none';
                }
            });

            // Toggle this player.
            if (player.style.display === 'none') {
                player.style.display = 'block';
                $(e.currentTarget).addClass('playing');
                iframe.src = iframe.getAttribute('data-src');
                if (playIcon) playIcon.style.display = 'none';
                if (pauseIcon) pauseIcon.style.display = 'block';
            } else {
                player.style.display = 'none';
                $(e.currentTarget).removeClass('playing');
                iframe.src = '';
                if (playIcon) playIcon.style.display = 'block';
                if (pauseIcon) pauseIcon.style.display = 'none';
            }
        },

        handleSoundCloudClick: function(e) {
            e.preventDefault();
            var $item = $(e.currentTarget).closest('.wcap-soundcloud-item');
            var key = $item.data('soundcloud-key');

            wcapStopAllPlayers(key);

            var player = document.getElementById('wcap-soundcloud-' + key);
            var playIcon = document.getElementById('wcap-play-' + key);
            var pauseIcon = document.getElementById('wcap-pause-' + key);
            var isHidden = player.style.display === 'none';

            // Hide all SoundCloud players and reset icons.
            document.querySelectorAll('.wcap-soundcloud-player').forEach(function(el) { el.style.display = 'none'; });
            $('.wcap-soundcloud-item .wcap-play-icon').removeClass('inactive');
            $('.wcap-soundcloud-item .wcap-pause-icon').addClass('inactive');

            if (isHidden) {
                player.style.display = 'block';
                if (playIcon) playIcon.style.display = 'none';
                if (pauseIcon) pauseIcon.style.display = 'inline-block';
            }
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
    // handling soundcloud and google palyer....
    function wcapStopAllPlayers(excludeKey = null) {
        // Stop all HTML5 <audio> players
        document.querySelectorAll('.wcap-audio-element').forEach(audio => {
            if (!excludeKey || audio.id !== `wcap-audio-${excludeKey}`) {
                audio.pause();
                audio.currentTime = 0;
                const button = document.querySelector(`.wcap-preview-button[data-audio-id="${audio.id}"]`);
                if (button) {
                    button.classList.remove('playing');
                }
            }
        });

        // Stop all Google Drive iframes
        document.querySelectorAll('.wcap-gdrive-player').forEach(player => {
            const key = player.id.replace('wcap-gdrive-', '');
            if (!excludeKey || key !== excludeKey) {
                const iframe = player.querySelector('iframe');
                if (iframe) iframe.src = ''; // Unload
                player.style.display = 'none';

                const playIcon = document.getElementById(`wcap-play-${key}`);
                const pauseIcon = document.getElementById(`wcap-pause-${key}`);
                if (playIcon && pauseIcon) {
                    playIcon.style.display = 'block';
                    pauseIcon.style.display = 'none';
                }
            }
        });

        // Stop all SoundCloud players using the Widget API
        document.querySelectorAll('.wcap-soundcloud-player iframe').forEach((iframe) => {
            const playerId = iframe.parentElement.id.replace('wcap-soundcloud-', '');
            if (!excludeKey || playerId !== excludeKey) {
                const widget = SC.Widget(iframe);
                widget.pause(); // This actually pauses the audio
                iframe.parentElement.style.display = 'none';

                const playIcon = document.getElementById(`wcap-play-${playerId}`);
                const pauseIcon = document.getElementById(`wcap-pause-${playerId}`);
                if (playIcon && pauseIcon) {
                    // playIcon.classList.add("active");
                    $(playIcon).removeClass("inactive");
                    $(pauseIcon).addClass("inactive");
                    // pauseIcon.style.display = 'none';
                }
            }
        });
    }


    window.wcapStopAllPlayers = wcapStopAllPlayers;


})(jQuery);