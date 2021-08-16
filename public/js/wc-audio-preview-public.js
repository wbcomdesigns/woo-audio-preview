jQuery( document ).ready(
	function($){
		'use strict';

		$( document ).on(
			'click',
			'.wcap-preview-btn-div',
			function(){
				var id = $( this ).data( 'id' );
				$( "#" + id ).slideToggle( "slow" );
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
  
    
	}
);
