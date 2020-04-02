(function ($) {
    'use strict';

    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */
    $(function () {
		$('.wcap-add-audio-cl').live('click', function () {
            var woo_audio_tr = '<tr class="wcap-audio-file"><td class="sort"></td><td class="file_name"><input class="input_text" placeholder="Mp3 Name" name="wcap_audio[wcap_audio_names][]" value="" type="text" ></td><td class="file_url"><input class="input_text" placeholder="http://" id="wcap_audio_urls" name="wcap_audio[wcap_audio_urls][]" value="" type="text"></td><td class="file_url_choose" width="1%"><input type="file" id="wcap_preview_attachment" name="wcap_audio[wcap_preview_attachment][]" value="" size="25"/></td><td width="15%"><a href="javascript:void(0)"  class="wcap-add-audio-cl">Add</a>&nbsp;<a href="javascript:void(0)" class="wcap-delete-audio-cl" id="wcap-delete-audio-id">Remove</a></td></tr>'; 
			$('.woo-audio-preview-table tbody').append(woo_audio_tr);			
        });
		
        $('.wcap-delete-audio-cl').live('click', function () {
			
			$(this).parents('tr').remove();
			/*
				$('.preview_files p.wcap-del-msg').text('');
				var file_url = $(this).data().file;
				var p_id = $(this).data().p_id;
				var data = {
					'action': 'wcap_delete_audio_ajax',
					'file_url': file_url,
					'p_id': p_id,
				};
				// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
				$.post(wcap_ajax_object.ajax_url, data, function (response) {
					$('.wcap_preview-tr .wcap-audio-file .file_url .input_text').val('');
					$('.wcap_preview-tr .wcap-audio-file .file_name .input_text').val('');
					if (response != '') {
						$('.preview_files p.wcap-del-msg').text('File Removed Successfully.').show();
					}
				});
			*/
        });
        $('body.post-type-product form#post').on('submit',function () {
            if ($('#wcap_preview_attachment').val() != '') {
                var ext = $('#wcap_preview_attachment').val().split('.').pop().toLowerCase();
                if ($.inArray(ext, ['mp3']) == -1) {
                    $('.preview_files p.wcap-del-msg').text("The audio type that you've uploaded is invalid. Please upload given audio type.").show();
                     $(".wcap-audio-file .file_url_choose #wcap_preview_attachment").addClass("focused");
                    $('html, body').animate({
                        scrollTop: ($('#wcap_preview_attachment').offset().top)
                    }, 500);
                    return false;
                }
            }
            if ($('#wcap_audio_urls').val() != '') {
                var ext = $('#wcap_audio_urls').val().split('.').pop().toLowerCase();
                if ($.inArray(ext, ['mp3']) == -1) {
                    $('.preview_files p.wcap-del-msg').text("The audio type that you've uploaded is invalid. Please upload given audio type.").show();
                        $(".wcap-audio-file td.file_url #wcap_audio_urls").addClass("focused");
                    $('html, body').animate({
                        scrollTop: ($('#wcap_audio_urls').offset().top)
                    }, 500);
                    return false;
                }
            }
        });
    });
})(jQuery);
