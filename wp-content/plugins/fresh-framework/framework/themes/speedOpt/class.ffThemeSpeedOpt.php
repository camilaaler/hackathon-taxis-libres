<?php

class ffThemeSpeedOpt extends ffBasicObject {

	public function run() {

		ffContainer()->getScriptEnqueuer()->addScriptFramework('ff-defer-css-maker', '/framework/themes/speedOpt/deferCss.js');
		return;

		?>
			<html>
			<head>
				<script type='text/javascript' src='http://localhost/ark/ark-loops/wp-includes/js/jquery/jquery.js?ver=1.12.4'></script>
				<script type='text/javascript' src='http://localhost/ark/ark-loops/wp-content/plugins/p-fresh-framework//framework/themes/speedOpt/deferCss.js?ver=1.29.1'></script>
			</head>
			<body>
			<div>


			</div>

			</body>
			</html>


<?php


		die();

	}

}