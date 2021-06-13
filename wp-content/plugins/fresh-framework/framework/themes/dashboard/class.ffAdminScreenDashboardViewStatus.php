<?php
class ffAdminScreenDashboardViewStatus extends ffAdminScreenView {


	public function ajaxRequest( ffAdminScreenAjax $ajax ) {
	}



	public function newAjaxRequest( ffAjaxRequest $ajaxRequest ) {
		if( $ajaxRequest->getData('action') == 'verify' ) {
			$this->_ajaxActionVerify( $ajaxRequest );
		}

		else if( $ajaxRequest->getData('action') == 'register' ) {
			$this->_ajaxActionRegister( $ajaxRequest );
		}

	}

	protected function _ajaxActionRegister( $ajaxRequest ) {
		$licenseKey = $ajaxRequest->getData('licenseKey');
		$email = $ajaxRequest->getData('email');

		$licensing = ffContainer()->getLicensing();

		$licensing->setLicenseKey($licenseKey);
		$licensing->setEmail($email);
		$result = $licensing->registerThisSite();

		$info = new ffDataHolder( $result );

		if( $info->status == 'success' ) {
			echo '<p>' . $info->message . '</p>';
		}
	}

	/**
	 * @param ffAjaxRequest $ajaxRequest\
	 */
	protected function _ajaxActionVerify( $ajaxRequest ) {

	}

	protected function _render() {


		$arkVersion = wp_get_theme();
		$arkVersion = $arkVersion->get('Version');

		$urlRewriter = ffContainer()->getUrlRewriter();

		$defaultUrl = $urlRewriter->addQueryParameter('adminScreenView', 'Default')->getNewUrl();
		$statusURl = $urlRewriter->addQueryParameter('adminScreenView', 'Status')->getNewUrl();

		$request = ffContainer()->getRequest();

		$action = $request->get('action');
		if( $action == 'deleteBuilderBackendCache' ) {
			$themeBuilderCache = ffContainer()->getThemeFrameworkFactory()->getThemeBuilderCache();
			$themeBuilderCache->deleteBackendCache();

		} else if ( $action == 'deleteBuilderFrontendCache') {
			$themeBuilderCache = ffContainer()->getThemeFrameworkFactory()->getThemeBuilderCache();
			$themeBuilderCache->deleteFrontendCache();

		} else if ( $action == 'disableWpDebug' ) {
			$wpConfig= ffContainer()->getWPConfigEditor();
			$wpConfig->disableWPDebug();
		} else if( $action == 'enableWpDebug' ) {
			$wpConfig= ffContainer()->getWPConfigEditor();
			$wpConfig->enableWPDebug();
		}

		if( $action != null ) {
			$urlRewriter->removeQueryParameter('action');
			echo '<script>window.location="' . $urlRewriter->getNewUrl().'";</script>';
		}

		echo '
		<div class="wrap about-wrap about-ark">
		<h1>Welcome to Ark!</h1>


		<p class="about-text">Thank you for choosing Ark as your platform! Before using it, please activate your License to unlock all functionality.</p>
		<div class="wp-badge"><div class="ff-logo"></div><span>Version '.$arkVersion.'</span></div>

		<h2 class="nav-tab-wrapper wp-clearfix">
			<a href="'.$defaultUrl.'" class="nav-tab">License Activation</a>
			
			<a href="'.$statusURl.'" class="nav-tab nav-tab-active">System Status & Tools</a>
			
		</h2>
		
		';

		$this->_printDashboardTabStatus();


		echo '</div>';
	}

