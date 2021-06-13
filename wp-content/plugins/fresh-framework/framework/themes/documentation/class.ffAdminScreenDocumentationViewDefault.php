<?php
class ffAdminScreenDocumentationViewDefault extends ffAdminScreenView {


	public function ajaxRequest( ffAdminScreenAjax $ajax ) {

//		var_dump( $ajax );
	}



	public function newAjaxRequest( ffAjaxRequest $ajaxRequest ) {

	}




	protected function _render() {

		
		echo '<div class="wrap about-wrap">';
			echo '<h1>Docs & Support</h1>';
			echo '<p class="about-text">Learn more about Ark and get help from developers or other users.</p>';
		echo '</div>';

		echo '<div class="wrap">';
			echo '<div style="max-width:560px;">';
				echo '<div class="ark-support-box">';
				echo '<h2 class="ark-support-box-title">';
					if( ffThemeOptions::getQuery('layout')->getWithoutComparationDefault('enable-academy-info', 1) && ! defined('ARK_DISABLE_ACADEMY_INFO') ) {
						echo 'Free ';
					}
				echo 'Resources</h2>';
				echo '<a href="#first-aid-help">» First Aid Help (please check this out)</a><br>';
				echo '<a href="https://www.youtube.com/playlist?list=PLtdsaq239-KDx0hg_FQYb60VpF0E2y_Qm" target="_blank">» Video Tutorials</a><br>';
				echo '<a href="http://arktheme.com/docs/" target="_blank">» Documentation</a><br>';
				echo '<a href="http://arktheme.com/open-ticket/" target="_blank">» Get Support (Open Ticket)</a><br>';
				echo '<a href="https://www.facebook.com/groups/1827105637579063/" target="_blank">» The Ark Facebook Group (1500+ users)</a><br>';

				if( ffThemeOptions::getQuery('layout')->getWithoutComparationDefault('enable-academy-info', 1) && ! defined('ARK_DISABLE_ACADEMY_INFO') ) {
					echo '<h2 class="ark-support-box-title">Premium Resources</h5>';
					echo '<a href="http://arktheme.com/academy/" target="_blank">» Training Academy</a><br>';
					echo '<a href="http://arktheme.com/hire-us/" target="_blank">» Hire an Expert</a><br>';
				}
				echo '</div>';
			echo '</div>';
		echo '</div>';

		echo '<div class="wrap about-wrap">';
			// echo '<h3 style="text-align:left;">Refund – Money Back Guarantee</h3>';
			echo '<div class="card"><h3 class="title">Refund – Money Back Guarantee</h3><p>If you are not <strong>100%</strong> happy with The Ark we offer a full refund without any questions, at any time. We want everyone to be <strong>100%</strong> satisfied with their purchase so if you are not, simply get a refund by following the link below:<br><br><a target="_blank" style="font-size: 20px;" href="https://themeforest.net/refund_requests/new">Get Refund &#8594;</a></p></div>';
		
			echo '<h3 style="text-align:left;">Video Introduction to Ark</h3>';
			echo '<iframe width="581" height="327" src="https://www.youtube.com/embed/6ROQzFyBvvk" frameborder="0" allowfullscreen></iframe>';

			echo '<div class="card">';
				echo '<h3 class="title" id="first-aid-help">First Aid</h3>';
				echo '<p><strong style="font-size:20px">Fresh Builder is not loading.</strong></p>';
				?>
				<p>When FreshBuilder is not loading, it's usually related to your hosting environment. Please follow these steps, as they are able to sort it out for 99% of users:</p>

				<p>1.) In most cases, this is a result of conflict with one of the 3rd party plugins used on your site. Usually, the plugins throw up some kind of error or notice (not related to ark) and this error or notice is screwing up the loading. So as a first aid, please try to de-activate all 3rd party plugins (or all plugins at all), EXCEPT Fresh Framework and Ark Core.</p>

				<p>2.) If this does not help, can you please try to add this into your HTACCESS FILE?</p>

				<pre>
