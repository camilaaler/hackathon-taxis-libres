<?php

class ffFrameworkScriptLoader extends ffBasicObject {

	/**
	 *
	 * @var ffScriptEnqueuer
	 */
	private $_scriptEnqueuer = null;

	/**
	 *
	 * @var ffStyleEnqueuer
	 */
	private $_styleEnqueuer = null;

	public function __construct( ffWPLayer $WPLayer, ffScriptEnqueuer $scriptEnqueuer, ffStyleEnqueuer $styleEnequeuer ) {
		$this->_setScriptEnqueuer( $scriptEnqueuer );
		$this->_setStyleEnqueuer( $styleEnequeuer );
		$this->_setWPLayer( $WPLayer );
	}

	public function requireFrsLib() {
		$this->_getScriptEnqueuer()->addScriptFramework(
			'ff-frslib',
			'/framework/frslib/src/frslib.js',
			array( 'jquery' ),
            5
		);

        $this->_getScriptEnqueuer()->addScriptFramework(
            'ff-md5',
            '/framework/frslib/src/md5.js',
            array( 'jquery' ),
            5
        );

		$this->_getScriptEnqueuer()->addScriptFramework(
			'ff-zlib',
			'/framework/extern/zlib/zlib.min.js',
			array( 'jquery' ),
			5
		);



		return $this;
	}

	public function requireFreshGrid() {

		$path = '/framework/themes/builder/metaBoxThemeBuilder/assets/freshGrid/';

		$css = $this->_getStyleEnqueuer();
		$js = $this->_getScriptEnqueuer();

		$css->addStyleFramework('animate', $path.'extern/animate.css/animate.min.css');
		$css->addStyleFramework('ff-freshgrid', $path.'freshGrid.css');

		$js->addScriptFramework('jquery.wow', $path.'extern/wow.js/wow.min.js', array('jquery'), null, true);
		$js->addScriptFramework('ff-freshgrid', $path.'jquery.freshGrid.js', array('jquery'), null, true);
//		$this->_getScriptEnqueuer()->addScriptFramework(
//			'ff-feedbacker',
//			'/framework/frslib/src/feedbacker.js',
//			array( 'jquery' ),
//			5
//		);
	}

	public function requireJqueryHotkeys() {
		$this->_getScriptEnqueuer()->addScriptFramework('ff-jquery-hotkeys', '/framework/extern/jquery-hotkeys/jquery.hotkeys.js', array('jquery'));

		return $this;
	}

    public function requireJqueryCookie() {

        $this->_getScriptEnqueuer()->addScriptFramework('ff-jquery-cookie', '/framework/extern/jquery-cookie/jquery.cookie.js', array('jquery'), null, true);
    }

	public function requireFrsLibLess() {
		$this->requireFrsLib();

		$this->_getScriptEnqueuer()->addScriptFramework('ff-less-compiler', '/framework/extern/less/js-compiler/less.js');
		$this->_getScriptEnqueuer()->addScriptFramework('ff-frslib-less-compiler', '/framework/frslib/src/frslib-less-compiler.js');
	}

    public function requireRequireJS() {
        $this->_getScriptEnqueuer()->addScriptFramework('ff-require-js', '/framework/extern/requirejs/require.js');

        return $this;
    }

	public function requireMinicolors() {
		$this->requireFrsLib();
//		$this->_getScriptEnqueuer()->addScriptFramework('ff-minicolors', '/framework/extern/minicolors/jquery.minicolors.js', array('jquery'));
//		$this->_getStyleEnqueuer()->addStyleFramework('ff-minicolors', '/framework/extern/minicolors/jquery.minicolors.css');

		return $this;
	}

    private function _isOurFrontTheme() {
        if( function_exists('wp_get_theme') ) {
            $theme = wp_get_theme()->template;

            if( $theme == 't-front') {
                return true;
            } else {
                return false;
            }
        }

        return false;
    }

    public function requireBackboneDeepModel() {
        $this->_getScriptEnqueuer()
				->addScriptFramework('ff-backbone-deep-model', '/framework/extern/backboneDeepModel/backbone.deep.model.js' );

        return $this;
    }

	public function disableOldFrsLibOptions() {
		$this->_disableFrsLib = true;
	}

	private $_disableFrsLib = false;

