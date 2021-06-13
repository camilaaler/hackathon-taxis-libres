(function($){

	$(document).ready(function(){

		$('.ff-button-action-verify').click(function(){


            var $consent = $('.ff-input-checkbox-consent_general');

            if( !$consent.is(':checked')) {
                $consent.parent().css('border', '1px solid red');
                return false;
            }

            $consent.parent().css('border', '');



			var data = {};
			data.action = 'verify';
			data.licenseKey = $('.ff-input-license-key').val();
			data.email = $('.ff-input-email').val();

            if( $('.ff-input-checkbox-consent_newsletter').is(':checked') ) {
                data.consent_newsletter = 1;
            } else {
                data.consent_newsletter = 0;
            }


			frslib.ajax.frameworkAdminScreenRequest(data, function(response){
				console.log( response );
				if( parseInt(response.status) == 1 ) {
					$('.ff-ajax-output-holder').html(response.html);
				}

			});

			return false;

		});




		$(document).on('click', '.ff-button-action-register', function(){
			var data = {};
			data.action = 'register';
			data.licenseKey = $('.ff-input-hidden-key').val();
			data.email = $('.ff-input-hidden-email').val();

            if( $('.ff-input-checkbox-consent_newsletter').is(':checked') ) {
                data.consent_newsletter = 1;
            } else {
                data.consent_newsletter = 0;
            }

			frslib.ajax.frameworkAdminScreenRequest(data, function(response){


				$('.ff-ajax-output-holder').html(response);
				if( parseInt(response.status) == 1 ) {

					$('.ff-ajax-output-holder').html(response.html);

				}

			});

			return false;
		});

        $('.ffb-clean-backend-cache').click(function(){
            localStorage.removeItem('freshbuilder_data');
            localStorage.removeItem('freshbuilder_version_hash');
        });

	});

})(jQuery);