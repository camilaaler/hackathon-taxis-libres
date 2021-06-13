<?php
class ffAdminScreenDashboardViewDefault extends ffAdminScreenView {


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
		$consent_newsletter = $ajaxRequest->getData('consent_newsletter');



		$licensing = ffContainer()->getLicensing();

		$licensing->setLicenseKey($licenseKey);
		$licensing->setEmail($email);
		$licensing->setConsent( $consent_newsletter );
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
		$licenseKey = $ajaxRequest->getData('licenseKey');
		$email = $ajaxRequest->getData('email');
		$consent_newsletter = $ajaxRequest->getData('consent_newsletter');



		$licensing = ffContainer()->getLicensing();

		$licensing->setConsent( $consent_newsletter );

		$infoArray = $licensing->getInformationsForLicenseKey($licenseKey, $consent_newsletter);






		$info = new ffDataHolder( $infoArray );

		$ad = ffContainer()->getAjaxDispatcher();

		if( $info->status == 'ok' ) {
			ob_start();
//			var_Dump( $info );
			// echo '<p> Contact with server has been successful</p>';
			if( !$info->valid ) {
				echo '<p>License key is not valid</p>';
			} else {
				if( $info->is_current_domain_active ) {
					echo '<p>This License Key is active on this domain ('. $licensing->getThisSite() .').</p>';
					$licensing->setStatus( ffLicensing::STATUS_ACTIVE );

					$licensing->setLicenseKey( $licenseKey);
					$licensing->setEmail( $email );
				} else {
					echo '<p>This License Key is not active on this domain. Press the Activate License button below to attach this License Key to this domain.</p>';
					$licensing->setStatus( ffLicensing::STATUS_DEACTIVATED );
				}

				if( null != $info->get('history', null) ){

					echo '<p> Number of domain transfers left: <strong>' . $info['number-of-changes-left'] . '</strong></p>';

					echo '<h3>Registration History</h3>';
					echo '<table>';
					echo '<thead>';
					echo '<tr><td>Domain</td><td>Activation Date</td><td>Status</td></tr>';
					echo '</thead>';
					echo '<tbody>';
					foreach( $info->get('history', array() ) as $oneHistory ) {
						echo '<tr>';
						echo '<td>';
						echo $oneHistory['domain_name'];
						echo '</td>';

						echo '<td>';
						echo $oneHistory['date_added'] . ' GMT';
						echo '</td>';

						echo '<td>';
						echo $oneHistory['is_active'] ? 'Active': 'Disabled';
						echo '</td>';
						echo '</tr>';
					}
					echo '</tbody>';
					echo '</table>';

				}

				if( !$info->is_current_domain_active ) {
					echo '<input type="hidden" class="ff-input-hidden-key" value="' . esc_attr($licenseKey) . '">';
					echo '<input type="hidden" class="ff-input-hidden-email" value="' . esc_attr($email) . '">';
					echo '<input style="margin-top:10px;" type="submit" class="ff-button-action-register" value="Activate License for this domain">';
				}
//				echo '<input type="submit" '
			}


			$content = ob_get_contents();
			ob_end_clean();

			$ad->addResponse('can-be-registered', 1);
		} else {
			$ad->addResponse('can-be-registered', 0);
			$content = '<p>Contact with the License Server has failed. Make sure you are connected to the Internet. If the License Server is offline, please try again later.</p>';
		}