	/**
	 * @return $this ffFrameworkScriptLoader
	 */
	public function requireFrsLibOptions() {
		if( $this->_disableFrsLib ) {
			return $this;
		}
//		debug_print_backtrace();
//		die();
//        var_dump( wp_get_theme()->template );
//        die();
		$this->requireSelect2();
		$this->_getScriptEnqueuer()->addScript('jquery-ui-core');
		$this->_getScriptEnqueuer()->addScript('jquery-ui-sortable');

        if( !$this->_isOurFrontTheme() ) {
            $this->_getScriptEnqueuer()->addScript('jquery-ui-datepicker'); //disabled for now because it was being included into t-front which is not wanted
        }

        $this->_getScriptEnqueuer()->addScript('wp-color-picker');
        $this->_getStyleEnqueuer()->addStyle('wp-color-picker');

        $this->requireFrsLibModal();

        $this->_getScriptEnqueuer()->addScriptFramework(
			'ff-frslib-options-walkers-walker',
			'/framework/frslib/src/frslib-options-walkers-walker.js',
			array( 'ff-frslib' ),
            3
		);

        $this->_getScriptEnqueuer()->addScriptFramework(
			'ff-frslib-options-walkers-printerBoxed',
			'/framework/frslib/src/frslib-options-walkers-printerBoxed.js',
			array( 'ff-frslib' ),
            10
		);


        $this->_getScriptEnqueuer()->addScriptFramework(
			'ff-frslib-options-walkers-defaultDataConvertor',
			'/framework/frslib/src/frslib-options-walkers-defaultDataConvertor.js',
			array( 'ff-frslib' )
		);

        $this->_getScriptEnqueuer()->addScriptFramework(
			'ff-frslib-options-query',
			'/framework/frslib/src/frslib-options-query.js',
			array( 'ff-frslib' )
		);


		$this->_getScriptEnqueuer()->addScriptFramework(
			'ff-frslib-options-addons',
			'/framework/frslib/src/frslib-options-addons.js',
			array( 'ff-frslib' ),
			28
		);


		$this->_getScriptEnqueuer()->addScriptFramework(
			'ff-frslib-options',
			'/framework/frslib/src/frslib-options.js',
			array( 'ff-frslib' ),
            28
		);




        if( !$this->_isOurFrontTheme() ) {
            $this->_getStyleEnqueuer()->addStyleFramework('jquery-ui-datepicker', 'framework/extern/jquery-ui/datepicker.css'); //disabled for now because it was being included into t-front which is not wanted
        }
		return $this;
	}

