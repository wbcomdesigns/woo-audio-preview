jQuery(document).ready(
	function ($) {
		'use strict';

		// Pause all other audio players when one starts
		$(document).on('play', 'audio', function() {
			$('audio').not(this).each(function() {
				this.pause();
			});
		});

		$(document).on(
			'click',
			'.wcap-preview-btn-div',
			function () {
				var id = $(this).data('id');
        
				// Hide all other players
				$('.wcap-player-cl').not('#' + id).each(function () {
					$(this).slideUp('fast');
					$(this).find('audio').each(function () {
						this.pause();
					});
				});
				
				// Toggle current player
				$("#" + id).slideToggle("slow");
			}
		);
		
		$(document).on(
			'click',
			'.wcap-audio-title #wcap_select_audio',
			function () {
				var links = $(this).data('audio');
				$("#audio_player").attr('src', links);

			}
		);


		$(document).on(
			'click',
			'.wcap-audio-playlist-row .wcap-audio-small-toggle-btn #wcap_icon_select_audio',
			function () {
				var links = $(this).data('audio');
				$("#audio_player").attr('src', links);

			}
		);

		$(document).on(
			'click',
			'.wcap-audio-playlist-row',
			function () {
				var audio = document.getElementById('audio_player');
				if ($(this).hasClass("active")) {
					$(".wcap-audio-playlist-row").removeClass("active");
				}
				else {
					$(".wcap-audio-playlist-row").removeClass("active");
					$(this).addClass("active");
				}
				$(".wcap-audio-small-toggle-btn .dashicons").each(function () {
					$(this).removeClass("dashicons-controls-pause");
					$(this).addClass("dashicons-controls-play");

				});
				if ($(this).find('span.dashicons').hasClass('dashicons-controls-play')) {
					$(this).find('span.dashicons').removeClass('dashicons-controls-play');
					$(this).find('span.dashicons').addClass('dashicons-controls-pause');
				} else {
					$(this).find('span.dashicons').removeClass('dashicons-controls-pause');
					$(this).find('span.dashicons').addClass('dashicons-controls-play');
				}
				//$(this).parent().siblings(".wcap-audio-small-toggle-btn").find('span').attr('class', 'dashicons dashicons-controls-pause');
			}
		);




	}
);
