<?php

// HOW TO USE:
// echo ffArkAcademyHelper::getInfo(1);

class ffArkAcademyHelper {

	private static $_infoArray = array();

	public static function getInfo( $id ) {
		$info = self::_getInfoFromArray( $id );



		ob_start();
		if( $info == null ) {
			echo '<span style="red">ID ' . $id . ' not found </span>';
			return;
		}

		switch( $info['type'] ) {

			case 1:
				echo ' <a href="' . $info['url'] . '" class="aa-help aa-help--type-1" target="_blank" title="Watch Video Lesson"></a>';
				break;

			case 2:
				echo ' <a href="' . $info['url'] . '" class="aa-help aa-help--type-2" target="_blank" title="Watch Video Lesson"></a>';
				break;

			case 3:
				echo ' <a href="' . $info['url'] . '" class="aa-help aa-help--type-3" target="_blank" title="Watch Video Lesson"></a>';
				break;

		}

		$content = ob_get_contents();
		ob_end_clean();

		return $content;

	}


	public static function initJsPrinting() {
		add_action( 'admin_enqueue_scripts', 'ffArkAcademyHelper::actAdminEnqScripts' );
	}

	public static function actAdminEnqScripts() {
		if( class_exists('ffThemeOptions') && ffThemeOptions::getQuery('layout')->getWithoutComparationDefault('enable-academy-info', 1) && ! defined('ARK_DISABLE_ACADEMY_INFO') ) {
			ffContainer()->getStyleEnqueuer()->addStyleFrameworkAdmin( 'ff-academy-help', 'framework/core/class.ffArkAcademyHelper.css', null, null, null, null );
			wp_enqueue_script( 'ff-ark-academy-helper', ffContainer()->getInstance()->getWPLayer()->getFrameworkUrl() . 'framework/core/class.ffArkAcademyHelper.js' );
		}
	}