	public function requireFrsLibOptions2() {

		$deps =  array('ff-frslib','jquery', 'backbone', 'underscore');
		$this->requireSelect2New();

		$this->requireJqueryHotkeys();

		ffContainer()->getAceLoader()->loadAceEditor( false );

		$this->_getScriptEnqueuer()->addScriptFramework(
			'ff-frslib-options2-element-helper',
			'/framework/frslib/new/options-element-helper.js',
			$deps,
			28
		);

		$this->_getStyleEnqueuer()->addStyleFramework(
			'ff-frslib-options2-css',
			'/framework/frslib/new/frslib-options2.css'
		);


		$this->_getScriptEnqueuer()->addScriptFramework(
			'ff-frslib-options2-modal',
			'/framework/frslib/new/options-modal.js',
			$deps,
			28
		);

		$this->_getScriptEnqueuer()->addScriptFramework(
			'ff-frslib-options2-walker',
			'/framework/frslib/new/options-walker.js',
			$deps,
			28
		);

		$this->_getScriptEnqueuer()->addScriptFramework(
			'ff-frslib-options2-printer-basic',
			'/framework/frslib/new/options-printer-basic.js',
			$deps,
			28
		);

		$this->_getScriptEnqueuer()->addScriptFramework(
			'ff-frslib-options2-printer-options',
			'/framework/frslib/new/options-printer-options.js',
			$deps,
			28
		);

		$this->_getScriptEnqueuer()->addScriptFramework(
			'ff-frslib-options2-printer-elements',
			'/framework/frslib/new/options-printer-elements.js',
			$deps,
			28
		);

		$this->_getScriptEnqueuer()->addScriptFramework(
			'ff-frslib-options2-printer-sections',
			'/framework/frslib/new/options-printer-sections.js',
			$deps,
			28
		);

		$this->_getScriptEnqueuer()->addScriptFramework(
			'ff-frslib-options2-printer',
			'/framework/frslib/new/options-printer.js',
			$deps,
			28
		);

		$this->_getScriptEnqueuer()->addScriptFramework(
			'ff-frslib-options2-color-library',
			'/framework/frslib/new/options-color-library.js',
			$deps,
			28
		);

		$this->_getScriptEnqueuer()->addScriptFramework(
			'ff-frslib-options2-form-parser',
			'/framework/frslib/new/options-form-parser.js',
			$deps,
			28
		);

		$this->_getScriptEnqueuer()->addScriptFramework(
			'ff-frslib-options2-query',
			'/framework/frslib/new/options-query.js',
			$deps,
			28
		);

		$this->_getScriptEnqueuer()->addScriptFramework(
			'ff-frslib-options2-data-manager',
			'/framework/frslib/new/options-data-manager.js',
			$deps,
			28
		);

		$this->_getScriptEnqueuer()->addScriptFramework(
			'ff-frslib-options2-manager',
			'/framework/frslib/new/options-manager.js',
			$deps,
			28
		);

		$this->_getScriptEnqueuer()->addScriptFramework(
			'ff-frslib-options2-manager-global-options',
			'/framework/frslib/new/options-manager-global-options.js',
			$deps,
			28
		);

		$this->_getScriptEnqueuer()->addScriptFramework(
			'ff-frslib-options2-manager-modal',
			'/framework/frslib/new/options-manager-modal.js',
			$deps,
			28
		);

		$this->_getScriptEnqueuer()->addScriptFramework(
			'ff-frslib-options2-data-holder',
			'/framework/frslib/new/options-data-holder.js',
			$deps,
			28
		);



		$this->_getScriptEnqueuer()->addScriptFramework(
			'ff-frslib-options2-options',
			'/framework/frslib/new/options.js',
			$deps,
			28
		);
		$styleEnqueuer = $this->_getStyleEnqueuer();
		$scriptEnqueuer = $this->_getScriptEnqueuer();

		$scriptEnqueuer->addScriptFramework('ffb-builder-notification-manager', '/framework/themes/builder/metaBoxThemeBuilder/assets/class.NotificationManager.js', $deps, null, true);

		$this->requireEnvironment();

		$styleEnqueuer->addStyleFramework('ffb-builder-spectrum', '/framework/themes/builder/metaBoxThemeBuilder/assets/extern/jquery.spectrum/jquery.spectrum.css');
	    $scriptEnqueuer->addScriptFramework('ffb-builder-spectrum', '/framework/themes/builder/metaBoxThemeBuilder/assets/extern/jquery.spectrum/jquery.spectrum.min.js', $deps, null, true);


	}

	public function requireEnvironment( $deps = array()) {

		$deps = array_merge( $deps, array( 'ff-frslib','jquery', 'backbone', 'underscore') );


		$this->_getScriptEnqueuer()->addScriptFramework(
			'ff-frslib-environment',
			'/framework/frslib/new/environment.js',
			$deps,
			28
		);
	}

