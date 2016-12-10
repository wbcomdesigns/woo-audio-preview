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
        $('.wcap-delete-audio-cl').on('click', function () {
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
