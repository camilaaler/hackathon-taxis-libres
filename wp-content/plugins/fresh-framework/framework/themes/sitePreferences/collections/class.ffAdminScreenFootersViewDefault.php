<?php
class ffAdminScreenFootersViewDefault extends ffAdminScreenCollectionView {

	protected function _init() {
		$this->_itemName = 'footer';

		$this->_collectionName = 'Footers';
		$this->_transTitle = 'Footers'.ffArkAcademyHelper::getInfo(25);
		$this->_transOptionsName = 'Footer Settings';
		$this->_transBuilderName = 'Footer Builder';

		$this->_hasOptions = false;
	}

	protected function _getOptionsStructure() {
		return null;
	}

	protected function _nothingSelected() {
		?>
		<div class="ff-collection-intro-msg">
			<h3>Before you start</h3>
			<ul>
				<li>There are several pre-designed (Default) Footers on the left side. Click on the plus symbol at the bottom left to add your own Footers.</li>
				<li>If your changes are not being reflected then you are editing a Footer that is simply not assigned to your relevant page.</li>
				<li>Footers can be assigned to your pages globally via <a href="admin.php?page=ThemeOptions#GlobalLayout">Theme Options > Global Layout</a>, contextually via <a href="admin.php?page=SitePreferences">Sitemap > Your Context > Layout Settings</a> and locally in each post/page.</li>
			</ul>
			<br>
			<h3>Video Introduction to Footers</h3>
			<iframe width="560" height="315" src="https://www.youtube.com/embed/27Ua1fpAkPY" frameborder="0" allowfullscreen></iframe>
		</div>
		<?php
	}


	/**
	 * @return ffOptionsCollection
	 */
	protected function _getItemCollection() {
		$optionsCollection = ffContainer()->getDataStorageFactory()->createOptionsCollection();

		$optionsCollection->setNamespace('footer');
		$optionsCollection->addDefaultItemCallbacksFromThemeFolder();

//		$optionsCollection->addDefaultItemCallback('header_default_2', function(){
//
//			$defaultItem = array();
//			$defaultItem['name'] = 'Default Header 2';
//			$defaultItem['options'] = null;
//			$defaultItem['builder'] = '[ffb_section_0][/ffb_section_0]';
//
//			return $defaultItem;
//		});

		return $optionsCollection;
	}

}