	public function requireFrsLibModal() {

		$this->requireMinicolors();
		$this->_getScriptEnqueuer()->addScriptFramework(
			'ff-frslib-classes',
			'/framework/frslib/src/frslib-classes.js',
			array( 'ff-frslib' )
		);

		$this->_getScriptEnqueuer()->addScriptFramework(
			'ff-class.modalWindow',
			'/framework/frslib/src/frslib-class.modalWindow.js',
			array( 'jquery', 'ff-frslib-classes' )
		);

		$this->_getScriptEnqueuer()->addScriptFramework(
			'ff-class.modalWindow_aPicker',
			'/framework/frslib/src/frslib-class.modalWindow_aPicker.js',
			array( 'ff-class.modalWindow' )
		);

		$this->_getScriptEnqueuer()->addScriptFramework(
			'ff-class.modalWindow_aBasic',
			'/framework/frslib/src/frslib-class.modalWindow_aBasic.js',
			array( 'ff-class.modalWindow' )
		);

		$this->_getScriptEnqueuer()->addScriptFramework(
			'ff-class.modalWindowColorPicker',
			'/framework/frslib/src/frslib-class.modalWindowColorPicker.js',
			array( 'ff-class.modalWindow_aPicker' )
		);

//		$this->_getScriptEnqueuer()->addScriptFramework(
//			'ff-class.modalWindowColorEditor',
//			'/framework/frslib/src/frslib-class.modalWindowColorEditor.js',
//			array( 'ff-class.modalWindowColorPicker' )
//		);

		$this->_getScriptEnqueuer()->addScriptFramework(
				'ff-class.modalWindowColorLibraryColor',
				'/framework/frslib/src/frslib-class.modalWindowColorLibraryColor.js',
				array( 'ff-class.modalWindow_aPicker' )
		);

		$this->_getScriptEnqueuer()->addScriptFramework(
				'ff-class.modalWindowIconPicker',
				'/framework/frslib/src/frslib-class.modalWindowIconPicker.js',
				array( 'ff-class.modalWindow_aPicker' )
		);

		$this->_getScriptEnqueuer()->addScriptFramework(
				'ff-class.modalWindowColorAddGroup',
				'/framework/frslib/src/frslib-class.modalWindowColorAddGroup.js',
				array( 'ff-class.modalWindow' )
		);

		$this->_getScriptEnqueuer()->addScriptFramework(
				'ff-class.modalWindowColorDeleteGroup',
				'/framework/frslib/src/frslib-class.modalWindowColorDeleteGroup.js',
				array( 'ff-class.modalWindow' )
		);

		$this->_getScriptEnqueuer()->addScriptFramework(
				'ff-class.modalWindowSectionPicker',
				'/framework/frslib/src/frslib-class.modalWindowSectionPicker.js',
				array( 'ff-class.modalWindow' )
		);

		$this->_getScriptEnqueuer()->addScriptFramework(
			'ff-frslib-modaxl',
			'/framework/frslib/src/frslib-modal.js',
				array()
			//array( 'ff-frslib', 'ff-class.modalWindowColorPicker')
		);

		return $this;
	}

	public function requireFrsLibMetaboxes() {
		$this->_getScriptEnqueuer()
				->addScriptFramework('ff-frslib-metaboxes', '/framework/frslib/src/frslib-metaboxes.js', array('ff-frslib'), 8);

		return $this;
	}

	public function requireSelect2() {
		$this->_getStyleEnqueuer()->addStyleFramework('select2', '/framework/extern/select2/jquery.select2.css');
		$this->_getScriptEnqueuer()->addScriptFramework('select2', '/framework/extern/select2/jquery.select2.min.js');
		$this->_getScriptEnqueuer()->addScriptFramework('select2-tools', '/framework/extern/select2/select2-tools.js');

		return $this;
	}

	public function requireSelect2New() {
		$this->_getStyleEnqueuer()->addStyleFramework('select2-freshface', '/framework/extern/select2New/select2.min.css');
		$this->_getScriptEnqueuer()->addScriptFramework('select2-freshface', '/framework/extern/select2New/select2.full.min.js');

		return $this;
	}

	public function requireJsTree() {
		$this->_getStyleEnqueuer()->addStyleFramework('ff-jstree-style', '/framework/extern/jstree/themes/default/style.min.css');
		$this->_getScriptEnqueuer()->addScriptFramework('ff-jstree-script', '/framework/extern/jstree/jstree.js', array('jquery'));
	}

    public function requireDataTables() {
        $this->_getStyleEnqueuer()->addStyleFramework('ff-datatables-style', '/framework/extern/dataTables/datatables.min.css');
		$this->_getScriptEnqueuer()->addScriptFramework('ff-datatables-script', '/framework/extern/dataTables/datatables.min.js', array('jquery'));
    }

	public function requireFfAdmin() {
		$this->_getStyleEnqueuer()->addStyleFrameworkAdmin('ff-admin-css', 'framework/adminScreens/assets/css/ff-admin.css', null, null, null, null);
		// $this->_getStyleEnqueuer()->addStyleFrameworkAdmin('ff-admin', 'framework/adminScreens/assets/css/ff-admin.less', null, null, null, null);

		// $this->addAdminColorsToStyle('ff-admin');
		// $this->_getStyleEnqueuer()->addLessVariable('ff-admin','fresh-framework-url', '"'.ffContainer::getInstance()->getWPLayer()->getFrameworkUrl().'"' );
		//$this->_getStyleEnqueuer()->addStyle()

		return $this;
	}

