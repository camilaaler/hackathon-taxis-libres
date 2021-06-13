<?php
class ffAdminScreenBoxedWrappersViewDefault extends ffAdminScreenCollectionView {

	protected function _init() {
		$this->_itemName = 'boxed_wrapper';

		$this->_collectionName = 'Boxed Wrappers';
		$this->_transTitle = 'Boxed Wrappers'.ffArkAcademyHelper::getInfo(23);
		$this->_transOptionsName = 'Boxed Wrapper Settings';
		$this->_transBuilderName = 'Boxed Wrapper Builder';

		$this->_hasOptions = true;
		$this->_hasBuilder = false;
	}

	protected function _getOptionsStructure() {
		return ffContainer()->getThemeFrameworkFactory()->getThemeBuilderElementManager()->getElementById('boxed-wrapper')->getElementOptionsStructure(true);
	}

	protected function _nothingSelected() {
		?>
		<div class="ff-collection-intro-msg">
			<h3>Before you start</h3>
			<ul>
				<li>There is one pre-designed (Default) Boxed Wrapper on the left side. Click on the plus symbol at the bottom left to add your own Boxed Wrappers.</li>
				<li>If your changes are not being reflected then you are editing a Boxed Wrapper that is simply not assigned to your relevant page.</li>
				<li>Boxed Wrappers can be assigned to your pages globally via <a href="admin.php?page=ThemeOptions#GlobalLayout">Theme Options > Global Layout</a>, contextually via <a href="admin.php?page=SitePreferences">Sitemap > Your Context > Layout Settings</a> and locally in each post/page.</li>
			</ul>
			<br>
			<!-- <h3>Video Introduction to Footers</h3>
			<iframe width="560" height="315" src="https://www.youtube.com/embed/27Ua1fpAkPY" frameborder="0" allowfullscreen></iframe> -->
		</div>
		<!-- <div style="padding-top:180px;">
			<h1>Read this before editing</h1>
			<ul>
				<li>- for your comfort, we delivered 8+ predefined footers.</li>
				<li style="color:red;">- if you are editing footer and dont see any changes after save, <strong>you are editing the wrong (not assigned) footer</strong></li>
				<li>- footers are assigned globally in "Sitemap" or locally in post / page writepanels</li>
				<li>- for edit, click on the wished footer in left menu</li>
			</ul>
		</div> -->
		<?php
	}


	/**
	 * @return ffOptionsCollection
	 */
	protected function _getItemCollection() {
		$optionsCollection = ffContainer()->getDataStorageFactory()->createOptionsCollection();

		$optionsCollection->setNamespace('boxed_wrapper');
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