<?php
class ffAdminScreenLayoutsViewDefault extends ffAdminScreenCollectionView {

	protected function _init() {
		$this->_itemName = 'templates';

		$this->_collectionName = 'Layouts';
		$this->_transTitle = 'Layouts'.ffArkAcademyHelper::getInfo(29);
		$this->_transOptionsName = 'Layout Settings';
		$this->_transBuilderName = 'Layout Builder';


//		$this->_hasOptions = false;
	}

	protected function _getOptionsStructure() {
		$fwc = ffContainer();
		return $fwc->getOptionsFactory()->createOptionsHolder('ffOptionsHolder_SitePreferences')->getOptions();;
	}


	protected function _nothingSelected() {
		?>
		<div class="ff-collection-intro-msg">
			<h3>Before you start</h3>
			<ul>
				<li>A Layout is the foundation of each page. It prints Boxed Wrapper, Header, Titlebar, Content and Footer. You can pick these via "Layout Settings" tab in each Layout.</li>
				<li> You can build custom wrappers around the actual content of your posts and pages via "Layout Builder" tab in each Layout.</li>
				<li>Most of the Layouts use "Post Content" Fresh Builder element to print the actual content of your posts and pages. This element is not necessary for the Achive type of pages.</li>
				<li>There are several pre-designed (Default) Layouts on the left side. Click on the plus symbol at the bottom left to add your own Layouts.</li>
				<li>If your changes are not being reflected then you are editing a Layout that is simply not assigned to your relevant page.</li>
				<li>Layouts can be assigned to your pages contextually via <a href="admin.php?page=SitePreferences">Sitemap > View > Currently active Layout</a> and locally in each post/page.</li>
			</ul>
			<br>
			<h3>Video Introduction to Sitemap (and Layouts)</h3>
			<iframe width="560" height="315" src="https://www.youtube.com/embed/pv-ZjmmOEcw" frameborder="0" allowfullscreen></iframe>
		</div>

		<?php
		return;

	}


	/**
	 * @return ffOptionsCollection
	 */
	protected function _getItemCollection() {
		$optionsCollection = ffContainer()->getDataStorageFactory()->createOptionsCollection();

		$optionsCollection->setNamespace('templates');
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