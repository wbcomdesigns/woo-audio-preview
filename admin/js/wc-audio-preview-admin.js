(function ($) {
  "use strict";

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
    if (typeof wp !== 'undefined' && wp.i18n) {
        var { __ } = wp.i18n;
    }
    $(document).on("click", ".wcap-add-audio-cl", function (e) {
      var woo_audio_tr =
        '<tr class="wcap-audio-file"><td class="sort"></td><td class="file_name"><input class="input_text" placeholder="Audio Name" name="wcap_audio[wcap_audio_names][]" value="" type="text" ></td><td class="file_url"><input class="input_text" placeholder="http://" id="wcap_audio_urls" name="wcap_audio[wcap_audio_urls][]" value="" type="text"></td><td class="file_url_choose wcap_preview_attachment" width="1%"><input type="file" id="wcap_preview_attachment" class="wcap_preview_attachment" name="wcap_audio[wcap_preview_attachment][]" value="" size="25"/></td><td width="15%"><a href="javascript:void(0)"  class="tooltip wcap-add-audio-cl button button-primary button-small">Add<span class="tooltiptext">Add a new audio file</span></a>&nbsp;<a href="javascript:void(0)" class="tooltip wcap-delete-audio-cl button button-primary button-small" id="wcap-delete-audio-id">Remove<span class="tooltiptext">Remove this audio file</span></a></td></tr>';
      $(".woo-audio-preview-table tbody").append(woo_audio_tr);
    });

    $(document).on("click", ".wcap-delete-audio-cl", function (e) {
      e.preventDefault();
      $(this).parents("tr").remove();
      var file_url = $(this).closest('tr').find('.file_url input[type=text]').val();
      var postId = $(this).data('p_id');

      if ($("tr.wcap-audio-file").length < 2) {
        $("a.wcap-delete-audio-cl").remove();
      }
      var data = {
        action: "wcap_delete_audio_ajax",
        file_url:file_url,
        p_id :postId,
        nonce: wcap_ajax_object.nonce,
      };
      $.post(ajaxurl, data, function (response) {
        //location.reload();
      });
    });
  $("body.post-type-product form#post").on("submit", function (e) {
     e.preventDefault();
    let isValid = true;
    let emptyRows = [];
    const allowedExtensions = wcap_ajax_object.allowedExtensions;
    const allowedExtensionsStr = allowedExtensions.join(', ').toUpperCase();
    const maxFileSizeMB = 10;
    const maxFileSizeBytes = maxFileSizeMB * 1024 * 1024;

    // Clear previous error states
    $(".preview_files p.wcap-del-msg").text("").hide();
    $(".focused").removeClass("focused");

    // Validate preview attachment
    $(".wcap_preview_attachment").each(function () {
       
      let val = $(this).val();
      
      if (val !== "") {
        let ext = val.split(".").pop().toLowerCase();
        let file = this.files[0]
        if ($.inArray(ext, allowedExtensions) === -1) {
         
          $(".preview_files p.wcap-del-msg")
            .text(__(`Invalid audio format. Allowed: ${allowedExtensionsStr}`,"wc-audio-preview"))
            .show();
            $(this).addClass("focused");
          isValid = false;
           return;
        }
        if (file && file.size > maxFileSizeBytes) {
          $(".preview_files p.wcap-del-msg")
            .text(__(`File is too large. Max size: ${maxFileSizeMB} MB`, "wc-audio-preview"))
            .show();
          $(this).addClass("focused");
          isValid = false;
        }
      }
    });

    $(".wcap-audio-file").each(function () {
      let $row = $(this);
      let fileInput = $row.find("input[type='file']");
      let textInput = $row.find(".file_url input[type='text']");
      let fileVal = fileInput.val();
      let urlVal = textInput.val();
      let errorShown = false;

      // Reset previous error state
      $row.find("p.wcap-del-msg").text("").hide();
      fileInput.removeClass("focused");
      textInput.removeClass("focused");

      if (fileVal !== "") {
        let ext = fileVal.split(".").pop().toLowerCase();
        if ($.inArray(ext, allowedExtensions) === -1) {
          
          $row.closest(".preview_files").find("p.wcap-del-msg").text(__(`External URL must point to an audio file. Allowed: ${allowedExtensionsStr}`,"wc-audio-preview")).show();
          fileInput.addClass("focused");
          isValid = false;
          errorShown = true;
          return;
        }
        
      } else if (urlVal !== "") {
        let ext = urlVal.split(".").pop().toLowerCase();
        if ($.inArray(ext, allowedExtensions) === -1) {
          
          $row.closest(".preview_files").find("p.wcap-del-msg").text(__(`External URL must point to an audio file. Allowed: ${allowedExtensionsStr}`,"wc-audio-preview")).show();
          textInput.addClass("focused");
          isValid = false;
          errorShown = true;
          return;
        }
      } else {
        // Mark this row for removal if both inputs are empty
        emptyRows.push($row);
      }
    });
    emptyRows.forEach(row => row.remove());


    /* If any invalid file, prevent form submission*/
    if (!isValid) {
      const $target = $(".focused:first");
      const offset = $target.offset();

      if (offset) {
        $("html, body").animate(
          {
            scrollTop: offset.top,
          },
          500
        );
      }

      return false;
    }

  });

    /*faq tab accordion*/
    var wb_ads_elmt = document.getElementsByClassName(
      "wbcom-faq-accordion"
    );
    var k;
    var wb_ads_elmt_len = wb_ads_elmt.length;
    for (k = 0; k < wb_ads_elmt_len; k++) {
      wb_ads_elmt[k].onclick = function () {
        this.classList.toggle("active");
        var panel = this.nextElementSibling;
        if (panel.style.maxHeight) {
          panel.style.maxHeight = null;
        } else {
          panel.style.maxHeight = panel.scrollHeight + "px";
        }
      };
    }
  });
})(jQuery);