	protected function _printDashboardTabStatus() {
		$urlRewriter = ffContainer()->getUrlRewriter();


		echo '<h3 style="text-align:left;">Backend Cache</h3>';





		$urlRewriter->addQueryParameter('action', 'deleteBuilderBackendCache');

		$themeBuilderCache = ffContainer()->getThemeFrameworkFactory()->getThemeBuilderCache();
		$backendCacheFiles = $themeBuilderCache->numberOfFilesInBackendCache();
		echo '<p>If you have experience issues with loading the Fresh Builder, please try cleaning the Backend Cache.</p>';
		echo '<p><a class="ffb-clean-backend-cache button button-primary" href="'.$urlRewriter->getNewUrl().'">Clean the Backend Cache ('.$backendCacheFiles.' files)</a></p>';

		$urlRewriter->addQueryParameter('action', 'deleteBuilderFrontendCache');
		$frontendCacheFiles = $themeBuilderCache->numberOfFilesInFrontedCache();

		echo '<h3 style="text-align:left;">Frontend Cache</h3>';
		echo '<p>If you experience issues with rendering of your website, please try cleaning the Frontend Cache.</p>';
		echo '<p><a class="button button-primary" href="'.$urlRewriter->getNewUrl().'">Clean the Frontend Cache ('.$frontendCacheFiles.' files)</a></p>';

		echo '<h3 style="text-align:left;">System Status</h3>';

		$sysEnv = ffContainer()->getThemeFrameworkFactory()->getSystemEnvironment();
		$serverReport = $sysEnv->generateServerReport();

		$notCompatible = $sysEnv->getNonValidServerReportLines();
		if( !empty( $notCompatible ) ) {
			echo '<table class="ff-system-status-table" style="padding:40px; background-color:#dcdcdc;">';
			echo '<thead>';
			echo '<tr>';
			echo '<td>Name</td>';
			echo '<td>Status</td>';
			echo '<td>Value</td>';
			echo '<td>Recommended</td>';
			echo '</tr>';
			echo '</thead>';
			foreach ($notCompatible as $key => $values) {
				$values = ffDataHolder($values);
				echo '<tr>';
				echo '<td>' . $values->name . '</td>';

				echo '<td>';
				if ($values->valid) {
					echo '<span style="color:green;">OK</span>';
				} else {
					echo '<span style="color:red;">WRONG</span>';
				}
				echo '</td>';

				echo '<td style="width:50%;">';
				if ($key == 'active_plugins') {
					$lines = explode("\n", $values->value);
					echo '<table>';
					foreach ($lines as $oneLine) {
						$data = explode(' -- ', $oneLine);
						echo '<tr>';
						foreach ($data as $key => $oneCol) {
							if ($key == 2) break;

							echo '<td>' . $oneCol . '</td>';
						}
						echo '</tr>';
					}
					echo '</table>';
				} else {
					echo $values->value;
				}
				echo '</td>';

				echo '<td>';
				echo $values->recommended;
				echo '</td>';

				echo '</tr>';
			}
			echo '</table>';
		}

		echo '<table class="ff-system-status-table">';
			echo '<thead>';
				echo '<tr>';
					echo '<td>Name</td>';
					echo '<td>Status</td>';
					echo '<td>Value</td>';
					echo '<td>Recommended</td>';
				echo '</tr>';
			echo '</thead>';
			foreach( $serverReport as $key => $values ) {
				$values = ffDataHolder( $values );
				echo '<tr>';
					echo '<td>' . $values->name . '</td>';

					echo '<td>';
						if( $values->valid ) {
							echo '<span style="color:green;">OK</span>';
						} else {
							echo '<span style="color:red;">WRONG</span>';
						}
					echo '</td>';

					echo '<td style="width:50%;">';
						if( $key == 'active_plugins' ) {
							$lines = explode("\n", $values->value);
							echo '<table class="ff-system-status-table__plugins">';
							foreach( $lines as $oneLine ) {
								$data = explode(' -- ', $oneLine );
								echo '<tr>';
								foreach( $data as $key => $oneCol ) {
									if( $key == 2 ) break;

									echo '<td>' . $oneCol . '</td>';
								}
								echo '</tr>';
							}
							echo '</table>';
						} else {
							echo $values->value;
						}
					echo '</td>';

					echo '<td>';
						echo $values->recommended;
					echo '</td>';

				echo '</tr>';
			}
		echo '</table>';

		// echo '<style> .ff-system-status-table td{padding:0 10px 0 10px;} .ff-system-status-table td td {padding:0 20px 0 0;}</style>';

		$report = array();
		foreach( $serverReport as $key => $values ) {
			$values = ffDataHolder( $values );

			if( $key == 'active_plugins' ) {
				$oneLine = "\n". $values->id . ' -> ' . "\n\n" . $values->value ;
			} else {
				$oneLine = $values->valid . ' -> ' . $values->id . ' -> ||' . $values->value .'||';
			}


			$report[] = $oneLine;
		}

		if( !empty($notCompatible) ) {
			$report[] ='NOT COMPATIBLE';
			foreach( $notCompatible as $key => $values ) {
				$values = ffDataHolder( $values );

				if( $key == 'active_plugins' ) {
					$oneLine = "\n". $values->id . ' -> ' . "\n\n" . $values->value ;
				} else {
					$oneLine = $values->valid . ' -> ' . $values->id . ' -> ||' . $values->value .'||';
				}


				$report[] = $oneLine;
			}
		}



		echo '<h3 style="text-align:left;">System Report</h3>';
		echo '<p>Please share this with our support staff when prompted.</p>';
		echo '<textarea cols="100" rows="5">'.implode("\n", $report) .'</textarea>';

		echo '<h3 style="text-align:left;">WP_DEBUG</h3>';
		$wpConfig= ffContainer()->getWPConfigEditor();

		if( $wpConfig->getWPDebugValue() == true) {
			$urlRewriter->addQueryParameter('action', 'disableWpDebug');
			echo '<p>WP_DEBUG is currently: <span style="background-color: #ffffff;padding: 4px 8px;margin-left: 6px;border-radius: 4px;"><span style="display: inline-block;width: 10px;height: 10px;border-radius:10px;margin-right: 2px;background:#32d316;"></span> Enabled</span></p>';
			echo '<p><a class="button button-primary" href="'.$urlRewriter->getNewUrl().'">Disable WP_DEBUG</a></p>';
		} else {
			$urlRewriter->addQueryParameter('action', 'enableWpDebug');
			echo '<p>WP_DEBUG is currently: <span style="background-color: #ffffff;padding: 4px 8px;margin-left: 6px;border-radius: 4px;"><span style="display: inline-block;width: 10px;height: 10px;border-radius:10px;margin-right: 2px;background:#ff2a00;"></span> Disabled</span></p>';
			echo '<p><a class="button button-primary" href="'.$urlRewriter->getNewUrl().'">Enable WP_DEBUG</a></p>';
		}

	}





	protected function _requireAssets() {
		$themeBuilder = ffContainer()->getThemeFrameworkFactory()->getThemeBuilder();

		$themeBuilder->requireBuilderScriptsAndStyles();

//		$pluginUrl = ffPluginFreshMinificatorContainer::getInstance()->getPluginUrl();
//		$this->_getScriptEnqueuer()->addScript('ffAdminScreenMinificatorViewDefault', $pluginUrl .'/adminScreens/minificator/assets/adminScreen.js', array('jquery') );
		$this->_getStyleEnqueuer()
			->addStyleFramework('ff-site-preferences', '/framework/themes/sitePreferences/style.css');
		$this->_getStyleEnqueuer()
			->addStyleFramework('ff-frslib-options2-css', '/framework/frslib/new/frslib-options2.css');


		$this->_getScriptEnqueuer()
			->addScriptFramework('ff-site-preferences', '/framework/themes/dashboard/script.js');

	}


	protected function _setDependencies() {

	}

}