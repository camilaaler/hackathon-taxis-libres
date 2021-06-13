<?php

class ffMetaBoxSitePreferences extends ffMetaBox {
	protected function _initMetaBox() {
		$supportedPostTypes = ffContainer()->getThemeFrameworkFactory()->getThemeBuilderManager()->getSupportedPostTypes();
//		var_dump( $supportedPostTypes );
//		die();

		$this->_addPostType( 'post' );
		$this->_addPostType( 'page' );
		$this->_addPostType( 'portfolio' );
//		$this->_addPostType( ffTheme::CONTENT_BLOCK_ADMIN_POST_TYPE_SLUG );
//		$this->_addPostType( 'portfolio' );
//		$this->_addPostType( ffTheme::TEMPLATE_POST_TYPE_SLUG );
//
		if( !empty( $supportedPostTypes ) ) {
			foreach( $supportedPostTypes as $onePostType ) {
				$this->_addPostType( $onePostType );
			}
		}
//
		$this->_setTitle('Post/Page Settings');
		$this->_setContext( ffMetaBox::CONTEXT_NORMAL);
		$this->_setPriority( ffMetaBox::PRIORITY_HIGH);

//		var_dump('SITE PREFERENCES');
//		die();
//
//		$this->_setParam( ffMetaBox::PARAM_NORMALIZE_OPTIONS, true);
//        $this->_setParam( ffMetaBox::PARAM_NORMALIZE_OPTIONS_TO_ONE_INPUT, true );
//		$this->_addVisibility( ffMetaBox::VISIBILITY_PAGE_TEMPLATE, 'page-onepage.php');
	}
}