&lt;IfModule mod_substitute.c&gt;
SubstituteMaxLineLength 20M
&lt;/IfModule&gt;
				</pre>

				<p>If you are not experienced with the .htaccess file, then:</p>
				<p>- it can be inserted in the root of your wp install (where are folders like wp-content, wp-admin and files like wp-config.php)</p>
				<p>- you can google this top, there are plenty and plenty of articles about this:</p>

				<p>3.) If this does not help, then most probably there is an inconsistency within your PHP memory limit (the told by apache and actual server one). This means, that if Ark asks "what is your PHP limit", Apache answers (for example) 256mb. But in reality, your server has only 128mb of PHP memory limit.</p>

				<p>Ark is monitoring its resources when loading the Fresh Builder to not overflow the memory limit. But Ark thinks, that 256mb is ok, so he easily overflows the 128mb.</p>

				<p>This can be sorted out, if you actually make the PHP memory limit smaller (like 128mb or even 64mb without any other plugins active). It can be done within your wp-config.php file and I would suggest you to google "PHP memory limit change".</p>

				<strong>Please, contact support only after making these steps.</strong>
<?php


//				echo '<p>A) Clean the Backend Cache via <a href="'.admin_url('admin.php?page=Dashboard&adminScreenView=Status').'">Ark > Dashboard > System Status</a>. Then refresh your browser on the Fresh Builder editor several times (3-5x).</p>';
//				echo '<p>B) Enable <em>SubstituteMaxLineLength</em> via <a href="'.admin_url('admin.php?page=ThemeOptions#GeneralBuilder').'">Ark > Theme Options > General</a>.</p>';
				echo '<p><strong style="font-size:20px">The site looks incorrect/broken in some way(s).</strong></p>';
				echo '<p>A) Clean the Frontend Cache via <a href="'.admin_url('admin.php?page=Dashboard&adminScreenView=Status').'">Ark > Dashboard > System Status</a>.</p>';
				echo '<p>B) Deactivate the Fresh Performance Cache plugin.</p>';
			echo '</div>';
		echo '</div>';


/*
		echo '<div class="wrap">';
		// echo '<h1>Support & Docs</h1>';


			echo '<div style="max-width:560px;">';

			
			echo '<div style="padding: 30px 40px 30px 40px; background: #feffbf;">';
			echo '<h2 style="font-size: 20px;">Refund – Money Back Guarantee</h2>';
			echo '<p style="font-size: 16px;">If you are not <strong>100%</strong> happy with The Ark we offer a full refund without any questions, at any time. We want everyone to be <strong>100%</strong> satisfied with their purchase so if you are not, simply get a refund by following the link below:<br><br><a target="_blank" style="font-size: 20px;" href="https://themeforest.net/refund_requests/new">Get Refund &#8594;</a></p>';
			echo '</div>';


			echo '<h1 style="padding:15px 40px; background-color:red; color:white; margin-top:15px;">FIRST AID HELP</h1>';
			echo '<div style="background-color:white; padding: 5px 40px 5px 40px; margin-bottom:15px;">';
			echo '<p><strong>1.) Fresh Builder is not loading</strong> - delete backend builder cache in WP Admin -> Ark -> Dashboard -> <a target="_blank" href="'.get_admin_url(null, '/admin.php?page=Dashboard&adminScreenView=Status').'">System Status</a>, then try to refresh your browser in Fresh Builder editor few times (3-5). If this does not help, post a ticket (check links below at this page)</p>';

			echo '<p><strong>2.) Your site CSS looks weird</strong> - Deactivate Fresh Performance Cache plugin </p>';
			echo '</div>';

			echo '<h2>MUST-WATCH VIDEO</h2>';
			echo '<p>Firstly, we strongly recommend you to watch this quick video with english voice-over. It contains everything important that you need to know to get started.</p>';
			echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/6ROQzFyBvvk" frameborder="0" allowfullscreen></iframe>';
			echo '<br>';
			echo '<br>';
			echo '<br>';
	
			echo '<br>';
	//		echo '<p><a target="_blank" href="https://themeforest.net/refund_requests/new">https://themeforest.net/refund_requests/new</a></p>';
			
			echo '</div>';
		echo '</div>';



		*/
	}



	protected function _requireAssets() {
		$themeBuilder = ffContainer()->getThemeFrameworkFactory()->getThemeBuilder();

		$themeBuilder->requireBuilderScriptsAndStyles();

//		$pluginUrl = ffPluginFreshMinificatorContainer::getInstance()->getPluginUrl();
//		$this->_getScriptEnqueuer()->addScript('ffAdminScreenMinificatorViewDefault', $pluginUrl .'/adminScreens/minificator/assets/adminScreen.js', array('jquery') );
		$this->_getStyleEnqueuer()
			->addStyleFramework('ff-dummy-content', '/framework/themes/dummy/adminScreen/style.css');

//		$this->_getScriptEnqueuer()
//			->addScriptFramework('ff-site-preferences', '/framework/themes/sitePreferences/script.js');
	}


	protected function _setDependencies() {

	}

}