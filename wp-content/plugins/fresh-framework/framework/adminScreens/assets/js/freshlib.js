
var freshlib = function() {};

(function($) {
	$(document).ready(function() {
		freshlib.adminScreenInfo = function() {};
		freshlib.adminScreenInfo.adminScreenName = $('.ff-view-identification').find('.admin-screen-name').html();
		freshlib.adminScreenInfo.adminViewName = $('.ff-view-identification').find('.admin-view-name').html();
		
		freshlib.ajax = function( data, callback ) {
			$.post(
					ajaxurl,
					{
						'action': 'ff_ajax_admin',
						'data':   data,
						'adminScreenName': freshlib.adminScreenInfo.adminScreenName,
						'adminViewName': freshlib.adminScreenInfo.adminViewName,
					},
						callback
					);
		};


        if( $('.toplevel_page_ThemeDashboard').length > 0 ) {

            var found = $('.toplevel_page_ThemeDashboard').find('.wp-submenu').html().indexOf('admin.php?page=HireUs');

            if( found > -1 ) {
                $('.toplevel_page_ThemeDashboard').attr('href', 'admin.php?page=HireUs');
            } else {
                $('.toplevel_page_ThemeDashboard').attr('href', 'admin.php?page=Dashboard');
            }


        }
		
	});
})(jQuery);