		$ad->addResponse('original_response', $infoArray );
		$ad->addResponse('status', 1);
		$ad->addResponse('html', $content);

	}

	protected function _render() {
		$arkVersion = wp_get_theme();
		$arkVersion = $arkVersion->get('Version');

		$urlRewriter = ffContainer()->getUrlRewriter();

		$defaultUrl = $urlRewriter->addQueryParameter('adminScreenView', 'Default')->getNewUrl();
		$statusURl = $urlRewriter->addQueryParameter('adminScreenView', 'Status')->getNewUrl();

		echo '
		<div class="wrap about-wrap about-ark">
		<h1>Welcome to Ark!</h1>

		<p class="about-text">Thank you for choosing Ark as your platform! Before using it, please activate your License to unlock all functionality.</p>
		<div class="wp-badge"><div class="ff-logo"></div><span>Version '.$arkVersion.'</span></div>
	
		<h3 style="text-align:left;">Video Introduction to Ark</h3>
		<iframe width="560" height="320" src="https://www.youtube.com/embed/6ROQzFyBvvk" frameborder="0" allowfullscreen></iframe>';
        // echo '<br><br><br>';

        // echo '<div style="float:left;" ><a style="background-color: #3b5998; color:white;padding:20px; font-size:25px; margin-top:20px; text-decoration:none; border:1px solid red;" target="_blank" href="https://www.facebook.com/ark.wordpress.theme/"><img style="margin-top:-5px; padding-right:5px;" src="'.ffContainer()->getWPLayer()->getFrameworkUrl().'/framework/themes/dashboard/facebook.png"/> ARK - official fb page (daily info)</a></div>';
        // echo '<div style="float:left; margin-left:50px;""><a style="background-color: #3b5998; color:white;padding:20px; font-size:25px; margin-top:20px; text-decoration:none;" target="_blank" href="https://www.facebook.com/groups/1827105637579063/"><img style="margin-top:-5px; padding-right:5px;" src="'.ffContainer()->getWPLayer()->getFrameworkUrl().'/framework/themes/dashboard/facebook.png"/> ARK - facebook group</a></div>';

        echo '
		
		<br>
		<br>
		<br>
		<h2 class="nav-tab-wrapper wp-clearfix">
			<a href="'.$defaultUrl.'" class="nav-tab nav-tab-active">License Activation</a>
			
			<a href="'.$statusURl.'" class="nav-tab">System Status & Tools</a>
			<!--
			<a href="./admin.php?page=Dashboard" class="nav-tab">Extensions</a>
			<a href="./admin.php?page=Dashboard" class="nav-tab">System Status</a>
			-->
		</h2>

<!--
		<p class="about-description">WordPress is Free and open source software, built by a distributed community of mostly volunteer developers from around the world. WordPress comes with some awesome, worldview-changing rights courtesy of its <a href="https://wordpress.org/about/license/">license</a>, the GPL.</p>

		<div class="changelog">
			<div class="under-the-hood three-col">
				<div class="col">
					<h3>Step 1</h3>
					<p>WordPress is Free and open source software, built by a distributed community of mostly volunteer developers from around the world. WordPress comes with some awesome, worldview-changing rights courtesy of its <a href="https://wordpress.org/about/license/">license</a>, the GPL.</p>
				</div>
				<div class="col">
					<h3>Step 2</h3>
					<p>WordPress is Free and open source software, built by a distributed community of mostly volunteer developers from around the world. WordPress comes with some awesome, worldview-changing rights courtesy of its <a href="https://wordpress.org/about/license/">license</a>, the GPL.</p>
				</div>
				<div class="col">
					<h3>Step 3</h3>
					<p>WordPress is Free and open source software, built by a distributed community of mostly volunteer developers from around the world. WordPress comes with some awesome, worldview-changing rights courtesy of its <a href="https://wordpress.org/about/license/">license</a>, the GPL.</p>
				</div>
			</div>
		</div> -->';

		$this->_printDashboardTabRegister();

		echo '<br><hr><br>
<p>
		<strong>About License Activation</strong>
		</p>
		<p>
		One License can be active only on one site. But the License can be transferred upto 5 times to a different site. Each transfer will automatically deactivate the previous activation. Localhost, different subdomains and TLDs count towards your transfer limit as well.
		</p>
		<p>
		Example: You start developing on localhost and activate your License there - now you have 4 transfers left. Then you transfer your website to a production server and activate your License there - now you have 3 transfers left. Note that the License activated on localhost has been automatically deactivated thanks to License transfer to the new production server.
		</p>
		<p>
		Should you need more than 5 transfers, please contact us and we will be more than happy to reset your limit so you have 5 available transfers again.
		</p>


		
		<!--<p style="font-size:11px; color:#aaa">*By activating your license you agree to receive occasional e-mail from FRESHFACE</p>-->
	';

//		echo '<h2 style="text-align:left;">Ark Facebook Group</h2>';

//        echo '<br><br><br>';


		// echo '<h2 style="text-align:left;">Online Documentation</h2>';
		// echo '<a href="http://arktheme.com/docs/" target="_blank"> Click here to visit online documentation </a>';
		// echo '</div>';
	}

	protected function _printDashboardTabRegister() {
		$licensing = ffContainer()->getLicensing();

		$licenseKey = $licensing->getLicenseKey();
		$email = $licensing->getEmail();

		$licensing->getInformationsForLicenseKey('getInformationsForLicenseKey');

		$status = $licensing->getStatus();


		$generalConsent = '';

		if( $status != ffLicensing::STATUS_NOT_REGISTERED ) {
			$generalConsent = 'checked="checked"';
		}

		$newsletterConsent = '';


		if( $licensing->getConsent() == 1 ) {
			$newsletterConsent = 'checked="checked"';
		}

		echo '<div class="ff-dashboard-tab ff-dashboard-tab__register">';
//		if( $licenseKey == null ) {
			echo '<div class="ff-register__license-key">';
				echo '<div class="ff-register__status-msg">';
					if( $status == ffLicensing::STATUS_NOT_REGISTERED ) {
						echo '<h3><i class="dashicons dashicons-no-alt"></i><span> Your License has yet to be activated. The full version of Ark is disabled until you activate your License for this domain. Please press the Verify button below for more information.</span></h3>';
					} else if ( $status == ffLicensing::STATUS_DEACTIVATED ) {
						echo '<h3><i class="dashicons dashicons-warning"></i><span> Your License is not active on this domain. The full version of Ark is disabled until you activate your License for this domain. Please press the Show License button below for more information.</span></h3>';
					} else if( $status == ffLicensing::STATUS_ACTIVE ) {
						echo '<h3><i class="dashicons dashicons-yes"></i><span> Your License has been succesfully activated for this domain. You are now enjoying the full version of Ark!</span></h3>';
					}

					$licenseKeyDetails = $licensing->getKeyDetails();

				if( isset( $licenseKeyDetails['supported_till'] ) ) {
					$supportedTill = $licenseKeyDetails['supported_till'];

					if( $supportedTill > 0 ) {
						$supportedTillDate = date('d F Y', $supportedTill);

						if( $supportedTill > time() ) {
							echo '<h3 style="">Updates and support will expire : ' . $supportedTillDate.'</h3>';
						} else {
							echo '<h3 style="color:red; text-transform:uppercase;">Updates and support expired : ' . $supportedTillDate.' please renew support</h3>';
//							echo '<h3>Updates and support valid : ' . $supportedTillDate.'</h3>';
						}

					}
				}



				echo '</div><br>';
//				echo '<p>For automatic updates, please register your site</p>';
				echo '<form>';
					echo '<label class="ff-register-form-label"><input type="text" class="ff-input-license-key" value="'.$licenseKey.'"> License Key</label><br>';
					echo '<label class="ff-register-form-label"><input type="text" class="ff-input-email" value="'.$email.'"> E-mail Address<span style="color:#aaa">*</span></label><br>';
					echo '<label class="ff-register-form-label"><input type="checkbox" '.$generalConsent.' class="ff-input-checkbox-consent ff-input-checkbox-consent_general" value="'.$email.'"> I agree that my e-mail and license key will be stored on the FRESHFACE remote server (required)</label><br>';
					echo '<label class="ff-register-form-label"><input type="checkbox" '.$newsletterConsent.' class="ff-input-checkbox-consent ff-input-checkbox-consent_newsletter" value="'.$email.'"> I agree with receiving an occasional e-mail from FRESHFACE (relevant information, Ark development, fun community competitions, etc.)</label><br>';
					echo '<input style="margin-top:10px;" type="submit" class="ff-button-action-verify" value="Show License">';
				echo '</form>';
				echo '<div class="ff-ajax-output-holder">';
				echo '</div>';
			echo '</div>';
//		}

		echo '</div>';
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