	/**
	 * Add all your lessons here
	 */
	private static function _initAcademyInfo() {
		self::_addAcademyLesson(1, 1, 'http://arktheme.com/academy/tutorial/backgrounds/');
		self::_addAcademyLesson(2, 1, 'http://arktheme.com/academy/tutorial/padding-and-margin/');
		self::_addAcademyLesson(3, 1, 'http://arktheme.com/academy/tutorial/padding-and-margin/');
		self::_addAcademyLesson(4, 1, 'http://arktheme.com/academy/tutorial/borders/');
		self::_addAcademyLesson(5, 1, 'http://arktheme.com/academy/tutorial/box-shadows/');
		self::_addAcademyLesson(6, 1, 'http://arktheme.com/academy/tutorial/border-radius/');
		self::_addAcademyLesson(7, 1, 'http://arktheme.com/academy/tutorial/reveal-animation/');
		self::_addAcademyLesson(8, 1, 'http://arktheme.com/academy/tutorial/dimensions/');
		self::_addAcademyLesson(9, 1, 'http://arktheme.com/academy/tutorial/vertical-centering/#165');
		self::_addAcademyLesson(10, 1, 'http://arktheme.com/academy/tutorial/visibility/');
		self::_addAcademyLesson(11, 1, 'http://arktheme.com/academy/tutorial/important-differences-between-typography-element-style-custom-code-and-custom-css-tools/');
		self::_addAcademyLesson(12, 1, 'http://arktheme.com/academy/tutorial/text-color-palette/');
		self::_addAcademyLesson(13, 1, 'http://arktheme.com/academy/tutorial/text-colors/');
		self::_addAcademyLesson(14, 1, 'http://arktheme.com/academy/tutorial/text-align/');
		self::_addAcademyLesson(15, 1, 'http://arktheme.com/academy/tutorial/font-size/');
		self::_addAcademyLesson(16, 1, 'http://arktheme.com/academy/tutorial/letter-spacing/');
		self::_addAcademyLesson(17, 1, 'http://arktheme.com/academy/tutorial/font-weight/');
		self::_addAcademyLesson(18, 1, 'http://arktheme.com/academy/tutorial/other-typography/');
		self::_addAcademyLesson(19, 1, 'http://arktheme.com/academy/tutorial/element-style/');
		self::_addAcademyLesson(20, 1, 'http://arktheme.com/academy/tutorial/custom-code/');
		self::_addAcademyLesson(21, 1, 'http://arktheme.com/academy/tutorial/global-styles/');
		//hledej: ffArkAcademyHelper::getInfo(22));        JS, tiskne se dvakrat, resp je tam asi zbytecne zduplikovany soubor 	self::_addAcademyLesson(22, 1, 'http://arktheme.com/academy/tutorial/global-color/');
		self::_addAcademyLesson(23, 2, 'http://arktheme.com/academy/tutorial/boxed-wrappers/');
		self::_addAcademyLesson(24, 3, 'http://arktheme.com/academy/tutorial/demo-install/');
		self::_addAcademyLesson(25, 2, 'http://arktheme.com/academy/tutorial/footers/');
		self::_addAcademyLesson(26, 1, 'http://arktheme.com/academy/tutorial/global-styles/');
		self::_addAcademyLesson(27, 1, 'http://arktheme.com/academy/tutorial/global-styles/');
		self::_addAcademyLesson(28, 2, 'http://arktheme.com/academy/tutorial/header-positions/');
		self::_addAcademyLesson(29, 2, 'http://arktheme.com/academy/tutorial/global-layout-sitemap-and-website-hierarchy/');
		self::_addAcademyLesson(30, 3, 'http://arktheme.com/academy/tutorial/migration/');
		self::_addAcademyLesson(31, 3, 'http://arktheme.com/academy/tutorial/sidebar/');
		self::_addAcademyLesson(32, 2, 'http://arktheme.com/academy/tutorial/global-layout-sitemap-and-website-hierarchy/');
		self::_addAcademyLesson(33, 2, 'http://arktheme.com/academy/tutorial/admin-screen-theme-options/');
		self::_addAcademyLesson(34, 2, 'http://arktheme.com/academy/tutorial/titlebars/');
		self::_addAcademyLesson(35, 1, 'http://arktheme.com/academy/tutorial/transparent-horizontal-header-and-whitespace-compensation/#55');
		self::_addAcademyLesson(36, 1, 'http://arktheme.com/academy/tutorial/transparent-horizontal-header-and-whitespace-compensation/#55');
		self::_addAcademyLesson(37, 1, 'http://arktheme.com/academy/tutorial/transparent-horizontal-header-and-whitespace-compensation/#124');
		self::_addAcademyLesson(38, 1, 'http://arktheme.com/academy/tutorial/transparent-horizontal-header-and-whitespace-compensation/#124');
		self::_addAcademyLesson(39, 1, 'http://arktheme.com/academy/tutorial/transparent-horizontal-header-and-whitespace-compensation/#15');
		self::_addAcademyLesson(40, 1, 'http://arktheme.com/academy/tutorial/topbar-builder/');
		self::_addAcademyLesson(41, 1, 'http://arktheme.com/academy/tutorial/header-positions/');
		self::_addAcademyLesson(42, 1, 'http://arktheme.com/academy/tutorial/header-width/');
		self::_addAcademyLesson(43, 1, 'http://arktheme.com/academy/tutorial/header-logos/');
		self::_addAcademyLesson(44, 1, 'http://arktheme.com/academy/tutorial/navigation-menu/');
		self::_addAcademyLesson(45, 1, 'http://arktheme.com/academy/tutorial/mega-menu/');
		self::_addAcademyLesson(46, 1, 'http://arktheme.com/academy/tutorial/custom-content-in-headers/');
		self::_addAcademyLesson(47, 1, 'http://arktheme.com/academy/tutorial/custom-content-in-headers/');
		self::_addAcademyLesson(48, 1, 'http://arktheme.com/academy/tutorial/custom-content-in-headers/');
		self::_addAcademyLesson(49, 1, 'http://arktheme.com/academy/tutorial/custom-content-in-headers/');
		self::_addAcademyLesson(50, 1, 'http://arktheme.com/academy/tutorial/search-bar-variants-in-horizontal-header/');
		self::_addAcademyLesson(51, 1, 'http://arktheme.com/academy/tutorial/header-side-menu-in-horizontal-headers/');
		self::_addAcademyLesson(52, 1, 'http://arktheme.com/academy/tutorial/horizontal-headers/');
		self::_addAcademyLesson(53, 1, 'http://arktheme.com/academy/tutorial/vertical-headers/');
		self::_addAcademyLesson(54, 1, 'http://arktheme.com/academy/tutorial/header-colors-customisation/');
		self::_addAcademyLesson(55, 1, 'http://arktheme.com/academy/tutorial/header-colors-customisation/#168');
		self::_addAcademyLesson(56, 1, 'http://arktheme.com/academy/tutorial/create-one-page-and-one-page-menu/#130');
		self::_addAcademyLesson(57, 1, 'http://arktheme.com/academy/tutorial/building-basic-contact-form/');
		self::_addAcademyLesson(58, 1, 'http://arktheme.com/academy/tutorial/building-basic-contact-form/');
		self::_addAcademyLesson(59, 1, 'http://arktheme.com/academy/tutorial/building-basic-contact-form/');
		self::_addAcademyLesson(60, 1, 'http://arktheme.com/academy/tutorial/building-basic-contact-form/');
		self::_addAcademyLesson(61, 1, 'http://arktheme.com/academy/tutorial/building-basic-contact-form/');
		self::_addAcademyLesson(62, 1, 'http://arktheme.com/academy/tutorial/build-submittable-form-with-custom-php-handler-code/');
		self::_addAcademyLesson(63, 1, 'http://arktheme.com/academy/tutorial/advanced-contact-form/#145');
		self::_addAcademyLesson(64, 1, 'http://arktheme.com/academy/tutorial/advanced-contact-form/#500');
		self::_addAcademyLesson(65, 1, 'http://arktheme.com/academy/tutorial/add-php-code/#150');
		self::_addAcademyLesson(66, 1, 'http://arktheme.com/academy/tutorial/add-custom-font/');
		self::_addAcademyLesson(67, 1, 'http://arktheme.com/academy/tutorial/add-custom-icon-font/');
		self::_addAcademyLesson(68, 1, 'http://arktheme.com/academy/tutorial/javascript-code/');
		self::_addAcademyLesson(69, 1, 'http://arktheme.com/academy/tutorial/how-to-get-google-maps-api-key/');
		self::_addAcademyLesson(70, 1, 'http://arktheme.com/academy/tutorial/vertical-centering/');
		self::_addAcademyLesson(71, 1, 'http://arktheme.com/academy/tutorial/vertical-centering/');
		self::_addAcademyLesson(72, 1, 'http://arktheme.com/academy/tutorial/equalize-height/');
		self::_addAcademyLesson(73, 1, 'http://arktheme.com/academy/tutorial/equalize-height/');
		self::_addAcademyLesson(74, 1, 'http://arktheme.com/academy/tutorial/bootstrap-grid-basics/');
		self::_addAcademyLesson(75, 1, 'http://arktheme.com/academy/tutorial/equalize-height/');
		self::_addAcademyLesson(76, 1, 'http://arktheme.com/academy/tutorial/equalize-height/');
		self::_addAcademyLesson(77, 1, 'http://arktheme.com/academy/tutorial/advanced-bootstrap-grid-section-container-row-column/');
		self::_addAcademyLesson(78, 1, 'http://arktheme.com/academy/tutorial/advanced-bootstrap-grid-section-container-row-column/');
		self::_addAcademyLesson(79, 1, 'http://arktheme.com/academy/tutorial/advanced-bootstrap-grid-section-container-row-column/');
	
		self::_addAcademyLesson(80, 1, 'http://arktheme.com/academy/tutorial/wrapper/');
		self::_addAcademyLesson(81, 1, 'http://arktheme.com/academy/tutorial/shortcode-wrapper/');
		self::_addAcademyLesson(82, 1, 'http://arktheme.com/academy/tutorial/icons/');
		self::_addAcademyLesson(83, 1, 'http://arktheme.com/academy/tutorial/buttons/');
		self::_addAcademyLesson(84, 1, 'http://arktheme.com/academy/tutorial/map/');
		self::_addAcademyLesson(85, 3, 'http://arktheme.com/academy/tutorial/how-to-get-google-maps-api-key/');

		self::_addAcademyLesson(86, 1, 'http://arktheme.com/academy/tutorial/bootstrap-grid-advanced-is-last-column/');
		self::_addAcademyLesson(87, 1, 'http://arktheme.com/academy/tutorial/bootstrap-grid-advanced-offsetting/');
		self::_addAcademyLesson(88, 1, 'http://arktheme.com/academy/tutorial/link-options/');
		self::_addAcademyLesson(89, 1, 'http://arktheme.com/academy/tutorial/image-settings/');
		self::_addAcademyLesson(90, 1, 'http://arktheme.com/academy/tutorial/image-settings/');
		self::_addAcademyLesson(91, 3, 'http://arktheme.com/academy/tutorial/keyboard-shortcuts/');

		self::_addAcademyLesson(92, 1, 'http://arktheme.com/academy/tutorial/icon-library/');
		self::_addAcademyLesson(93, 1, 'http://arktheme.com/academy/tutorial/section-library/');
		self::_addAcademyLesson(94, 1, 'http://arktheme.com/academy/tutorial/element-library/');
		self::_addAcademyLesson(99, 1, 'http://arktheme.com/academy/tutorial/slider-custom/');
		self::_addAcademyLesson(100, 1, 'http://arktheme.com/academy/tutorial/carousel-custom/');
		self::_addAcademyLesson(101, 1, 'http://arktheme.com/academy/tutorial/carousel-custom/');
		self::_addAcademyLesson(102, 1, 'http://arktheme.com/academy/tutorial/sidebar/');
		self::_addAcademyLesson(103, 1, 'http://arktheme.com/academy/tutorial/list/');
		self::_addAcademyLesson(104, 1, 'http://arktheme.com/academy/tutorial/image-slider/');
		self::_addAcademyLesson(105, 1, 'http://arktheme.com/academy/tutorial/html-php-code/');
		self::_addAcademyLesson(106, 1, 'http://arktheme.com/academy/tutorial/empty-space/');
		self::_addAcademyLesson(107, 1, 'http://arktheme.com/academy/tutorial/if/');
		self::_addAcademyLesson(108, 1, 'http://arktheme.com/academy/tutorial/embed-code/');
		self::_addAcademyLesson(109, 1, 'http://arktheme.com/academy/tutorial/map/#138');
		self::_addAcademyLesson(110, 1, 'http://arktheme.com/academy/tutorial/map/#249');
		self::_addAcademyLesson(111, 1, 'http://arktheme.com/academy/tutorial/map/#303');
		self::_addAcademyLesson(112, 1, 'http://arktheme.com/academy/tutorial/map/#280');
		self::_addAcademyLesson(113, 1, 'http://arktheme.com/academy/tutorial/map/#370');
		self::_addAcademyLesson(114, 1, 'http://arktheme.com/academy/tutorial/breadcrumbs/');
		self::_addAcademyLesson(115, 1, 'http://arktheme.com/academy/tutorial/breadcrumbs/');
		self::_addAcademyLesson(116, 1, 'http://arktheme.com/academy/tutorial/breadcrumbs/');
		self::_addAcademyLesson(117, 1, 'http://arktheme.com/academy/tutorial/breadcrumbs/');
		self::_addAcademyLesson(118, 1, 'http://arktheme.com/academy/tutorial/breadcrumbs/');
		self::_addAcademyLesson(119, 1, 'http://arktheme.com/academy/tutorial/breadcrumbs/');
		self::_addAcademyLesson(120, 1, 'http://arktheme.com/academy/tutorial/breadcrumbs/');
		self::_addAcademyLesson(121, 1, 'http://arktheme.com/academy/tutorial/loop/');
		self::_addAcademyLesson(122, 1, 'http://arktheme.com/academy/tutorial/loop-post/');
		self::_addAcademyLesson(123, 1, 'http://arktheme.com/academy/tutorial/single-post-wrapper/');
		self::_addAcademyLesson(124, 1, '');
		self::_addAcademyLesson(125, 1, '');
		self::_addAcademyLesson(126, 1, '');
		self::_addAcademyLesson(127, 1, '');
		self::_addAcademyLesson(128, 1, '');
		self::_addAcademyLesson(129, 1, '');
		self::_addAcademyLesson(130, 1, '');
		self::_addAcademyLesson(131, 1, '');
		self::_addAcademyLesson(132, 1, '');
		self::_addAcademyLesson(133, 1, '');
		self::_addAcademyLesson(134, 1, '');
		self::_addAcademyLesson(135, 1, '');
		self::_addAcademyLesson(136, 1, '');
		self::_addAcademyLesson(137, 1, '');
		self::_addAcademyLesson(138, 1, '');
		self::_addAcademyLesson(139, 1, '');
		self::_addAcademyLesson(140, 1, '');
		self::_addAcademyLesson(141, 1, '');
		self::_addAcademyLesson(142, 1, '');


		
		/*
		Pomoci JS potom pridat videa i do:
	
			92 icon library k nadpisu   <a href="http://arktheme.com/academy/tutorial/icon-library/" class="aa-help aa-help--type-1" target="_blank" title="Watch Video Lesson"></a>
		done	93 do tabu (zeptat)  <a href="http://arktheme.com/academy/tutorial/section-library/" class="aa-help aa-help--type-1" target="_blank" title="Watch Video Lesson"></a>
		done	94 do tabu (zeptat) <a href="http://arktheme.com/academy/tutorial/element-library/" class="aa-help aa-help--type-1" target="_blank" title="Watch Video Lesson"></a>


		*/

	}



	private static function _getInfoFromArray( $id ) {
		if( empty( self::$_infoArray ) ) {
			self::_initAcademyInfo();
		}

		if( isset( self::$_infoArray[ $id ] ) ) {
			return self::$_infoArray[ $id ];
		} else {
			return null;
		}
	}



	private static function _addAcademyLesson( $id, $type, $url, $description = null ) {
		$lesson = [];

		$lesson[ 'id' ] = $id;
		$lesson[ 'type' ] = $type;
		$lesson[ 'url' ] = $url;
		$lesson[ 'description' ] = $description;

		self::$_infoArray[ $id ] = $lesson;
	}



}

if( is_admin() ) {
	ffArkAcademyHelper::initJsPrinting();
}
