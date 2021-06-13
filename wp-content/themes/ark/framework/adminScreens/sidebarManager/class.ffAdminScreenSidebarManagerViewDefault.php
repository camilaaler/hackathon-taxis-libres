<?php

class ffAdminScreenSidebarManagerViewDefault extends ffAdminScreenView {

	public function actionSave( ffRequest $request ) {

		if( ! $request->postEmpty() ){
			flush_rewrite_rules( false );
		}

	}
	
	protected function _render() {

		ffContainer::getInstance()->getModalWindowFactory()->printModalWindowSectionPicker();

		echo '<div class="wrap about-wrap">';
		echo '<form method="post">';

		echo '<h1>'. ark_wp_kses( __( 'Sidebars', 'ark' ) );
		echo ffArkAcademyHelper::getInfo(31);
		echo '</h1>';
		echo '<br/><br/>';

		$this->_renderOptions(
			  ffThemeContainer::SIDEBARS_HOLDER
			, ffThemeContainer::SIDEBARS_PREFIX
			, ffThemeContainer::SIDEBARS_NAMESPACE
			, ffThemeContainer::SIDEBARS_NAME
		);

		echo '</form>';
		echo '</div>';
	}

	protected function _requireAssets() {
	}

	protected function _setDependencies() {

	}

	public function ajaxRequest( ffAdminScreenAjax $ajax ) {

	}
}