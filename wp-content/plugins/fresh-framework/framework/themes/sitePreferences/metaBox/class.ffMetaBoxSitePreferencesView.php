<?php

class ffMetaBoxSitePreferencesView extends ffMetaBoxView {
	/**
	 * Handle ajax request - junction
	 * @param ffAjaxRequest $ajaxRequest
	 */
	public function ajaxRequest( ffAjaxRequest $ajaxRequest ) {
		$data = new ffDataHolder( $ajaxRequest->getDataStripped() );

		$postId = $data->postId;

		$toSave = array();
		$toSave['settings'] = $data->settings;

//		$toSave['random_time_1'] = time();
//		$toSave['random_things_2'] = md5(time());

//		var_dump( $data, $postId);

		$postMeta = ffContainer()->getDataStorageFactory()->createDataStorageWPPostMetas_NamespaceFacade( $postId );


		$postMetaUpdate = $postMeta->setOptionCodedJSON('ff-layout-settings', $toSave );

//		var_dump( $postMetaUpdate );
//
//		if( $postMetaUpdate == false ) {
//			for( $i= 0; $i <= 5; $i++ ) {
//				sleep(300);
//				$postMetaUpdate = $postMeta->setOptionCodedJSON('ff-layout-settings', $toSave );
//				var_dump( $postMetaUpdate );
//				if( $postMetaUpdate ) {
//					$i = 10;
//					break;
//				}
//
//			}
//		}

	}
	/**
	 * Render just the post id, the rest of the options will be loaded trough ajax call
	 * @param $post
	 */
	protected function _render( $post ) {

		echo '<div class="ff-metabox-options">';

			$fwc = ffContainer::getInstance();

			$postMeta = ffContainer()->getDataStorageFactory()->createDataStorageWPPostMetas_NamespaceFacade( $post->ID );

			$data = $postMeta->getOptionCodedJSON('ff-layout-settings');
			$structure = $fwc->getOptionsFactory()->createOptionsHolder('ffOptionsHolder_SitePreferencesMetaBox')->getOptions();

			$printer2 = ffContainer()->getOptionsFactory()->createOptionsPrinter2( $data, $structure);
			$printer2->setName('layout');
			$printer2->printOptions();
			
		echo '</div>';
	}


	protected function _requireAssets() {
		$themeBuilder = ffContainer()->getThemeFrameworkFactory()->getThemeBuilder();
		$themeBuilder->requireBuilderScriptsAndStyles();

		$scriptEnqueuer = ffContainer()->getScriptEnqueuer();

		$scriptEnqueuer->addScriptFramework('ff-metabox-sitepref', '/framework/themes/sitePreferences/metaBox/script.js');
	}

	protected function _save( $postId ) {
		$printer2 = ffContainer()->getOptionsFactory()->createOptionsPrinter2();
		$printer2->setName('layout');
		$data = $printer2->getSubmittedOptions();

//		var_dump( $_POST );
//		var_dump( $data ) ;
//		die();

		if( $data != null ) {
			$postMeta = ffContainer()->getDataStorageFactory()->createDataStorageWPPostMetas_NamespaceFacade( $postId );
			$postMeta->setOptionCodedJSON('ff-layout-settings', $data);
		}



	}
}