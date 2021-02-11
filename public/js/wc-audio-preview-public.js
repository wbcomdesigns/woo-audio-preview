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
	}
);
