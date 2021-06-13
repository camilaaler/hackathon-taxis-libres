<?php
class ffAdminScreenHeadersViewDefault extends ffAdminScreenCollectionView {

	protected function _init() {
		$this->_itemName = 'header';

		$this->_collectionName = 'Headers';
		$this->_transTitle = 'Headers'.ffArkAcademyHelper::getInfo(28);
		$this->_transOptionsName = 'Header Settings';
		$this->_transBuilderName = 'Topbar Builder';
	}

	protected function _getOptionsStructure() {
		return ffContainer()->getThemeFrameworkFactory()->getThemeBuilderElementManager()->getElementById('header')->getElementOptionsStructure(true);
	}

	protected function _nothingSelected() {
		?><div class="ff-collection-intro-msg">
			<h3>Before you start</h3>
			<ul>
				<li>There are several pre-designed (Default) Headers on the left side. Click on the plus symbol at the bottom left to add your own Headers.</li>
				<li>The most popular Default Headers are: "Default Classic - Transparent Top" and "Default Header".</li>
				<li>If your changes are not being reflected then you are editing a Header that is simply not assigned to your relevant page.</li>
				<li>Headers can be assigned to your pages globally via <a href="admin.php?page=ThemeOptions#GlobalLayout">Theme Options > Global Layout</a>, contextually via <a href="admin.php?page=SitePreferences">Sitemap > Your Context > Layout Settings</a> and locally in each post/page.</li>
			</ul>
			<br>
			<h3>Video Introduction to Headers</h3>
			<iframe width="560" height="315" src="https://www.youtube.com/embed/XxWnihgnMU4" frameborder="0" allowfullscreen></iframe>
		</div>
		<!-- div style="padding-top:100px;">
			<h1>Read this before editing</h1>
			<ul>
				<li>- for your comfort, we delivered 15+ predefined headers.</li>
				<li>- most used headers across sites are "Default Classic - Transparent Top" and "Default Header"</li>
				<li style="color:red;">- if you are editing header and dont see any changes after save, <strong>you are editing the wrong (not assigned) header</strong></li>
				<li>- headers are assigned globally in "Sitemap" or locally in post / page writepanels</li>
				<li>- for edit, click on the wished header in left menu</li>
			</ul>
			<br><br>
			<h1>Quick tutorial</h1>
			<iframe width="560" height="315" src="https://www.youtube.com/embed/XxWnihgnMU4" frameborder="0" allowfullscreen></iframe>


		</div> -->
		<?php
	}

	/**
	 * @return ffOptionsCollection
	 */
	protected function _getItemCollection() {
		$optionsCollection = ffContainer()->getDataStorageFactory()->createOptionsCollection();

		$optionsCollection->setNamespace('header');
		$optionsCollection->addDefaultItemCallbacksFromThemeFolder();

		return $optionsCollection;
		 
	}

}