	public function addAdminColorsToStyle( $styleName ){

		$userID      = $this->_getWPlayer()->get_current_user_id();
		$admin_color = $this->_getWPlayer()->get_the_author_meta( 'admin_color', $userID );

		$_wp_admin_css_colors = $this->_getWPlayer()->get_wp_admin_css_colors();

		if( empty( $_wp_admin_css_colors[ $admin_color ] ) ){
			$this->_getStyleEnqueuer()->addLessVariable( $styleName, 'wpadmin_colors_0',            '#222');
			$this->_getStyleEnqueuer()->addLessVariable( $styleName, 'wpadmin_colors_1',            '#333');
			$this->_getStyleEnqueuer()->addLessVariable( $styleName, 'wpadmin_colors_2',            '#0074a2');
			$this->_getStyleEnqueuer()->addLessVariable( $styleName, 'wpadmin_colors_3',            '#2ea2cc');
			$this->_getStyleEnqueuer()->addLessVariable( $styleName, 'wpadmin_icon_colors_base',    '#999');
			$this->_getStyleEnqueuer()->addLessVariable( $styleName, 'wpadmin_icon_colors_focus',   '#2ea2cc');
			$this->_getStyleEnqueuer()->addLessVariable( $styleName, 'wpadmin_icon_colors_current', '#fff');
		}else{
			$colors = $_wp_admin_css_colors[ $admin_color ];
			$this->_getStyleEnqueuer()->addLessVariable( $styleName, 'wpadmin_colors_0',            $colors->colors['0']);
			$this->_getStyleEnqueuer()->addLessVariable( $styleName, 'wpadmin_colors_1',            $colors->colors['1']);
			$this->_getStyleEnqueuer()->addLessVariable( $styleName, 'wpadmin_colors_2',            $colors->colors['2']);
			$this->_getStyleEnqueuer()->addLessVariable( $styleName, 'wpadmin_colors_3',            $colors->colors['3']);
			$this->_getStyleEnqueuer()->addLessVariable( $styleName, 'wpadmin_icon_colors_base',    $colors->icon_colors['base']);
			$this->_getStyleEnqueuer()->addLessVariable( $styleName, 'wpadmin_icon_colors_focus',   $colors->icon_colors['focus']);
			$this->_getStyleEnqueuer()->addLessVariable( $styleName, 'wpadmin_icon_colors_current', $colors->icon_colors['current']);
		}
	}


	/**
	 * @return ffScriptEnqueuer
	 */
	public function getScriptEnqueuer() {
		return $this->_getScriptEnqueuer();
	}

	/**
	 * @return ffStyleEnqueuer
	 */
	public function getStyleEnqueuer() {
		return $this->_getStyleEnqueuer();
	}

	/**
	 * @return ffScriptEnqueuer
	 */
	protected function _getScriptEnqueuer() {
		return $this->_scriptEnqueuer;
	}

	/**
	 * @param ffScriptEnqueuer $scriptEnqueuer
	 */
	protected function _setScriptEnqueuer($scriptEnqueuer) {
		$this->_scriptEnqueuer = $scriptEnqueuer;
		return $this;
	}

	/**
	 *
	 * @return ffStyleEnqueuer
	 */
	protected function _getStyleEnqueuer() {
		return $this->_styleEnqueuer;
	}

	/**
	 *
	 * @param ffStyleEnqueuer $_styleEnqueuer
	 */
	protected function _setStyleEnqueuer(ffStyleEnqueuer $_styleEnqueuer) {
		$this->_styleEnqueuer = $_styleEnqueuer;
		return $this;
	}

	/**
	 * @return ffWPLayer instance of ffWPLayer
	 */
	protected function _getWPlayer() {
		return $this->_WPLayer;
	}

	/**
	 * @param ffWPLayer $_WPLayer
	 * @return ffLessWPOptions_Factory caller instance of ffLessWPOptions_Factory
	 */
	protected function _setWPlayer(ffWPLayer $WPLayer) {
		$this->_WPLayer = $WPLayer;
		return $this;
	}

}