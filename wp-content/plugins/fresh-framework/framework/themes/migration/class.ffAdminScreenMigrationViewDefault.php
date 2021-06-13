<?php
class ffAdminScreenMigrationViewDefault extends ffAdminScreenView {


	public function ajaxRequest( ffAdminScreenAjax $ajax ) {

//		var_dump( $ajax );
	}



	public function newAjaxRequest( ffAjaxRequest $ajaxRequest ) {

	}

	protected function _actionReplaceImages() {

		echo '<h1>Migrating images</h1>';

        $somethingReplaced = false;

		$posts = ffContainer()->getPostLayer()->getPostGetter()
			->setNumberOfPosts(-1)
//			->setOffset(45)
			->getPostsByType('any');

		$tbr = ffContainer()->getThemeFrameworkFactory()->getThemeBuilderRegexp();
//
		foreach( $posts as $onePost ) {
			$postContent = $onePost->getContent();

			$newContent = $tbr->imageFind( $postContent, array($this,'imageFound'));

			if( $tbr->getFoundImageInPost() ) {

				echo 'Replaced images here: ' . get_post_permalink($onePost->getID()) . '<br>';
                $somethingReplaced = true;

				$postUpdater = ffContainer()->getPostLayer()->getPostUpdater();
				$data = array();
				$data['ID'] = $onePost->getID();
				$data['post_content'] = $newContent;

				$postUpdater->updatePost($data);
				echo '<br><br><br>';
			}
		}


		$optionsCollection = ffContainer()->getDataStorageFactory()->createOptionsCollection();

		$optionsCollection->setNamespace('footer');
		$optionsCollection->addDefaultItemCallbacksFromThemeFolder();

		foreach( $optionsCollection as $oneItem ) {

			$postContent = $oneItem->get('builder');

			$newContent = $tbr->imageFind( $postContent, array($this,'imageFound'));

			if( $tbr->getFoundImageInPost() ) {
				echo 'Replaced images in footer ' . $oneItem->get('name');
                $somethingReplaced = true;
				$oneItem->set('builder', $newContent);
			}

		}

		$optionsCollection->save();

        $optionsCollection = ffContainer()->getDataStorageFactory()->createOptionsCollection();

        $optionsCollection->setNamespace('titlebar');
        $optionsCollection->addDefaultItemCallbacksFromThemeFolder();

        foreach( $optionsCollection as $oneItem ) {

            $postContent = $oneItem->get('builder');

            $newContent = $tbr->imageFind( $postContent, array($this,'imageFound'));

            if( $tbr->getFoundImageInPost() ) {
                echo 'Replaced images in titlebar ' . $oneItem->get('name');
                $somethingReplaced = true;
                $oneItem->set('builder', $newContent);
            }

        }

        $optionsCollection->save();

        if( $somethingReplaced == false ) {
            echo 'Nothing found';
        }

	}

	private $_replacedImages = array();

	public function imageFound( $image ) {




		$imageData = ffDataHolder( $image );

		$imageNewUrl = wp_get_attachment_url( $imageData->id );

		if( $imageData->url != $imageNewUrl ) {
			echo 'Image Replaced' . '<br>';
			echo 'Old URL : ' . $imageData->url . '<br>';
			echo 'New URL : ' .$imageNewUrl . '<br>';
			$image['url'] = $imageNewUrl;

			$imageData->new_url = $imageNewUrl;
			$this->_replacedImages[] = $imageData;

			return $image;
		}

		return null;
	}

	private function _actionReplaceUrl() {
        $somethingReplaced = false;

		echo '<h1>Replacing STRING</h1>';

		$reqeust = ffContainer()->getRequest();

		$oldUrl = $reqeust->post('ff-old-url');
		$newUrl = $reqeust->post('ff-new-url');

		$oldUrlEncoded = rawurlencode( $oldUrl );
		$newUrlEncoded = rawurlencode( $newUrl );

		$posts = ffContainer()->getPostLayer()->getPostGetter()
			->setNumberOfPosts(-1)
//			->setOffset(45)
			->getPostsByType('any');
//
//		$tbr = ffContainer()->getThemeFrameworkFactory()->getThemeBuilderRegexp();
////
		foreach( $posts as $onePost ) {
			$postContent = $onePost->getContent();

			$count = 0;
			$newContent = str_replace( $oldUrlEncoded, $newUrlEncoded, $postContent, $count);

			if( $count > 0 ) {
				echo 'Replaced string here: ' . get_post_permalink($onePost->getID()) . '<br>';
                $somethingReplaced = true;

				$postUpdater = ffContainer()->getPostLayer()->getPostUpdater();
				$data = array();
				$data['ID'] = $onePost->getID();
				$data['post_content'] = $newContent;
//
				$postUpdater->updatePost($data);
				echo '<br><br><br>';
			}

		}

		$optionsCollection = ffContainer()->getDataStorageFactory()->createOptionsCollection();

		$optionsCollection->setNamespace('footer');
		$optionsCollection->addDefaultItemCallbacksFromThemeFolder();

		foreach( $optionsCollection as $oneItem ) {

			$postContent = $oneItem->get('builder');

			$count = 0;
			$newContent = str_replace( $oldUrlEncoded, $newUrlEncoded, $postContent, $count);

			if( $count > 0 ) {
				echo 'Replaced images in footer ' . $oneItem->get('name');
                $somethingReplaced = true;
				$oneItem->set('builder', $newContent);
			}

		}

		$optionsCollection->save();

        $optionsCollection = ffContainer()->getDataStorageFactory()->createOptionsCollection();

        $optionsCollection->setNamespace('titlebar');
        $optionsCollection->addDefaultItemCallbacksFromThemeFolder();

        foreach( $optionsCollection as $oneItem ) {

            $postContent = $oneItem->get('builder');

            $count = 0;
            $newContent = str_replace( $oldUrlEncoded, $newUrlEncoded, $postContent, $count);

            if( $count > 0 ) {
                echo 'Replaced images in titlebar ' . $oneItem->get('name');
                $somethingReplaced = true;
                $oneItem->set('builder', $newContent);
            }

        }

        $optionsCollection->save();

        if( $somethingReplaced == false ) {
            echo 'Nothing found';
        }
	}


