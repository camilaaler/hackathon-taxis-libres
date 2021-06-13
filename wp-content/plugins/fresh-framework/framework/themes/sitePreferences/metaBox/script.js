/**
 * Created by thomas on 21.10.16.
 */

(function($){

	$(document).ready(function(){
		// alert('aa');
		$('body').on('ff-builder-saving-ajax', function(){
			// alert('bb');
			$('#PostPageSettings').find('.ff-options-2-holder').trigger('ff-get-form-data', function( data ){
				var specification = {};
				specification.metaboxClass = 'ffMetaBoxSitePreferences';
				data.postId = $('#post_ID').val();
				frslib.ajax.frameworkRequest( 'ffMetaBoxManager', specification, data, function( response ) {
					console.log( response );
				});
			});
		});

        //components-button editor-post-publish-button

        $('body').on('click', '.components-button.editor-post-publish-button, .components-button.editor-post-preview', function(){

            if( $('.edit-with-fresh-builder-btn--active-mode-fresh').size() > 0  ) {
                console.log( 'fb je ready');

                var saveFreshBuilderCheck = setInterval(function(){
                    if( $('.editor-post-saved-state.is-saving').size() == 0 ) {
                        clearInterval( saveFreshBuilderCheck );

                        $('.ffb-save-ajax').trigger('click');
                    }

                }, 300);
    //            editor-post-saved-state is-saving
            }

            $('#PostPageSettings').find('.ff-options-2-holder').trigger('ff-get-form-data', function( data ){
				var specification = {};
				specification.metaboxClass = 'ffMetaBoxSitePreferences';
				data.postId = $('#post_ID').val();
				frslib.ajax.frameworkRequest( 'ffMetaBoxManager', specification, data, function( response ) {
					console.log( response );
				});
			});

        });


		var intervalCheck = setInterval(function(){

			if( $('.ff-opt-builder-printing-mode').size() > 0 ) {
				var $input = $('.ff-opt-builder-printing-mode');
				if( $input.val() == 'in-content' ) {
					$input.parents('tr:first').css('display', 'none');
				}
				console.log( 'check');
				// $('.ff-opt-builder-printing-mode').parents('tr:first').css('display', 'none');
				clearInterval( intervalCheck );
			}


		},500);

	});


})(jQuery);