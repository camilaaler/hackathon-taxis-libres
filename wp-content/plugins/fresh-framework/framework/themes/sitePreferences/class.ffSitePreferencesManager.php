<?php

class ffSitePreferencesManager extends ffBasicObject {
	/**********************************************************************************************************************/
	/* OBJECTS
	/**********************************************************************************************************************/


	/**********************************************************************************************************************/
	/* PRIVATE VARIABLES
	/**********************************************************************************************************************/


	/**********************************************************************************************************************/
	/* CONSTRUCT
	/**********************************************************************************************************************/
	public function __construct() {

	}

	/**********************************************************************************************************************/
	/* PUBLIC FUNCTIONS
	/**********************************************************************************************************************/
	public function enableSitePreferences() {
		ffContainer()->getAdminScreenManager()
			->addAdminScreenClassName('ffAdminScreenSitePreferences')
			->addAdminScreenClassName('ffAdminScreenHeaders')
			->addAdminScreenClassName('ffAdminScreenBoxedWrappers')
			->addAdminScreenClassName('ffAdminScreenTitlebars')
			->addAdminScreenClassName('ffAdminScreenFooters')
			->addAdminScreenClassName('ffAdminScreenLayouts')
		;

		ffContainer()->getMetaBoxes()
			->getMetaBoxManager()
			->addMetaBoxClassName('ffMetaBoxSitePreferences');



//	    ffContainer()->getWPLayer()->add_action('template_redirect', array( $this, 'actTemplateRedirect'), 10);
//		global $wp_query;
//		var_dump( $wp_query);
//		die();

		if( class_exists('WooCommerce') ) {
			ffContainer()->getWPLayer()->add_action('template_include', array( $this, 'actTemplateInclude'), 999);
		} else {
			ffContainer()->getWPLayer()->add_action('template_include', array( $this, 'actTemplateInclude'), 10);
//			ffContainer()->getWPLayer()->add_action('bp_get_root_template', array( $this, 'actTemplateInclude'), 10);


//			ffContainer()->getWPLayer()->add_action('template_include', array( $this, 'actTemplateInclude'), 20);
		}

	}

	public function getHeadersCollection() {
		return new ffItemPartCollection_Header();
	}

	public function actTemplateInclude( $template ) {

//		return $template;

		$WPLayer = ffContainer()->getWPLayer();
		if( $WPLayer->is_feed() ) {
			return $template;
		}

		$vdm = ffContainer()->getThemeFrameworkFactory()->getSitePreferencesFactory()->getViewDataManager();
		$currentLayout = $vdm->getCurrentLayoutInfo();

		if( $currentLayout == null ) {
			return $template;
		}

		if( $currentLayout->get('layout', null) == null ) {
			return $template;
		}

		$newTemplate = $WPLayer->getFrameworkDir().'/framework/themes/sitePreferences/actTemplateInclude.php';


		if( file_exists( $newTemplate ) ) {
//			var_dump( $newTemplate );
//			die();
			return $newTemplate;
		} else {
			$this->actTemplateRedirectForce();
		}
	}

	public function actTemplateRedirectForce() {
		$this->actTemplateRedirect();
		die();
	}

	public function actTemplateRedirect() {

		$WPLayer = ffContainer()->getWPLayer();

		if( $WPLayer->is_feed() ) {
			return;
		}

		$vdm = ffContainer()->getThemeFrameworkFactory()->getSitePreferencesFactory()->getViewDataManager();
		$currentLayout = $vdm->getCurrentLayoutInfo();


		if( $currentLayout == null ) {
			return null;
		}

		$WPLayer->add_action('wp_footer', array($this, 'actWpFooter'));
		$WPLayer->add_action('wp_head', array($this, 'actWpHead'));

		if( $currentLayout->layout == null ) {
			return;
		}

		get_header();

		$builderManager = ffContainer()->getThemeFrameworkFactory()->getThemeBuilderManager();
		$builderManager->render( $currentLayout->layoutContent );
		$builderManager->addRenderdCssToStack();
		$builderManager->addRenderedJsToStack();

		get_footer();

//		die();
	}

//	private function _doPluginsCompatibilityWithTemplateRedirect

	private function _getRenderedCssAndJs() {
		$builderManager = ffContainer()->getThemeFrameworkFactory()->getThemeBuilderManager();
		$renderedJS = $builderManager->getRenderedJsStack();
		$renderedCss = $builderManager->getRenderedCssStack();

		$toReturn = '';
		if( !empty( $renderedJS )  ) {
			$toReturn .= '<script>' . $builderManager->getRenderedJsStack() . '</script>';
		}

		if( !empty( $renderedCss ) ) {
			$css = $builderManager->getRenderedCssStack();
			$css = str_replace("\n", '', $css );
			$toReturn .= '<style>' . $css .'</style>';
		}

		return $toReturn;
	}

	public function actWpHead() {
//		echo '<ffb-replace-with-style>';
	}

	public function actWpFooter() {
		return;
		$builderManager = ffContainer()->getThemeFrameworkFactory()->getThemeBuilderManager();
		$renderedJS = $builderManager->getRenderedJsStack();
		$renderedCss = $builderManager->getRenderedCssStack();

		if( !empty( $renderedJS )  ) {
			echo '<script>' . $builderManager->getRenderedJsStack() . '</script>';
		}

		if( !empty( $renderedCss ) ) {
			$css = $builderManager->getRenderedCssStack();
			$css = str_replace("\n", '', $css );
			echo '<style>' . $css .'</style>';
		}
	}
	/**********************************************************************************************************************/
	/* PUBLIC PROPERTIES
	/**********************************************************************************************************************/


	/**********************************************************************************************************************/
	/* PRIVATE FUNCTIONS
	/**********************************************************************************************************************/


	/**********************************************************************************************************************/
	/* ABSTRACT FUNCTIONS
	/**********************************************************************************************************************/


	/**********************************************************************************************************************/
	/* PRIVATE GETTERS & SETTERS
	/**********************************************************************************************************************/
}