	protected function _render() {

		$request = ffContainer()->getRequest();

		echo '<div class="wrap about-wrap">';
		echo '<h1>Migration';
		echo ffArkAcademyHelper::getInfo(30);
		echo '</h1>';
		echo '<h3>Before using the migration tools</h3>';
		echo '<p class="about-text">After you have migrated your site from staging environment to your production server you will find out that your database contains hardcoded URL references to the old domain that need to be replaced. These migration tools will replace most of these references for you. They will run a database query against the Ark data in database and replace any old URLs with new URLs.</p>';
		echo '<p class="about-text"><strong style="color:red;">Use these tools only after you have successfully migrated the website files and database to the new server first. Use these tools only on the new server, not the old one. But before you do, backup all files and database and double-check that this backup can indeed be used as a backup and is not corrupted somehow.</strong></p>';
		echo '<p class="about-text">If you do not know what this tool does then you do not need it, so please do not use it because you could break your database. You are also free to replace the old URL references on your own, manually in the database, instead of using the migration tools below.</p>';
		echo '<h3>After using the migration tools</h3>';
		echo '<p class="about-text">These migration tools are unable to replace 100% of all URL references so you might still need to replace some of them by hand in wp-admin, such as re-selecting header logo images for example. So as a last thing after using these tools, please carefully inspect your site for any remaining URL references that still need to be replaced by hand in wp-admin.</p>';
		switch( $request->get('action', 'default') ) {
			case 'replace-images':
				$this->_actionReplaceImages();
				break;

			case 'replace-url':
				$this->_actionReplaceUrl();
				break;
		}

		$urlRewriter = ffContainer()->getUrlRewriter();


			// echo '<h2>Replace Images</h2>';

			echo '
			<h2 class="nav-tab-wrapper wp-clearfix">
				<a href="./admin.php?page=Migration" class="nav-tab nav-tab-active">Migration Tool - Replace Images</a>
			</h2>
			';
			echo '<p>This tool finds every image and replaces it\'s old URL with new URL (when doing "HTTP -> HTTPS" switch for example, or a large server migration). Use this tool only on the new server, not the old one.</p>';
			echo '<p><strong style="color:red;">BACKUP YOUR DATABASE AND FILES FIRST!</strong></p>';
			echo '<p><a class="button button-primary" href="'. $urlRewriter->addQueryParameter('action', 'replace-images')->getNewUrl().'">Replace Images</a></p>';

			echo '<br>';
			// echo '<h2>Replace String</h2>';
			echo '
			<h2 class="nav-tab-wrapper wp-clearfix">
				<a href="./admin.php?page=Migration" class="nav-tab nav-tab-active">Migration Tool - Replace URL String</a>
			</h2>
			';
			echo '<p>This tool can be used to replace old URL strings with new URLs strings. Use this tool only on the new server, not the old one.</p>';
			echo '<form action="'. $urlRewriter->addQueryParameter('action', 'replace-url')->getNewUrl().'" method="POST">';			
				echo '<p class="description">Example 1: Find <code>http://devserver.com</code> and replace it with <code>https://www.finalserver.com</code></p>';
				echo '<p class="description">Example 2: Find <code>www.devserver.com</code> and replace it with <code>finalserver.com</code></p>';
				echo '<p><input type="text" name="ff-old-url" class="ff-old-url"> - Old string (Find)</p>';
//				echo '';
				echo '<p><input type="text" name="ff-new-url" class="ff-new-url"> - New string (Replace with)</p>';
//				echo '';
//				echo '<br>';
				echo '<p><strong style="color:red;">BACKUP YOUR DATABASE AND FILES FIRST!</strong></p>';
				echo '<input type="submit" class="button button-primary ff-replace-url-button" value="Replace URL String">';
			echo '</form>';
//			echo '<p><a class="button" href="'. $urlRewriter->addQueryParameter('action', 'replace')->getNewUrl().'">Replace URL</a> - the actual replacing process</p>';
//		echo '<h1>Migration</h1>';
//			echo '<'
		echo '</div>';

		// search for image strings across website


	}



	protected function _requireAssets() {
		$themeBuilder = ffContainer()->getThemeFrameworkFactory()->getThemeBuilder();

		$themeBuilder->requireBuilderScriptsAndStyles();

//		$pluginUrl = ffPluginFreshMinificatorContainer::getInstance()->getPluginUrl();
//		$this->_getScriptEnqueuer()->addScript('ffAdminScreenMinificatorViewDefault', $pluginUrl .'/adminScreens/minificator/assets/adminScreen.js', array('jquery') );
//		$this->_getStyleEnqueuer()
//			->addStyleFramework('ff-dummy-content', '/framework/themes/dummy/adminScreen/style.css');

		$this->_getScriptEnqueuer()
			->addScriptFramework('ff-migration', '/framework/themes/migration/script.js');
	}


	protected function _setDependencies() {

	}

}