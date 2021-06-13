(function($){

    $(document).ready(function(){

        $('#toplevel_page_ThemeDashboard').children('ul').children('li.wp-first-item').before('<li class="freshface-ark-academy-link-wrapper"><a target="_blank" href="http://arktheme.com/academy/"><span>Training Academy</a></a></li>');


        var ids = [];

        function addNewId( id, type, url ) {
            var newThing = {};
            newThing.id = id;
            newThing.type = type;
            newThing.url = url;

            ids[ id ] = newThing;
        }



        var getHtmlText = function( id) {

            var newThing = ids[ id ];

            var toReturn = '';
            switch( newThing.type ) {
                case 1:
                    toReturn += ' <a href="' + newThing.url + '" class="aa-help aa-help--type-1" target="_blank" title="Watch Video Lesson"></a>';
                    break;

                case 2:
                    toReturn += ' <a href="' + newThing.url + '" class="aa-help aa-help--type-2" target="_blank" title="Watch Video Lesson"></a>';
                    break;

                case 3:
                    toReturn += ' <a href="' + newThing.url + '" class="aa-help aa-help--type-3" target="_blank" title="Watch Video Lesson"></a>';
                    break;

            }

            return toReturn;
        }

        //self::_addAcademyLesson(81, 3, 'http://arktheme.com/academy/tutorial/admin-screen-templates/');
        //self::_addAcademyLesson(82, 3, 'http://arktheme.com/academy/tutorial/fresh-custom-code-plugin/');
        //self::_addAcademyLesson(83, 3, 'http://arktheme.com/academy/tutorial/fresh-custom-code-plugin/');
        //self::_addAcademyLesson(84, 1, 'http://arktheme.com/academy/tutorial/fresh-favicon/');
        //self::_addAcademyLesson(85, 3, 'http://arktheme.com/academy/tutorial/fresh-performance-cache/');


        addNewId(80, 3, 'http://arktheme.com/academy/tutorial/admin-screen-templates/');
        addNewId(81, 3, 'http://arktheme.com/academy/tutorial/admin-screen-templates/');
        addNewId(82, 3, 'http://arktheme.com/academy/tutorial/fresh-custom-code-plugin/');
        addNewId(83, 3, 'http://arktheme.com/academy/tutorial/fresh-custom-code-plugin/');
        addNewId(84, 1, 'http://arktheme.com/academy/tutorial/fresh-favicon/');
        addNewId(85, 3, 'http://arktheme.com/academy/tutorial/fresh-performance-cache/');


        //console.log( getHtmlText( 83 ));
        $('.post-type-ff-template h1.wp-heading-inline').append( getHtmlText (80) );

        $('.post-type-ff-customcode-item h1.wp-heading-inline').append( getHtmlText (82) );

        $('.appearance_page_Favicon .nav-tab-wrapper').append( getHtmlText (85) );
        $('.settings_page_Minificator h2').append( getHtmlText (85) );


        /*post-type-ff-template
         Pomoci JS potom pridat videa i do:
         80,81 Ark > Templates (i do singlu)
         82,83 Appearance > Custom Code (i do singlu)
         84 Appearance > Favicon, dej to: <th scope="row">Basic FaviconSEMSEMSEMSEMSEM</th> anebo pridej velky nadpis nahore (se zeptej)
         85 Settings > Performance Cache
         */


        //
        //$('#toplevel_page_ThemeDashboard').children('ul').children('li:first').html('ss');



    });


})(jQuery);