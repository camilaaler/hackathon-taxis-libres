<?php

/**
 * Automatically load wished classes. Replacing SPL_AUTOLOAD which we should
 * not use due to compatibility issues.
 * 
 * @author FRESHFACE
 * @since 0.1
 *
 */

class ffClassLoader {
/******************************************************************************/
/* VARIABLES AND CONSTANTS
/******************************************************************************/
    /**
     * @var ffFileSystem
     */
    private $_fileSystem = null;

	/**
	 * array[ className ] = classPath
	 */
	private $_classNameToPathMap = array();
	
	/**
	 * 
	 * array[] = loadedClass
	 */
	private $_loadedClasses = array();

    private $_themeClasses = array();
/******************************************************************************/
/* CONSTRUCT AND PUBLIC FUNCTIONS
/******************************************************************************/
	public function __construct() {
		$this->_initFrameworkClasses();
		if( function_exists('spl_autoload_register') ) {
			spl_autoload_register( array( $this, 'splAutoload') );
		}
	}

	public function splAutoload( $className ) {
		if( $this->classRegistered( $className ) ) {
			$this->loadClass( $className );
		}
	}

    public function setFileSystem( ffFileSystem $fileSystem ) {
        $this->_fileSystem = $fileSystem;
    }
	
	public function classRegistered( $className ) {
		return isset( $this->_classNameToPathMap[ $className ] );
	}



    public function getClassPath( $className ) {
        if( $this->classRegistered( $className ) ) {

            if( in_array( $className, $this->_themeClasses ) ) {
                $path = $this->_getFileSystem()->locateFileInChildTheme( $this->_classNameToPathMap[ $className ] );
            } else {
                $path = $this->_classNameToPathMap[ $className ];
            }
            return $path;
        } else {
            return null;
        }
    }

    public function getClassUrl( $className ) {
        $classPath = $this->getClassPath( $className );
        if( $classPath == null ) {
            return null;
        }



        return $this->_getFileSystem()->getUrlFromPath( $classPath );
    }
	
	public function classExists( $className ) {
		return class_exists( $className );
	}
	
	public function loadClass( $name ) {
		if( class_exists( $name ) ) {
			return true;
		}
		if( !in_array( $name, $this->_loadedClasses ) ) {
			if( !isset( $this->_classNameToPathMap[ $name ] ) ) {
				throw new Exception('ffClassLoader->loadClass trying to load class "'.$name.'" which has not been registered yet');
			}

            if( in_array( $name, $this->_themeClasses ) ) {
               $path = $this->_getFileSystem()->locateFileInChildTheme( $this->_classNameToPathMap[ $name ] );
            } else {
                $path = $this->_classNameToPathMap[ $name ];
            }
			

			$this->_requireClass( $path );
			$this->_loadedClasses[] = $name;
		}
	}

	public function getLoadedClasses() {
		return $this->_loadedClasses;
	}

    public function isThemeClass( $className ) {
        return in_array( $className, $this->_themeClasses );
    }
	
	public function addClass( $name, $path ) {
		$this->_classNameToPathMap[ $name ] = $path;
	}

    public function addClassFramework( $name, $path ) {
        $this->addClass( $name, FF_FRAMEWORK_DIR.$path);
    }

    public function addClassTheme( $name, $path ) {
        $this->_themeClasses[] = $name;
        $this->_classNameToPathMap[ $name ] = $path;
    }
	
	public function loadConstants() {
		$this->loadClass('ffConstActions');
		$this->loadClass('ffConstThemeViews');
		$this->loadClass('ffConstHTMLClasses');
		$this->loadClass('ffConstQuery');
        $this->loadClass('ffConstCache');

	}
/******************************************************************************/
/* PRIVATE FUNCTIONS
/******************************************************************************/

    private function _getFileSystem() {
        return $this->_fileSystem;
    }

	############################################################################
	# CORE
	############################################################################
	private function _addCoreFiles() {
		$this->addClass( 'ffBasicObject', FF_FRAMEWORK_DIR.'/framework/core/class.ffBasicObject.php');
		$this->addClass( 'ffContainer', FF_FRAMEWORK_DIR.'/framework/core/class.ffContainer.php');
		$this->addClass( 'ffFramework', FF_FRAMEWORK_DIR.'/framework/core/class.ffFramework.php');
		
		$this->addClass( 'ffPluginLoader_Factory', FF_FRAMEWORK_DIR.'/framework/core/factories/class.ffPluginLoader_Factory.php');
		$this->addClass( 'ffPluginLoader', FF_FRAMEWORK_DIR.'/framework/core/class.ffPluginLoader.php');
		$this->addClass( 'ffPluginIdentificator', FF_FRAMEWORK_DIR.'/framework/core/class.ffPluginIdentificator.php');
		$this->addClass( 'ffFactoryAbstract', FF_FRAMEWORK_DIR.'/framework/core/class.ffFactoryAbstract.php');
		$this->addClass( 'ffFactoryCenterAbstract', FF_FRAMEWORK_DIR.'/framework/core/class.ffFactoryCenterAbstract.php');
		$this->addClass( 'ffPluginAbstract', FF_FRAMEWORK_DIR.'/framework/core/class.ffPluginAbstract.php');
		$this->addClass( 'ffPluginContainerAbstract', FF_FRAMEWORK_DIR.'/framework/core/class.ffPluginContainerAbstract.php');
		
		$this->addClass( 'ffThemeAbstract', FF_FRAMEWORK_DIR.'/framework/core/class.ffThemeAbstract.php');
		$this->addClass( 'ffThemeContainerAbstract', FF_FRAMEWORK_DIR.'/framework/core/class.ffThemeContainerAbstract.php');
		$this->addClass( 'ffThemeIdentificator', FF_FRAMEWORK_DIR.'/framework/core/class.ffThemeIdentificator.php');
		
		$this->addClass( 'ffThemeLoader', FF_FRAMEWORK_DIR.'/framework/core/class.ffThemeLoader.php');
		
		$this->addClass( 'ffRequest', FF_FRAMEWORK_DIR.'/framework/core/class.ffRequest.php');
		$this->addClass( 'ffRequest_Factory', FF_FRAMEWORK_DIR.'/framework/core/factories/class.ffRequest_Factory.php');
		$this->addClass( 'ffLibManager', FF_FRAMEWORK_DIR.'/framework/core/class.ffLibManager.php');
		$this->addClass( 'ffColor', FF_FRAMEWORK_DIR.'/framework/core/class.ffColor.php');
		$this->addClass( 'ffException', FF_FRAMEWORK_DIR.'/framework/core/class.ffException.php');
		$this->addClass( 'ffCiphers', FF_FRAMEWORK_DIR.'/framework/core/class.ffCiphers.php');
		$this->addClass( 'ffAdminNotices', FF_FRAMEWORK_DIR.'/framework/core/class.ffAdminNotices.php');

		$this->addClass( 'ffArkAcademyHelper', FF_FRAMEWORK_DIR.'/framework/core/class.ffArkAcademyHelper.php');

		$this->addClass( 'ffCollection', FF_FRAMEWORK_DIR.'/framework/core/class.ffCollection.php');
		$this->addClass( 'ffCollectionAdvanced', FF_FRAMEWORK_DIR.'/framework/core/class.ffCollectionAdvanced.php');
		$this->addClass( 'ffStdClass', FF_FRAMEWORK_DIR.'/framework/core/class.ffStdClass.php');
		$this->addClass( 'ffDataHolder', FF_FRAMEWORK_DIR.'/framework/core/class.ffDataHolder.php');
		$this->addClass( 'ffEnvironment', FF_FRAMEWORK_DIR.'/framework/core/class.ffEnvironment.php');

		$this->addClass( 'ffEnvatoApi', FF_FRAMEWORK_DIR.'/framework/core/class.ffEnvatoApi.php');
		$this->addClass( 'ffFastSpringApi', FF_FRAMEWORK_DIR.'/framework/core/class.ffFastSpringApi.php');
		$this->addClass( 'ffWPUser', FF_FRAMEWORK_DIR.'/framework/core/class.ffWPUser.php');
        $this->addClass( 'ffEnvatoApiModern', FF_FRAMEWORK_DIR.'/framework/core/class.ffEnvatoApiModern.php');
		$this->addClass( 'ffLicensing', FF_FRAMEWORK_DIR.'/framework/core/class.ffLicensing.php');


		$this->addClass( 'ffUrlRewriter', FF_FRAMEWORK_DIR.'/framework/core/class.ffUrlRewriter.php');
		$this->addClass( 'ffWPQueryHelper', FF_FRAMEWORK_DIR.'/framework/core/class.ffWPQueryHelper.php');

		$this->addClass( 'ffWPConfigEditor', FF_FRAMEWORK_DIR.'/framework/core/class.ffWPConfigEditor.php');
        $this->addClass( 'ffHttpAction', FF_FRAMEWORK_DIR.'/framework/core/class.ffHttpAction.php');
        $this->addClass( 'ffCompatibilityTester', FF_FRAMEWORK_DIR.'/framework/core/class.ffCompatibilityTester.php');



        ########################################################################
		# WP LAYER
		########################################################################
		$this->addClass( 'ffWPLayer', FF_FRAMEWORK_DIR.'/framework/core/wplayer/class.ffWPLayer.php');
		$this->addClass( 'ffAssetsSourceHolder', FF_FRAMEWORK_DIR.'/framework/core/wplayer/class.ffAssetsSourceHolder.php');
		$this->addClass( 'ffHookManager', FF_FRAMEWORK_DIR.'/framework/core/wplayer/class.ffHookManager.php');
		$this->addClass( 'ffWPMLBridge', FF_FRAMEWORK_DIR.'/framework/core/wplayer/class.ffWPMLBridge.php');
		
		
		########################################################################
		# AJAX
		########################################################################
		$this->addClass( 'ffAjaxRequestFactory', FF_FRAMEWORK_DIR.'/framework/core/ajax/factories/class.ffAjaxRequest_Factory.php');
		$this->addClass( 'ffAjaxDispatcher', FF_FRAMEWORK_DIR.'/framework/core/ajax/class.ffAjaxDispatcher.php');
		$this->addClass( 'ffAjaxRequest', FF_FRAMEWORK_DIR.'/framework/core/ajax/class.ffAjaxRequest.php');
	}

	############################################################################
	# ASSETS
	############################################################################
	private function _addAssetsFiles() {
		$this->addClass( 'ffAssetsIncludingFactory', FF_FRAMEWORK_DIR.'/framework/assetsIncluding/factories/class.ffAssetsIncludingFactory.php');
		
		$this->addClass( 'ffScript', FF_FRAMEWORK_DIR.'/framework/assetsIncluding/class.ffScript.php');
		$this->addClass( 'ffScript_Factory', FF_FRAMEWORK_DIR.'/framework/assetsIncluding/factories/class.ffScript_Factory.php');

		$this->addClass( 'ffScriptEnqueuer', FF_FRAMEWORK_DIR.'/framework/assetsIncluding/class.ffScriptEnqueuer.php');
		$this->addClass( 'ffScriptEnqueuerMinification', FF_FRAMEWORK_DIR.'/framework/assetsIncluding/class.ffScriptEnqueuerMinification.php');
		$this->addClass( 'ffScriptEnqueuer_Factory', FF_FRAMEWORK_DIR.'/framework/assetsIncluding/factories/class.ffScriptEnqueuer_Factory.php');
		$this->addClass( 'ffFrameworkScriptLoader', FF_FRAMEWORK_DIR.'/framework/assetsIncluding/class.ffFrameworkScriptLoader.php');

		$this->addClass( 'ffStyle', FF_FRAMEWORK_DIR.'/framework/assetsIncluding/class.ffStyle.php');
		$this->addClass( 'ffStyle_Factory', FF_FRAMEWORK_DIR.'/framework/assetsIncluding/factories/class.ffStyle_Factory.php');
		$this->addClass( 'ffStyleEnqueuer', FF_FRAMEWORK_DIR.'/framework/assetsIncluding/class.ffStyleEnqueuer.php');
		$this->addClass( 'ffStyleEnqueuerMinification', FF_FRAMEWORK_DIR.'/framework/assetsIncluding/class.ffStyleEnqueuerMinification.php');
		$this->addClass( 'ffMinificator', FF_FRAMEWORK_DIR.'/framework/assetsIncluding/class.ffMinificator.php');

		$this->addClass( 'ffLessScssCompiler', FF_FRAMEWORK_DIR.'/framework/assetsIncluding/lessScssCompiler/class.ffLessScssCompiler.php');
		$this->addClass( 'ffVariableTransporter', FF_FRAMEWORK_DIR.'/framework/assetsIncluding/lessScssCompiler/class.ffVariableTransporter.php');

		$this->addClass( 'ffLessWPOptions_Factory', FF_FRAMEWORK_DIR.'/framework/assetsIncluding/lessWPOptions/class.ffLessWPOptions_Factory.php');
		$this->addClass( 'ffLessWPOptionsManager', FF_FRAMEWORK_DIR.'/framework/assetsIncluding/lessWPOptions/class.ffLessWPOptionsManager.php');
		
		
		$this->addClass( 'ffLessManager', FF_FRAMEWORK_DIR.'/framework/assetsIncluding/lessScssCompiler/class.ffLessManager.php');
		$this->addClass( 'ffLessVariableParser', FF_FRAMEWORK_DIR.'/framework/assetsIncluding/lessScssCompiler/class.ffLessVariableParser.php');
		$this->addClass( 'ffLessSystemColorLibraryManager', FF_FRAMEWORK_DIR.'/framework/assetsIncluding/lessScssCompiler/class.ffLessSystemColorLibraryManager.php');
		$this->addClass( 'ffLessSystemColorLibrary', FF_FRAMEWORK_DIR.'/framework/assetsIncluding/lessScssCompiler/class.ffLessSystemColorLibrary.php');
		$this->addClass( 'ffLessSystemColorLibraryBackend', FF_FRAMEWORK_DIR.'/framework/assetsIncluding/lessScssCompiler/class.ffLessSystemColorLibraryBackend.php');
		$this->addClass( 'ffLessSystemColorLibraryDefault', FF_FRAMEWORK_DIR.'/framework/assetsIncluding/lessScssCompiler/class.ffLessSystemColorLibraryDefault.php');
		$this->addClass( 'ffLessUserSelectedColorsDataStorage', FF_FRAMEWORK_DIR.'/framework/assetsIncluding/lessScssCompiler/class.ffLessUserSelectedColorsDataStorage.php');
		
		$this->addClass( 'ffLessColorLibrary', FF_FRAMEWORK_DIR.'/framework/assetsIncluding/lessScssCompiler/class.ffLessColorLibrary.php');
		
		$this->addClass( 'ffOneLessFile', FF_FRAMEWORK_DIR.'/framework/assetsIncluding/lessScssCompiler/class.ffOneLessFile.php');
		$this->addClass( 'ffOneLessFileFactory', FF_FRAMEWORK_DIR.'/framework/assetsIncluding/lessScssCompiler/factories/class.ffOneLessFileFactory.php');
		
	}

	############################################################################
	# FILE SYSTEM
	############################################################################
	private function _addFileSystemFiles() {
		$this->addClass( 'ffFileManager_Factory', FF_FRAMEWORK_DIR.'/framework/fileSystem/factories/class.ffFileManager_Factory.php');
		$this->addClass( 'ffFileManager', FF_FRAMEWORK_DIR.'/framework/fileSystem/class.ffFileManager.php');
		$this->addClass( 'ffFileSystem', FF_FRAMEWORK_DIR.'/framework/fileSystem/class.ffFileSystem.php');
		$this->addClass( 'ffFileSystem_Factory', FF_FRAMEWORK_DIR.'/framework/fileSystem/factories/class.ffFileSystem_Factory.php');
		$this->addClass( 'ffHttp', FF_FRAMEWORK_DIR.'/framework/fileSystem/class.ffHttp.php');
		$this->addClass( 'ffFtp', FF_FRAMEWORK_DIR.'/framework/fileSystem/class.ffFtp.php');
		$this->addClass( 'ffPluginInstaller', FF_FRAMEWORK_DIR.'/framework/fileSystem/class.ffPluginInstaller.php');
		$this->addClass( 'ffHtaccess', FF_FRAMEWORK_DIR.'/framework/fileSystem/class.ffHtaccess.php');
	}

	############################################################################
	# OPTIONS
	############################################################################
	private function _addOptionsFiles() {
		########################################################################
		# dataHolders
		########################################################################
		$this->addClass( 'ffIOneDataNode', FF_FRAMEWORK_DIR.'/framework/options/dataHolders/class.ffIOneDataNode.php');
		$this->addClass( 'ffOneOption', FF_FRAMEWORK_DIR.'/framework/options/dataHolders/class.ffOneOption.php');
		$this->addClass( 'ffOneElement', FF_FRAMEWORK_DIR.'/framework/options/dataHolders/class.ffOneElement.php');
		$this->addClass( 'ffOneSection', FF_FRAMEWORK_DIR.'/framework/options/dataHolders/class.ffOneSection.php');
		$this->addClass( 'ffOneStructure', FF_FRAMEWORK_DIR.'/framework/options/dataHolders/class.ffOneStructure.php');
		$this->addClass( 'ffOptionsStructureHelper', FF_FRAMEWORK_DIR.'/framework/options/dataHolders/class.ffOptionsStructureHelper.php');


		$this->addClass( 'ffIOptionsHolder', FF_FRAMEWORK_DIR.'/framework/options/dataHolders/class.ffIOptionsHolder.php');
		$this->addClass( 'ffOptionsHolder', FF_FRAMEWORK_DIR.'/framework/options/dataHolders/class.ffOptionsHolder.php');
        $this->addClass( 'ffOptionsHolder_CachingFacade', FF_FRAMEWORK_DIR.'/framework/options/dataHolders/class.ffOptionsHolder_CachingFacade.php');
		
		########################################################################
		# factories
		########################################################################
		$this->addClass( 'ffOneOption_Factory', FF_FRAMEWORK_DIR.'/framework/options/factories/class.ffOneOption_Factory.php');
		$this->addClass( 'ffOneSection_Factory', FF_FRAMEWORK_DIR.'/framework/options/factories/class.ffOneSection_Factory.php');
		$this->addClass( 'ffOneStructure_Factory', FF_FRAMEWORK_DIR.'/framework/options/factories/class.ffOneStructure_Factory.php');
		$this->addClass( 'ffOptions_Factory', FF_FRAMEWORK_DIR.'/framework/options/factories/class.ffOptions_Factory.php');
		$this->addClass( 'ffOptionsQuery_Factory', FF_FRAMEWORK_DIR.'/framework/options/factories/class.ffOptionsQuery_Factory.php');
		$this->addClass( 'ffOptionsArrayConvertor_Factory', FF_FRAMEWORK_DIR.'/framework/options/factories/class.ffOptionsArrayConvertor_Factory.php');
		$this->addClass( 'ffOptionsHolder_Factory', FF_FRAMEWORK_DIR.'/framework/options/factories/class.ffOptionsHolder_Factory.php');
		$this->addClass( 'ffOptionsPrinterComponent_Factory', FF_FRAMEWORK_DIR.'/framework/options/factories/class.ffOptionsPrinterComponent_Factory.php');
		
		
		########################################################################
		# printerComponent
		########################################################################
		$this->addClass( 'ffOptionsPrinterElementsAndComponentsBasic', FF_FRAMEWORK_DIR.'/framework/options/printerComponent/class.ffOptionsPrinterElementsAndComponentsBasic.php');
		$this->addClass( 'ffOptionsPrinterElementsBasic', FF_FRAMEWORK_DIR.'/framework/options/printerComponent/class.ffOptionsPrinterElementsBasic.php');
		$this->addClass( 'ffOptionsPrinterComponentsBasic', FF_FRAMEWORK_DIR.'/framework/options/printerComponent/class.ffOptionsPrinterComponentsBasic.php');
		
		$this->addClass( 'ffOptionsPrinterComponents', FF_FRAMEWORK_DIR.'/framework/options/printerComponent/class.ffOptionsPrinterComponents.php');
		$this->addClass( 'ffOptionsPrinterElements', FF_FRAMEWORK_DIR.'/framework/options/printerComponent/class.ffOptionsPrinterElements.php');
		
		
		########################################################################
		# walkers
		########################################################################
		$this->addClass( 'ffOptionsWalker', FF_FRAMEWORK_DIR.'/framework/options/walkers/class.ffOptionsWalker.php');
		$this->addClass( 'ffOptionsQuery', FF_FRAMEWORK_DIR.'/framework/options/walkers/class.ffOptionsQuery.php');
        $this->addClass( 'ffOptionsQueryDynamic', FF_FRAMEWORK_DIR.'/framework/options/walkers/class.ffOptionsQueryDynamic.php');
		$this->addClass( 'ffOptionsArrayConvertor', FF_FRAMEWORK_DIR.'/framework/options/walkers/class.ffOptionsArrayConvertor.php');
		$this->addClass( 'ffOptionsPostReader', FF_FRAMEWORK_DIR.'/framework/options/walkers/class.ffOptionsPostReader.php');

		$this->addClass( 'ffOptionsPrinter', FF_FRAMEWORK_DIR.'/framework/options/walkers/printers/class.ffOptionsPrinter.php');
		$this->addClass( 'ffOptionsPrinter2', FF_FRAMEWORK_DIR.'/framework/options/walkers/printers/class.ffOptionsPrinter2.php');
		$this->addClass( 'ffOptionsPrinterBoxed', FF_FRAMEWORK_DIR.'/framework/options/walkers/printers/class.ffOptionsPrinterBoxed.php');
        $this->addClass( 'ffOptionsPrinterJavaScriptConvertor', FF_FRAMEWORK_DIR.'/framework/options/walkers/printers/class.ffOptionsPrinterJavaScriptConvertor.php');
        $this->addClass( 'ffOptionsPrinterJSONConvertor', FF_FRAMEWORK_DIR.'/framework/options/walkers/printers/class.ffOptionsPrinterJSONConvertor.php');
		$this->addClass( 'ffOptionsPrinterLogic', FF_FRAMEWORK_DIR.'/framework/options/walkers/printers/class.ffOptionsPrinterLogic.php');
		$this->addClass( 'ffOptionsPrinterDataBoxGenerator', FF_FRAMEWORK_DIR.'/framework/options/walkers/printers/class.ffOptionsPrinterDataBoxGenerator.php');
		
		########################################################################
		# optionholders
		########################################################################
		
		
	}
	
	############################################################################
	# GRAPHIC
	############################################################################
	private function _addGraphicFiles() {
		$this->addClass( 'ffGraphicFactory', FF_FRAMEWORK_DIR.'/framework/graphic/factories/class.ffGraphicFactory.php');

		$this->addClass( 'ffImageInformator', FF_FRAMEWORK_DIR.'/framework/graphic/image/class.ffImageInformator.php');
        $this->addClass( 'ffImageHttpManager', FF_FRAMEWORK_DIR.'/framework/graphic/image/class.ffImageHttpManager.php');
        $this->addClass( 'ffImageServingObject', FF_FRAMEWORK_DIR.'/framework/graphic/image/class.ffImageServingObject.php');


	}
	
	############################################################################
	# DATA STORAGE
	############################################################################
	private function _addDataStorageFiles() {
		$this->addClass( 'ffDataStorage_Factory', FF_FRAMEWORK_DIR.'/framework/dataStorage/factories/class.ffDataStorage_Factory.php');
		$this->addClass( 'ffIDataStorage', FF_FRAMEWORK_DIR.'/framework/dataStorage/class.ffIDataStorage.php');
		$this->addClass( 'ffDataStorage', FF_FRAMEWORK_DIR.'/framework/dataStorage/class.ffDataStorage.php');
		$this->addClass( 'ffDataStorage_WPPostMetas', FF_FRAMEWORK_DIR.'/framework/dataStorage/class.ffDataStorage_WPPostMetas.php');
		$this->addClass( 'ffDataStorage_WPPostMetas_NamespaceFacade', FF_FRAMEWORK_DIR.'/framework/dataStorage/class.ffDataStorage_WPPostMetas_NamespaceFacade.php');
		$this->addClass( 'ffDataStorage_WPOptions', FF_FRAMEWORK_DIR.'/framework/dataStorage/class.ffDataStorage_WPOptions.php');
		$this->addClass( 'ffDataStorage_WPOptions_NamespaceFacade', FF_FRAMEWORK_DIR.'/framework/dataStorage/class.ffDataStorage_WPOptions_NamespaceFacade.php');

		$this->addClass( 'ffDataStorage_WPSiteOptions', FF_FRAMEWORK_DIR.'/framework/dataStorage/class.ffDataStorage_WPSiteOptions.php');
		$this->addClass( 'ffDataStorage_WPSiteOptions_NamespaceFacade', FF_FRAMEWORK_DIR.'/framework/dataStorage/class.ffDataStorage_WPSiteOptions_NamespaceFacade.php');


		
		$this->addClass( 'ffDataStorage_Cache', FF_FRAMEWORK_DIR.'/framework/dataStorage/class.ffDataStorage_Cache.php');
        $this->addClass( 'ffDataStorage_Theme', FF_FRAMEWORK_DIR.'/framework/dataStorage/class.ffDataStorage_Theme.php');
		
		
		
		########################################################################
		# DataStorage OptionsPost
		########################################################################
		$this->addClass( 'ffDataStorage_OptionsPostTypeRegistrator', FF_FRAMEWORK_DIR.'/framework/dataStorage/dataStorageOptionsPost/class.ffDataStorage_OptionsPostTypeRegistrator.php');
		$this->addClass( 'ffDataStorage_OptionsPostType', FF_FRAMEWORK_DIR.'/framework/dataStorage/dataStorageOptionsPost/class.ffDataStorage_OptionsPostType.php');
		$this->addClass( 'ffDataStorage_OptionsPostType_NamespaceFacade', FF_FRAMEWORK_DIR.'/framework/dataStorage/dataStorageOptionsPost/class.ffDataStorage_OptionsPostType_NamespaceFacade.php');

		########################################################################
		# Options Collection
		########################################################################
		$this->addClass( 'ffArrayQuery', FF_FRAMEWORK_DIR.'/framework/dataStorage/collections/class.ffArrayQuery.php');
		$this->addClass( 'ffArrayQueryFactory', FF_FRAMEWORK_DIR.'/framework/dataStorage/collections/class.ffArrayQueryFactory.php');
		$this->addClass( 'ffOptionsCollection', FF_FRAMEWORK_DIR.'/framework/dataStorage/collections/class.ffOptionsCollection.php');

	}
	
	############################################################################
	# LIBS
	############################################################################
	private function _addLibsFiles() {
		########################################################################
		# TWITTER
		########################################################################
		$this->addClass( 'ffOptionsHolder_Twitter', FF_FRAMEWORK_DIR.'/framework/lib/twitter/class.ffOptionsHolder_Twitter.php');
		$this->addClass( 'ffLib_TwitterFeeder', FF_FRAMEWORK_DIR.'/framework/lib/twitter/class.ffLib_TwitterFeeder.php');
		$this->addClass( 'ffLib_TwitterFeeder_OAuthFactory', FF_FRAMEWORK_DIR.'/framework/lib/twitter/factories/class.ffLib_TwitterFeeder_OAuthFactory.php');
		$this->addClass( 'extTwitterOAuth', FF_FRAMEWORK_DIR.'/framework/lib/twitter/class.extTwitterOAuth.php');
		$this->addClass( 'ffLib_TwitterFeeder_OneTweet', FF_FRAMEWORK_DIR.'/framework/lib/twitter/class.ffLib_TwitterFeeder_OneTweet.php');
		$this->addClass( 'ffLib_TwitterFeeder_OneTweet_Factory', FF_FRAMEWORK_DIR.'/framework/lib/twitter/factories/class.ffLib_TwitterFeeder_OneTweet_Factory.php');
		$this->addClass( 'ffLib_TwitterFeeder_TweetsCollection', FF_FRAMEWORK_DIR.'/framework/lib/twitter/class.ffLib_TwitterFeeder_TweetsCollection.php');
		$this->addClass( 'ffLib_TwitterFeeder_TweetsCollection_Factory', FF_FRAMEWORK_DIR.'/framework/lib/twitter/factories/class.ffLib_TwitterFeeder_TweetsCollection_Factory.php');
		
		########################################################################
		# CONDITIONAL LOGIC
		########################################################################
		$this->addClass( 'ffOptionsHolderConditionalLogic', FF_FRAMEWORK_DIR.'/framework/lib/conditionalLogic/class.ffOptionsHolderConditionalLogic.php');
		$this->addClass( 'ffConditionalLogicEvaluator', FF_FRAMEWORK_DIR.'/framework/lib/conditionalLogic/class.ffConditionalLogicEvaluator.php');
		$this->addClass( 'ffConditionalLogicConstants', FF_FRAMEWORK_DIR.'/framework/lib/conditionalLogic/class.ffConditionalLogicConstants.php');
		
		########################################################################
		# colorLibrary
		########################################################################
		$this->addClass( 'ffUserColorLibraryItemFactory', FF_FRAMEWORK_DIR.'/framework/lib/colorLibrary/factories/class.ffUserColorLibraryItemFactory.php');
		$this->addClass( 'ffUserColorLibrary', FF_FRAMEWORK_DIR.'/framework/lib/colorLibrary/class.ffUserColorLibrary.php');
		$this->addClass( 'ffUserColorLibraryItem', FF_FRAMEWORK_DIR.'/framework/lib/colorLibrary/class.ffUserColorLibraryItem.php');

        ########################################################################
		# BREADCRUMBS
		########################################################################
		$this->addClass( 'ffBreadcrumbs', FF_FRAMEWORK_DIR.'/framework/lib/breadcrumbs/class.ffBreadcrumbs.php');
		$this->addClass( 'ffBreadcrumbsCollection', FF_FRAMEWORK_DIR.'/framework/lib/breadcrumbs/class.ffBreadcrumbsCollection.php');
		$this->addClass( 'ffOneBreadcrumb', FF_FRAMEWORK_DIR.'/framework/lib/breadcrumbs/class.ffOneBreadcrumb.php');

        $this->addClass( 'ffBreadcrumbsCollectionFactory', FF_FRAMEWORK_DIR.'/framework/lib/breadcrumbs/factories/class.ffBreadcrumbsCollectionFactory.php');
		$this->addClass( 'ffOneBreadcrumbFactory', FF_FRAMEWORK_DIR.'/framework/lib/breadcrumbs/factories/class.ffOneBreadcrumbFactory.php');

        ########################################################################
		# JSTREE
		########################################################################
        $this->addClass( 'ffJsTreeDataBuilder', FF_FRAMEWORK_DIR.'/framework/lib/jstree/class.ffJsTreeDataBuilder.php');
	}

    private function _addHelperFiles() {
        $this->addClassFramework('ffHelpersFactory', '/framework/helpers/factories/class.ffHelpersFactory.php');
        $this->addClassFramework('ffStringHelper', '/framework/helpers/class.ffStringHelper.php');
        $this->addClassFramework('ffTableHelper', '/framework/helpers/class.ffTableHelper.php');
        $this->addClassFramework('ffAttrHelper', '/framework/helpers/class.ffAttrHelper.php');
	    $this->addClassFramework('ffMultiAttrHelper', '/framework/helpers/class.ffMultiAttrHelper.php');
	    $this->addClassFramework('ffElementHelper', '/framework/helpers/class.ffElementHelper.php');
    }

	############################################################################
	# COMPONENT
	############################################################################	
	private function _addComponentFiles() {
		$this->addClass( 'ffComponentAbstract', FF_FRAMEWORK_DIR.'/framework/components/class.ffComponentAbstract.php');
		$this->addClass( 'ffWidgetDecoratorAbstract', FF_FRAMEWORK_DIR.'/framework/components/class.ffWidgetDecoratorAbstract.php');
		
		
		$this->addClass( 'ffComponent_Factory', FF_FRAMEWORK_DIR.'/framework/components/factories/class.ffComponent_Factory.php');
		
		$this->addClass( 'ffWidgetManager', FF_FRAMEWORK_DIR.'/framework/components/class.ffWidgetManager.php');
	}
	
	############################################################################
	# ADMIN SCREEN FILE
	############################################################################
	private function _addAdminScreenFiles() {
		$this->addClass( 'ffMenuFactory', FF_FRAMEWORK_DIR.'/framework/adminScreens/factories/class.ffMenuFactory.php');
		$this->addClass( 'ffMenu', FF_FRAMEWORK_DIR.'/framework/adminScreens/class.ffMenu.php');
		$this->addClass( 'ffMenuManager', FF_FRAMEWORK_DIR.'/framework/adminScreens/class.ffMenuManager.php');
		$this->addClass( 'ffAdminScreenManager', FF_FRAMEWORK_DIR.'/framework/adminScreens/class.ffAdminScreenManager.php');
		$this->addClass( 'ffAdminScreen', FF_FRAMEWORK_DIR.'/framework/adminScreens/class.ffAdminScreen.php');
		$this->addClass( 'ffAdminScreenView', FF_FRAMEWORK_DIR.'/framework/adminScreens/class.ffAdminScreenView.php');
		$this->addClass( 'ffIAdminScreen', FF_FRAMEWORK_DIR.'/framework/adminScreens/interfaces/class.ffIAdminScreen.php');
		$this->addClass( 'ffIAdminScreenView', FF_FRAMEWORK_DIR.'/framework/adminScreens/interfaces/class.ffIAdminScreenView.php');
		$this->addClass( 'ffAdminScreenFactory', FF_FRAMEWORK_DIR.'/framework/adminScreens/factories/class.ffAdminScreenFactory.php');
		$this->addClass( 'ffAdminScreenViewFactory', FF_FRAMEWORK_DIR.'/framework/adminScreens/factories/class.ffAdminScreenViewFactory.php');
		
		$this->addClass( 'ffAdminScreenAjax', FF_FRAMEWORK_DIR.'/framework/adminScreens/class.ffAdminScreenAjax.php');
		$this->addClass( 'ffAdminScreenAjaxFactory', FF_FRAMEWORK_DIR.'/framework/adminScreens/factories/class.ffAdminScreenAjaxFactory.php');
		
		$this->addClass( 'ffModalWindowBasicObject', FF_FRAMEWORK_DIR.'/framework/adminScreens/modalWindow/class.ffModalWindowBasicObject.php');
		$this->addClass( 'ffModalWindowFactory', FF_FRAMEWORK_DIR.'/framework/adminScreens/modalWindow/factories/class.ffModalWindow_Factory.php');
		$this->addClass( 'ffModalWindow', FF_FRAMEWORK_DIR.'/framework/adminScreens/modalWindow/class.ffModalWindow.php');
		$this->addClass( 'ffModalWindowView', FF_FRAMEWORK_DIR.'/framework/adminScreens/modalWindow/class.ffModalWindowView.php');
		$this->addClass( 'ffModalWindowManager', FF_FRAMEWORK_DIR.'/framework/adminScreens/modalWindow/class.ffModalWindowManager.php');
		$this->addClass( 'ffModalWindowAjaxManager', FF_FRAMEWORK_DIR.'/framework/adminScreens/modalWindow/class.ffModalWindowAjaxManager.php');
	
		// CONDITIONS
		$this->addClass( 'ffModalWindowManagerConditions', FF_FRAMEWORK_DIR.'/framework/adminScreens/modalWindow/conditions/class.ffModalWindowManagerConditions.php');
		$this->addClass( 'ffModalWindowConditions', FF_FRAMEWORK_DIR.'/framework/adminScreens/modalWindow/conditions/class.ffModalWindowConditions.php');
		$this->addClass( 'ffModalWindowConditionsViewDefault', FF_FRAMEWORK_DIR.'/framework/adminScreens/modalWindow/conditions/class.ffModalWindowConditionsViewDefault.php');
		//$this->addClass( 'ffModalWindowManagerPokus', FF_FRAMEWORK_DIR.'/framework/adminScreens/modalWindow/pokus/class.ffModalWindowManagerPokus.php');

		// LIBRARY COLOR PICKER
		$this->addClass( 'ffModalWindowManagerLibraryIconPicker', FF_FRAMEWORK_DIR.'/framework/adminScreens/modalWindow/libraries/icon/picker/class.ffModalWindowManagerLibraryIconPicker.php');
		$this->addClass( 'ffModalWindowLibraryIconPicker', FF_FRAMEWORK_DIR.'/framework/adminScreens/modalWindow/libraries/icon/picker/class.ffModalWindowLibraryIconPicker.php');
		$this->addClass( 'ffModalWindowLibraryIconPickerViewDefault', FF_FRAMEWORK_DIR.'/framework/adminScreens/modalWindow/libraries/icon/picker/class.ffModalWindowLibraryIconPickerViewDefault.php');
		$this->addClass( 'ffModalWindowLibraryIconPickerIconPreparator', FF_FRAMEWORK_DIR.'/framework/adminScreens/modalWindow/libraries/icon/picker/class.ffModalWindowLibraryIconPickerIconPreparator.php');

		// LIBRARY COLOR PICKER
		$this->addClass( 'ffModalWindowManagerLibraryColorPicker', FF_FRAMEWORK_DIR.'/framework/adminScreens/modalWindow/libraries/color/picker/class.ffModalWindowManagerLibraryColorPicker.php');
		$this->addClass( 'ffModalWindowLibraryColorPicker', FF_FRAMEWORK_DIR.'/framework/adminScreens/modalWindow/libraries/color/picker/class.ffModalWindowLibraryColorPicker.php');
		$this->addClass( 'ffModalWindowLibraryColorPickerViewDefault', FF_FRAMEWORK_DIR.'/framework/adminScreens/modalWindow/libraries/color/picker/class.ffModalWindowLibraryColorPickerViewDefault.php');
		$this->addClass( 'ffModalWindowLibraryColorPickerColorPreparator', FF_FRAMEWORK_DIR.'/framework/adminScreens/modalWindow/libraries/color/picker/class.ffModalWindowLibraryColorPickerColorPreparator.php');

		// LIBRARY COLOR EDITOR
		$this->addClass( 'ffModalWindowManagerLibraryColorEditor', FF_FRAMEWORK_DIR.'/framework/adminScreens/modalWindow/libraries/color/editor/class.ffModalWindowManagerLibraryColorEditor.php');
		$this->addClass( 'ffModalWindowLibraryColorEditor', FF_FRAMEWORK_DIR.'/framework/adminScreens/modalWindow/libraries/color/editor/class.ffModalWindowLibraryColorEditor.php');
		$this->addClass( 'ffModalWindowLibraryColorEditorViewDefault', FF_FRAMEWORK_DIR.'/framework/adminScreens/modalWindow/libraries/color/editor/class.ffModalWindowLibraryColorEditorViewDefault.php');

		// LIBRARY ADD GROUP
		$this->addClass( 'ffModalWindowManagerLibraryAddGroup', FF_FRAMEWORK_DIR.'/framework/adminScreens/modalWindow/libraries/color/addGroup/class.ffModalWindowManagerLibraryAddGroup.php');
		$this->addClass( 'ffModalWindowLibraryAddGroup', FF_FRAMEWORK_DIR.'/framework/adminScreens/modalWindow/libraries/color/addGroup/class.ffModalWindowLibraryAddGroup.php');
		$this->addClass( 'ffModalWindowLibraryAddGroupViewDefault', FF_FRAMEWORK_DIR.'/framework/adminScreens/modalWindow/libraries/color/addGroup/class.ffModalWindowLibraryAddGroupViewDefault.php');
		
		// LIBRARY DELETE GROUP
		$this->addClass( 'ffModalWindowManagerLibraryDeleteGroup', FF_FRAMEWORK_DIR.'/framework/adminScreens/modalWindow/libraries/color/deleteGroup/class.ffModalWindowManagerLibraryDeleteGroup.php');
		$this->addClass( 'ffModalWindowLibraryDeleteGroup', FF_FRAMEWORK_DIR.'/framework/adminScreens/modalWindow/libraries/color/deleteGroup/class.ffModalWindowLibraryDeleteGroup.php');
		$this->addClass( 'ffModalWindowLibraryDeleteGroupViewDefault', FF_FRAMEWORK_DIR.'/framework/adminScreens/modalWindow/libraries/color/deleteGroup/class.ffModalWindowLibraryDeleteGroupViewDefault.php');
		
		// LIBRARY SECTION PICKER
		$this->addClass( 'ffModalWindowManagerSectionPicker', FF_FRAMEWORK_DIR.'/framework/adminScreens/modalWindow/libraries/section/class.ffModalWindowManagerSectionPicker.php');
		$this->addClass( 'ffModalWindowSectionPicker', FF_FRAMEWORK_DIR.'/framework/adminScreens/modalWindow/libraries/section/class.ffModalWindowSectionPicker.php');
		$this->addClass( 'ffModalWindowSectionPickerViewDefault', FF_FRAMEWORK_DIR.'/framework/adminScreens/modalWindow/libraries/section/class.ffModalWindowSectionPickerViewDefault.php');
		
		
		$this->addClass( 'ffMetaBoxes', FF_FRAMEWORK_DIR.'/framework/adminScreens/metaBoxes/factories/class.ffMetaBoxes.php');
		$this->addClass( 'ffMetaBox', FF_FRAMEWORK_DIR.'/framework/adminScreens/metaBoxes/class.ffMetaBox.php');
		$this->addClass( 'ffMetaBoxView', FF_FRAMEWORK_DIR.'/framework/adminScreens/metaBoxes/class.ffMetaBoxView.php');
		$this->addClass( 'ffMetaBoxManager', FF_FRAMEWORK_DIR.'/framework/adminScreens/metaBoxes/class.ffMetaBoxManager.php');
		
		$this->addClass( 'ffMetaBoxViewFactory', FF_FRAMEWORK_DIR.'/framework/adminScreens/metaBoxes/factories/class.ffMetaBoxViewFactory.php');
		$this->addClass( 'ffMetaBoxFactory', FF_FRAMEWORK_DIR.'/framework/adminScreens/metaBoxes/factories/class.ffMetaBoxFactory.php');
	}

	############################################################################
	# EXTERN FILES
	############################################################################	
	private function _addExternFiles() {
        /*----------------------------------------------------------*/
        /* FRESHIZER
        /*----------------------------------------------------------*/
        $this->addClass( 'externFreshizer', FF_FRAMEWORK_DIR.'/framework/extern/freshizer/freshizer.php');

		########################################################################
		# MINIFICATION
		########################################################################
		$this->addClass( 'externCssMin', FF_FRAMEWORK_DIR.'/framework/extern/minify/CSSmin.php');
		$this->addClass( 'externJsMinPlus', FF_FRAMEWORK_DIR.'/framework/extern/minify/JSMinPlus.php');
		$this->addClass( 'externJsMinPlus_Adapteur', FF_FRAMEWORK_DIR.'/framework/extern/minify/JSMinPlus_Adapteur.php');
		
		########################################################################
		# FTP
		########################################################################
		$this->addClass( 'externDgFtp', FF_FRAMEWORK_DIR.'/framework/extern/ftp/dgFtp.php');
		
		########################################################################
		# less
		########################################################################
		$this->addClass( 'lessc_freshframework', FF_FRAMEWORK_DIR.'/framework/extern/less/lessc.inc.php');

		########################################################################
		# scss
		########################################################################
		$this->addClass( 'scssCss', FF_FRAMEWORK_DIR.'/framework/extern/scss/scss.inc.php');
		
		########################################################################
		# ACE
		########################################################################
		$this->addClass( 'ffAceLoader', FF_FRAMEWORK_DIR.'/framework/extern/ace/class.ffAceLoader.php');
		
		########################################################################
		# Select2
		########################################################################
		$this->addClass( 'ffSelect2Loader', FF_FRAMEWORK_DIR.'/framework/extern/select2/class.ffSelect2Loader.php');

		########################################################################
		# WP Importer
		########################################################################
		$this->addClass('wordpress-importer', FF_FRAMEWORK_DIR.'/framework/extern/wp-importer/wordpress-importer.php');
	}
	
	############################################################################
	# QUERY FILES
	############################################################################
	private function _addQueryFiles() {
		########################################################################
		# IDENTIFICATORS
		########################################################################
		$this->addClass( 'ffCustomPostTypeIdentificator', FF_FRAMEWORK_DIR.'/framework/query/identificators/post/class.ffCustomPostTypeIdentificator.php');
		$this->addClass( 'ffCustomPostTypeCollection', FF_FRAMEWORK_DIR.'/framework/query/identificators/post/class.ffCustomPostTypeCollection.php');
		$this->addClass( 'ffCustomPostTypeCollectionItem_Factory', FF_FRAMEWORK_DIR.'/framework/query/identificators/post/factories/class.ffCustomPostTypeCollectionItem_Factory.php');
		$this->addClass( 'ffCustomPostTypeCollection_Factory', FF_FRAMEWORK_DIR.'/framework/query/identificators/post/factories/class.ffCustomPostTypeCollection_Factory.php');
		$this->addClass( 'ffCustomPostTypeCollectionItem', FF_FRAMEWORK_DIR.'/framework/query/identificators/post/class.ffCustomPostTypeCollectionItem.php');
		
		$this->addClass( 'ffCustomTaxonomyIdentificator', FF_FRAMEWORK_DIR.'/framework/query/identificators/taxonomy/class.ffCustomTaxonomyIdentificator.php');
		$this->addClass( 'ffCustomTaxonomyCollection', FF_FRAMEWORK_DIR.'/framework/query/identificators/taxonomy/class.ffCustomTaxonomyCollection.php');
		$this->addClass( 'ffCustomTaxonomyCollectionItem', FF_FRAMEWORK_DIR.'/framework/query/identificators/taxonomy/class.ffCustomTaxonomyCollectionItem.php');
		
		$this->addClass( 'ffCustomTaxonomyCollection_Factory', FF_FRAMEWORK_DIR.'/framework/query/identificators/taxonomy/factories/class.ffCustomTaxonomyCollection_Factory.php');
		$this->addClass( 'ffCustomTaxonomyCollectionItem_Factory', FF_FRAMEWORK_DIR.'/framework/query/identificators/taxonomy/factories/class.ffCustomTaxonomyCollectionItem_Factory.php');
		
		########################################################################
		# QUERY
		########################################################################
		$this->addClass( 'ffFrontendQueryIdentificator', FF_FRAMEWORK_DIR.'/framework/query/identificators/query/class.ffFrontendQueryIdentificator.php');
	}

	private function _addAttachmentLayerFiles(){

		$this->addClass( 'ffAttachmentLayer', FF_FRAMEWORK_DIR.'/framework/query/attachments/class.ffAttachmentLayer.php');
		$this->addClass( 'ffAttachmentLayer_Factory', FF_FRAMEWORK_DIR.'/framework/query/attachments/class.ffAttachmentLayer_Factory.php');

		// Attachment list in admim
		//$this->addClass( 'ffAttachmentAdminColumn', FF_FRAMEWORK_DIR.'/framework/query/attachments/items/class.ffAttachmentAdminColumn.php');

		// Attachment collection returned by getter
		$this->addClass( 'ffAttachmentCollection_Factory', FF_FRAMEWORK_DIR.'/framework/query/attachments/items/class.ffAttachmentCollection_Factory.php');
		$this->addClass( 'ffAttachmentCollectionItem', FF_FRAMEWORK_DIR.'/framework/query/attachments/items/class.ffAttachmentCollectionItem.php');
		$this->addClass( 'ffAttachmentCollection', FF_FRAMEWORK_DIR.'/framework/query/attachments/items/class.ffAttachmentCollection.php');

		// Attachments
		$this->addClass( 'ffAttachmentGetter', FF_FRAMEWORK_DIR.'/framework/query/attachments/items/class.ffAttachmentGetter.php');
		$this->addClass( 'ffAttachmentUpdater', FF_FRAMEWORK_DIR.'/framework/query/attachments/items/class.ffAttachmentUpdater.php');

		// Pseudo - Registrator
		$this->addClass( 'ffMimeTypesManager', FF_FRAMEWORK_DIR.'/framework/query/attachments/class.ffMimeTypesManager.php');
	}
	
	private function _addPostLayerFiles() {
		$this->addClass( 'ffPostLayer',              FF_FRAMEWORK_DIR.'/framework/query/posts/class.ffPostLayer.php');
		$this->addClass( 'ffPostLayer_Factory',      FF_FRAMEWORK_DIR.'/framework/query/posts/class.ffPostLayer_Factory.php');

		// Post list in admim
		$this->addClass( 'ffPostAdminColumnManager', FF_FRAMEWORK_DIR.'/framework/query/posts/class.ffPostAdminColumnManager.php');

		// Post collection returned by getter
		$this->addClass( 'ffPostCollection_Factory', FF_FRAMEWORK_DIR.'/framework/query/posts/items/class.ffPostCollection_Factory.php');
		$this->addClass( 'ffPostCollectionItem',     FF_FRAMEWORK_DIR.'/framework/query/posts/items/class.ffPostCollectionItem.php');
		$this->addClass( 'ffPostCollection',         FF_FRAMEWORK_DIR.'/framework/query/posts/items/class.ffPostCollection.php');

		$this->addClass( 'ffDirectDb',             FF_FRAMEWORK_DIR.'/framework/query/class.ffDirectDb.php');

		// Posts
		$this->addClass( 'ffPostGetter',             FF_FRAMEWORK_DIR.'/framework/query/posts/items/class.ffPostGetter.php');
		$this->addClass( 'ffPostUpdater',            FF_FRAMEWORK_DIR.'/framework/query/posts/items/class.ffPostUpdater.php');

		// Registrator
		$this->addClass( 'ffPostTypeRegistrator',         FF_FRAMEWORK_DIR.'/framework/query/posts/registrator/class.ffPostTypeRegistrator.php'         );
		$this->addClass( 'ffPostTypeRegistratorArgs',     FF_FRAMEWORK_DIR.'/framework/query/posts/registrator/class.ffPostTypeRegistratorArgs.php'     );
		$this->addClass( 'ffPostTypeRegistratorLabels',   FF_FRAMEWORK_DIR.'/framework/query/posts/registrator/class.ffPostTypeRegistratorLabels.php'   );
		$this->addClass( 'ffPostTypeRegistratorMessages', FF_FRAMEWORK_DIR.'/framework/query/posts/registrator/class.ffPostTypeRegistratorMessages.php' );
		$this->addClass( 'ffPostTypeRegistratorSupports', FF_FRAMEWORK_DIR.'/framework/query/posts/registrator/class.ffPostTypeRegistratorSupports.php' );

		$this->addClass( 'ffPostTypeRegistratorManager',  FF_FRAMEWORK_DIR.'/framework/query/posts/registrator/class.ffPostTypeRegistratorManager.php');
		$this->addClass( 'ffPostTypeRegistrator_Factory', FF_FRAMEWORK_DIR.'/framework/query/posts/registrator/class.ffPostTypeRegistrator_Factory.php' );
	}
	
	private function _addTaxLayerFiles() {
		$this->addClass( 'ffTaxLayer', FF_FRAMEWORK_DIR.'/framework/query/taxonomies/class.ffTaxLayer.php');
		$this->addClass( 'ffTaxLayer_Factory', FF_FRAMEWORK_DIR.'/framework/query/taxonomies/class.ffTaxLayer_Factory.php');

		// Taxonomies
		$this->addClass( 'ffTaxGetter', FF_FRAMEWORK_DIR.'/framework/query/taxonomies/items/class.ffTaxGetter.php');
		$this->addClass( 'ffTaxUpdater', FF_FRAMEWORK_DIR.'/framework/query/taxonomies/items/class.ffTaxUpdater.php');

		// Taxonomy list in admim
		$this->addClass( 'ffTaxAdminColumn', FF_FRAMEWORK_DIR.'/framework/query/taxonomies/items/adminColumns/class.ffTaxAdminColumn.php');

		// Registrator		
		$this->addClass( 'ffCustomTaxonomy',         FF_FRAMEWORK_DIR.'/framework/query/taxonomies/registrator/class.ffCustomTaxonomy.php'         );
		$this->addClass( 'ffCustomTaxonomyArgs',     FF_FRAMEWORK_DIR.'/framework/query/taxonomies/registrator/class.ffCustomTaxonomyArgs.php'     );
		$this->addClass( 'ffCustomTaxonomyLabels',   FF_FRAMEWORK_DIR.'/framework/query/taxonomies/registrator/class.ffCustomTaxonomyLabels.php'   );
		//$this->addClass( 'ffCustomTaxonomyMessages', FF_FRAMEWORK_DIR.'/framework/query/taxonomies/registrator/class.ffCustomTaxonomyMessages.php' );
		$this->addClass( 'ffCustomTaxonomyManager',  FF_FRAMEWORK_DIR.'/framework/query/taxonomies/registrator/class.ffCustomTaxonomyManager.php');
		$this->addClass( 'ffCustomTaxonomy_Factory', FF_FRAMEWORK_DIR.'/framework/query/taxonomies/registrator/class.ffCustomTaxonomy_Factory.php' );
	}	

	############################################################################ 
	# UPDATER FILES
	############################################################################
	private function _addUpdaterFiles() {
		$this->addClass( 'ffWPUpgrader', FF_FRAMEWORK_DIR.'/framework/updater/class.ffWPUpgrader.php');
	}
	
	
	############################################################################
	# FRSLIB FILES
	############################################################################
	private function _addFrsLibFiles() {
		$this->addClass( 'ffFrsLibLoader', FF_FRAMEWORK_DIR.'/framework/frs/class.ffFrsLibLoader.php');
	}	
	
	############################################################################
	# SHORTCODES FILES
	############################################################################
	private function _addThemeFiles() {

		$this->addClass('ffSystemEnvironment', FF_FRAMEWORK_DIR.'/framework/themes/class.ffSystemEnvironment.php' );

		$this->addClass('ffAdminScreenDummy', FF_FRAMEWORK_DIR.'/framework/themes/dummy/adminScreen/class.ffAdminScreenDummy.php');
		$this->addClass('ffAdminScreenDummyViewDefault', FF_FRAMEWORK_DIR.'/framework/themes/dummy/adminScreen/class.ffAdminScreenDummyViewDefault.php');

		$this->addClass('ffAdminScreenMigration', FF_FRAMEWORK_DIR.'/framework/themes/migration/class.ffAdminScreenMigration.php');
		$this->addClass('ffAdminScreenMigrationViewDefault', FF_FRAMEWORK_DIR.'/framework/themes/migration/class.ffAdminScreenMigrationViewDefault.php');

		$this->addClass('ffAdminScreenGlobalStyles', FF_FRAMEWORK_DIR.'/framework/themes/globalStyles/class.ffAdminScreenGlobalStyles.php');
		$this->addClass('ffAdminScreenGlobalStylesViewDefault', FF_FRAMEWORK_DIR.'/framework/themes/globalStyles/class.ffAdminScreenGlobalStylesViewDefault.php');

		$this->addClass('ffAdminScreenHireUs', FF_FRAMEWORK_DIR.'/framework/themes/hireUs/class.ffAdminScreenHireUs.php');
		$this->addClass('ffAdminScreenHireUsViewDefault', FF_FRAMEWORK_DIR.'/framework/themes/hireUs/class.mustBeDiff.php');

		$this->addClass('ffAdminScreenImprovements', FF_FRAMEWORK_DIR.'/framework/themes/improvements/class.ffAdminScreenImprovements.php');
		$this->addClass('ffAdminScreenImprovementsViewDefault', FF_FRAMEWORK_DIR.'/framework/themes/improvements/class.ffAdminScreenImprovementsViewDefault.php');


		$this->addClass('ffAdminScreenDocumentation', FF_FRAMEWORK_DIR.'/framework/themes/documentation/class.ffAdminScreenDocumentation.php');
		$this->addClass('ffAdminScreenDocumentationViewDefault', FF_FRAMEWORK_DIR.'/framework/themes/documentation/class.ffAdminScreenDocumentationViewDefault.php');
		
		
		$this->addClass('ffAdminScreenDashboard', FF_FRAMEWORK_DIR.'/framework/themes/dashboard/class.ffAdminScreenDashboard.php');
		$this->addClass('ffAdminScreenDashboardViewDefault', FF_FRAMEWORK_DIR.'/framework/themes/dashboard/class.ffAdminScreenDashboardViewDefault.php');
		$this->addClass('ffAdminScreenDashboardViewStatus', FF_FRAMEWORK_DIR.'/framework/themes/dashboard/class.ffAdminScreenDashboardViewStatus.php');

		$this->addClass('ffAdminScreenUpdates', FF_FRAMEWORK_DIR.'/framework/themes/updates/class.ffAdminScreenUpdates.php');
		$this->addClass('ffAdminScreenUpdatesViewDefault', FF_FRAMEWORK_DIR.'/framework/themes/updates/class.ffAdminScreenUpdatesViewDefault.php');

		$this->addClass('ffPrivateUpdateManager', FF_FRAMEWORK_DIR.'/framework/themes/updates/class.ffPrivateUpdateManager.php');



		$this->addClass('ffAdminScreenSitePreferences', FF_FRAMEWORK_DIR.'/framework/themes/sitePreferences/class.ffAdminScreenSitePreferences.php');
		$this->addClass('ffAdminScreenSitePreferencesViewDefault', FF_FRAMEWORK_DIR.'/framework/themes/sitePreferences/class.ffAdminScreenSitePreferencesViewDefault.php');

		$this->addClass( 'ffThemeAssetsIncluderAbstract', FF_FRAMEWORK_DIR.'/framework/themes/assetsIncluding/class.ffThemeAssetsIncluderAbstract.php');
		$this->addClass( 'ffThemeAssetsManager', FF_FRAMEWORK_DIR.'/framework/themes/assetsIncluding/class.ffThemeAssetsManager.php');
		$this->addClass( 'ffThemeFrameworkFactory', FF_FRAMEWORK_DIR.'/framework/themes/factories/class.ffThemeFrameworkFactory.php');
		
		$this->addClass('ffVideoIncluder', FF_FRAMEWORK_DIR.'/framework/themes/functions/class.ffVideoIncluder.php');
		$this->addClass('ffPaginationComputer', FF_FRAMEWORK_DIR.'/framework/themes/functions/class.ffPaginationComputer.php');
		$this->addClass('ffPaginationWPLoop', FF_FRAMEWORK_DIR.'/framework/themes/functions/class.ffPaginationWPLoop.php');
		$this->addClass('ffSocialFeedCreator', FF_FRAMEWORK_DIR.'/framework/themes/functions/class.ffSocialFeedCreator.php');
		$this->addClass('ffSocialSharerFeedCreator', FF_FRAMEWORK_DIR.'/framework/themes/functions/class.ffSocialSharerFeedCreator.php');
		$this->addClass('ffThemeViewIdentificator', FF_FRAMEWORK_DIR.'/framework/themes/functions/class.ffThemeViewIdentificator.php');
		
		$this->addClass('ffPostMetaGetter', FF_FRAMEWORK_DIR.'/framework/themes/functions/class.ffPostMetaGetter.php');

        $this->addClass('ffNavMenuWalker', FF_FRAMEWORK_DIR.'/framework/themes/functions/class.ffNavMenuWalker.php');
        $this->addClass('ffCommentWalker', FF_FRAMEWORK_DIR.'/framework/themes/functions/class.ffCommentWalker.php');
        $this->addClass('ffCommentsFormPrinter', FF_FRAMEWORK_DIR.'/framework/themes/functions/class.ffCommentsFormPrinter.php');

        $this->addClass('ffExternalPluginsTweaker', FF_FRAMEWORK_DIR.'/framework/themes/functions/class.ffExternalPluginsTweaker.php');



	############################################################################
	# SPEED OPT
	############################################################################
		$this->addClass('ffThemeSpeedOpt', FF_FRAMEWORK_DIR.'/framework/themes/speedOpt/class.ffThemeSpeedOpt.php');

	############################################################################
	# TOOLKIT
	############################################################################
		$this->addClass('ffTempOptions', FF_FRAMEWORK_DIR.'/framework/themes/toolkit/class.ffTempOptions.php');

    ############################################################################
	# THEME LAYOUT MANAGER
	############################################################################
        $this->addClass('ffLayoutsNamespaceFactory', FF_FRAMEWORK_DIR.'/framework/themes/layouts/factories/class.ffLayoutsNamespaceFactory.php');

        $this->addClass('ffOnePageRevisionManager', FF_FRAMEWORK_DIR.'/framework/themes/layouts/class.ffOnePageRevisionManager.php');

        $this->addClass('ffThemeLayoutManager', FF_FRAMEWORK_DIR.'/framework/themes/layouts/class.ffThemeLayoutManager.php');
        $this->addClass('ffLayoutsEmojiManager', FF_FRAMEWORK_DIR.'/framework/themes/layouts/class.ffLayoutsEmojiManager.php');

        $this->addClass('ffLayoutPostType', FF_FRAMEWORK_DIR.'/framework/themes/layouts/class.ffLayoutPostType.php');

        $this->addClass('ffOptionsHolder_Layout_Conditions', FF_FRAMEWORK_DIR.'/framework/themes/layouts/optionsHolders/class.ffOptionsHolder_Layout_Conditions.php');
        $this->addClass('ffOptionsHolder_Layout_Placement', FF_FRAMEWORK_DIR.'/framework/themes/layouts/optionsHolders/class.ffOptionsHolder_Layout_Placement.php');
        $this->addClass('ffLayoutPostType', FF_FRAMEWORK_DIR.'/framework/themes/layouts/class.ffLayoutPostType.php');

        $this->addClass('ffLayoutsDataManager', FF_FRAMEWORK_DIR.'/framework/themes/layouts/class.ffLayoutsDataManager.php');

        $this->addClass('ffLayoutCollectionItem_Factory', FF_FRAMEWORK_DIR.'/framework/themes/layouts/factories/class.ffLayoutCollectionItem_Factory.php');
        $this->addClass('ffLayoutsCollection', FF_FRAMEWORK_DIR.'/framework/themes/layouts/class.ffLayoutsCollection.php');
        $this->addClass('ffLayoutsCollectionFactory', FF_FRAMEWORK_DIR.'/framework/themes/layouts/factories/class.ffLayoutsCollectionFactory.php');

        $this->addClass('ffLayoutCollectionItem', FF_FRAMEWORK_DIR.'/framework/themes/layouts/class.ffLayoutCollectionItem.php');

        $this->addClass('ffLayoutPrinter', FF_FRAMEWORK_DIR.'/framework/themes/layouts/class.ffLayoutPrinter.php');

        $this->addClass('ffMetaBoxLayoutContent', FF_FRAMEWORK_DIR.'/framework/themes/layouts/metaBoxes/metaBoxLayoutContent/class.ffMetaBoxLayoutContent.php');
        $this->addClass('ffMetaBoxLayoutContentView', FF_FRAMEWORK_DIR.'/framework/themes/layouts/metaBoxes/metaBoxLayoutContent/class.ffMetaBoxLayoutContentView.php');

        $this->addClass('ffMetaBoxLayoutPlacement', FF_FRAMEWORK_DIR.'/framework/themes/layouts/metaBoxes/metaBoxLayoutPlacement/class.ffMetaBoxLayoutPlacement.php');
        $this->addClass('ffMetaBoxLayoutPlacementView', FF_FRAMEWORK_DIR.'/framework/themes/layouts/metaBoxes/metaBoxLayoutPlacement/class.ffMetaBoxLayoutPlacementView.php');

        $this->addClass('ffMetaBoxLayoutConditions', FF_FRAMEWORK_DIR.'/framework/themes/layouts/metaBoxes/metaBoxLayoutConditions/class.ffMetaBoxLayoutConditions.php');
        $this->addClass('ffMetaBoxLayoutConditionsView', FF_FRAMEWORK_DIR.'/framework/themes/layouts/metaBoxes/metaBoxLayoutConditions/class.ffMetaBoxLayoutConditionsView.php');



		$this->addClass('ffAdminConsole', FF_FRAMEWORK_DIR.'/framework/themes/adminConsole/class.ffAdminConsole.php');

	############################################################################
	# THEME BUILDER MANAGER
	############################################################################


		$this->addClass('ffThemeBuilder', FF_FRAMEWORK_DIR.'/framework/themes/builder/class.ffThemeBuilder.php');

		$this->addClass('ffThemeBuilderManager', FF_FRAMEWORK_DIR.'/framework/themes/builder/class.ffThemeBuilderManager.php');
		$this->addClass('ffThemeBuilderCache', FF_FRAMEWORK_DIR.'/framework/themes/builder/class.ffThemeBuilderCache.php');
		$this->addClass('ffThemeBuilderRegexp', FF_FRAMEWORK_DIR.'/framework/themes/builder/class.ffThemeBuilderRegexp.php');

		$this->addClass('ffThemeBuilderSectionManager', FF_FRAMEWORK_DIR.'/framework/themes/builder/class.ffThemeBuilderSectionManager.php');
		$this->addClass('ffThemeBuilderDeveloperTools', FF_FRAMEWORK_DIR.'/framework/themes/builder/class.ffThemeBuilderDeveloperTools.php');
        $this->addClass('ffThemeBuilderElement', FF_FRAMEWORK_DIR.'/framework/themes/builder/class.ffThemeBuilderElement.php');
        $this->addClass('ffThemeBuilderElementBasic', FF_FRAMEWORK_DIR.'/framework/themes/builder/class.ffThemeBuilderElementBasic.php');
		$this->addClass('ffThemeBuilderElementManager', FF_FRAMEWORK_DIR.'/framework/themes/builder/class.ffThemeBuilderElementManager.php');
		$this->addClass('ffThemeBuilderElementManager_Frontend', FF_FRAMEWORK_DIR.'/framework/themes/builder/class.ffThemeBuilderElementManager_Frontend.php');
        $this->addClass('ffThemeBuilderShortcodesWalker', FF_FRAMEWORK_DIR.'/framework/themes/builder/class.ffThemeBuilderShortcodesWalker.php');
        $this->addClass('ffThemeBuilderElementFactory', FF_FRAMEWORK_DIR.'/framework/themes/builder/class.ffThemeBuilderElementFactory.php');
		$this->addClass('ffThemeBuilderOptionsExtender', FF_FRAMEWORK_DIR.'/framework/themes/builder/class.ffThemeBuilderOptionsExtender.php');
		$this->addClass('ffThemeBuilderShortcodesStatusHolder', FF_FRAMEWORK_DIR.'/framework/themes/builder/class.ffThemeBuilderShortcodesStatusHolder.php');

		$this->addClass('ffThemeBuilderColorLibrary', FF_FRAMEWORK_DIR.'/framework/themes/builder/class.ffThemeBuilderColorLibrary.php');
		$this->addClass('ffThemeBuilderGlobalStyles', FF_FRAMEWORK_DIR.'/framework/themes/builder/class.ffThemeBuilderGlobalStyles.php');

        $this->addClass('ffMetaBoxThemeBuilder', FF_FRAMEWORK_DIR.'/framework/themes/builder/metaBoxThemeBuilder/class.ffMetaBoxThemeBuilder.php');
        $this->addClass('ffMetaBoxThemeBuilderView', FF_FRAMEWORK_DIR.'/framework/themes/builder/metaBoxThemeBuilder/class.ffMetaBoxThemeBuilderView.php');

		$this->addClass('ffThemeBuilderBlock', FF_FRAMEWORK_DIR.'/framework/themes/builder/class.ffThemeBuilderBlock.php');
        $this->addClass('ffThemeBuilderBlockGlobalStyle', FF_FRAMEWORK_DIR.'/framework/themes/builder/class.ffThemeBuilderBlockGlobalStyle.php');
        $this->addClass('ffThemeBuilderBlockBasic', FF_FRAMEWORK_DIR.'/framework/themes/builder/class.ffThemeBuilderBlockBasic.php');


		$this->addClass('ffThemeBuilderBlockFactory', FF_FRAMEWORK_DIR.'/framework/themes/builder/class.ffThemeBuilderBlockFactory.php');
		$this->addClass('ffThemeBuilderBlockManager', FF_FRAMEWORK_DIR.'/framework/themes/builder/class.ffThemeBuilderBlockManager.php');

		$this->addClass('ffThemeBuilderAssetsRenderer', FF_FRAMEWORK_DIR.'/framework/themes/builder/assetsRendering/class.ffThemeBuilderAssetsRenderer.php');
		$this->addClass('ffThemeBuilderCssRule', FF_FRAMEWORK_DIR.'/framework/themes/builder/assetsRendering/class.ffThemeBuilderCssRule.php');
		$this->addClass('ffThemeBuilderCssRuleFactory', FF_FRAMEWORK_DIR.'/framework/themes/builder/assetsRendering/class.ffThemeBuilderCssRuleFactory.php');

		$this->addClass('ffThemeBuilderJavascriptCode', FF_FRAMEWORK_DIR.'/framework/themes/builder/assetsRendering/class.ffThemeBuilderJavascriptCode.php');
		$this->addClass('ffThemeBuilderJavascriptCodeFactory', FF_FRAMEWORK_DIR.'/framework/themes/builder/assetsRendering/class.ffThemeBuilderJavascriptCodeFactory.php');


		$this->addClass('ffThemeBuilderBlock_HTML', FF_FRAMEWORK_DIR.'/framework/themes/builder/blocks/class.ffThemeBuilderBlock_HTML.php');
        $this->addClass('ffThemeBuilderBlock_BootstrapColumns', FF_FRAMEWORK_DIR.'/framework/themes/builder/blocks/class.ffThemeBuilderBlock_BootstrapColumns.php');
		$this->addClass('ffThemeBuilderBlock_Backgrounds', FF_FRAMEWORK_DIR.'/framework/themes/builder/blocks/class.ffThemeBuilderBlock_Backgrounds.php');
        $this->addClass('ffThemeBuilderBlock_BoxModel', FF_FRAMEWORK_DIR.'/framework/themes/builder/blocks/class.ffThemeBuilderBlock_BoxModel.php');
        $this->addClass('ffThemeBuilderBlock_Styles', FF_FRAMEWORK_DIR.'/framework/themes/builder/blocks/class.ffThemeBuilderBlock_Styles.php');
		$this->addClass('ffThemeBuilderBlock_Colors', FF_FRAMEWORK_DIR.'/framework/themes/builder/blocks/class.ffThemeBuilderBlock_Colors.php');
		$this->addClass('ffThemeBuilderBlock_PaddingMargin', FF_FRAMEWORK_DIR.'/framework/themes/builder/blocks/class.ffThemeBuilderBlock_PaddingMargin.php');
		$this->addClass('ffThemeBuilderBlock_AdvancedTools', FF_FRAMEWORK_DIR.'/framework/themes/builder/blocks/class.ffThemeBuilderBlock_AdvancedTools.php');
        $this->addClass('ffThemeBuilderBlock_CustomCodes', FF_FRAMEWORK_DIR.'/framework/themes/builder/blocks/class.ffThemeBuilderBlock_CustomCodes.php');
		$this->addClass('ffThemeBuilderBlock_Link', FF_FRAMEWORK_DIR.'/framework/themes/builder/blocks/class.ffThemeBuilderBlock_Link.php');
		$this->addClass('ffThemeBuilderBlock_Loop', FF_FRAMEWORK_DIR.'/framework/themes/builder/blocks/class.ffThemeBuilderBlock_Loop.php');


		$this->addClass('ffElIf', FF_FRAMEWORK_DIR.'/framework/themes/builder/elements/class.ffElIf.php');
		$this->addClass('ffElShortcodeWrapper', FF_FRAMEWORK_DIR.'/framework/themes/builder/elements/class.ffElShortcodeWrapper.php');
        $this->addClass('ffElColumn', FF_FRAMEWORK_DIR.'/framework/themes/builder/elements/class.ffElColumn.php');
        $this->addClass('ffElRow', FF_FRAMEWORK_DIR.'/framework/themes/builder/elements/class.ffElRow.php');
        $this->addClass('ffElSection', FF_FRAMEWORK_DIR.'/framework/themes/builder/elements/class.ffElSection.php');
        $this->addClass('ffElSectionBootstrap', FF_FRAMEWORK_DIR.'/framework/themes/builder/elements/class.ffElSectionBootstrap.php');
        $this->addClass('ffElContainer', FF_FRAMEWORK_DIR.'/framework/themes/builder/elements/class.ffElContainer.php');
		$this->addClass('ffElWrapper', FF_FRAMEWORK_DIR.'/framework/themes/builder/elements/class.ffElWrapper.php');
		$this->addClass('ffElLink', FF_FRAMEWORK_DIR.'/framework/themes/builder/elements/class.ffElLink.php');
		$this->addClass('ffElContentBlock', FF_FRAMEWORK_DIR.'/framework/themes/builder/elements/class.ffElContentBlock.php');
		$this->addClass('ffElContentBlockAdmin', FF_FRAMEWORK_DIR.'/framework/themes/builder/elements/class.ffElContentBlockAdmin.php');

	############################################################################
	# SITE PREFERENCES
	############################################################################
		$this->addClass('ffSiteMapper', FF_FRAMEWORK_DIR.'/framework/themes/sitePreferences/class.ffSiteMapper.php');
		$this->addClass('ffSitePreferencesFactory', FF_FRAMEWORK_DIR.'/framework/themes/sitePreferences/class.ffSitePreferencesFactory.php');
		$this->addClass('ffSitePreferencesManager', FF_FRAMEWORK_DIR.'/framework/themes/sitePreferences/class.ffSitePreferencesManager.php');
		
		
		$this->addClass('ffAdminScreenSitePreferences', FF_FRAMEWORK_DIR.'/framework/themes/sitePreferences/class.ffAdminScreenSitePreferences.php');
		$this->addClass('ffAdminScreenSitePreferencesViewDefault', FF_FRAMEWORK_DIR.'/framework/themes/sitePreferences/class.ffAdminScreenSitePreferencesViewDefault.php');
		$this->addClass('ffOptionsHolder_SitePreferencesMetaBox', FF_FRAMEWORK_DIR.'/framework/themes/sitePreferences/metaBox/class.ffOptionsHolder_SitePreferencesMetaBox.php');
		$this->addClass('ffMetaBoxSitePreferences', FF_FRAMEWORK_DIR.'/framework/themes/sitePreferences/metaBox/class.ffMetaBoxSitePreferences.php');
		$this->addClass('ffMetaBoxSitePreferencesView', FF_FRAMEWORK_DIR.'/framework/themes/sitePreferences/metaBox/class.ffMetaBoxSitePreferencesView.php');



		$this->addClass('ffAdminScreenCollectionView', FF_FRAMEWORK_DIR.'/framework/themes/sitePreferences/collections/class.ffAdminScreenCollectionView.php');

//		$this->addClass('ffOptionsCollection', FF_FRAMEWORK_DIR.'/framework/themes/sitePreferences/collections/class.ffOptionsCollection.php');
		$this->addClass('ffItemPartCollection', FF_FRAMEWORK_DIR.'/framework/themes/sitePreferences/collections/class.ffItemPartCollection.php');
		$this->addClass('ffItemPartCollection_Header', FF_FRAMEWORK_DIR.'/framework/themes/sitePreferences/collections/class.ffItemPartCollection_Header.php');

		$this->addClass('ffAdminScreenHeaders', FF_FRAMEWORK_DIR.'/framework/themes/sitePreferences/collections/class.ffAdminScreenHeaders.php');
		$this->addClass('ffAdminScreenHeadersViewDefault', FF_FRAMEWORK_DIR.'/framework/themes/sitePreferences/collections/class.ffAdminScreenHeadersViewDefault.php');

		$this->addClass('ffAdminScreenBoxedWrappers', FF_FRAMEWORK_DIR.'/framework/themes/sitePreferences/collections/class.ffAdminScreenBoxedWrappers.php');
		$this->addClass('ffAdminScreenBoxedWrappersViewDefault', FF_FRAMEWORK_DIR.'/framework/themes/sitePreferences/collections/class.ffAdminScreenBoxedWrappersViewDefault.php');

		$this->addClass('ffAdminScreenFooters', FF_FRAMEWORK_DIR.'/framework/themes/sitePreferences/collections/class.ffAdminScreenFooters.php');
		$this->addClass('ffAdminScreenFootersViewDefault', FF_FRAMEWORK_DIR.'/framework/themes/sitePreferences/collections/class.ffAdminScreenFootersViewDefault.php');

		$this->addClass('ffAdminScreenLayouts', FF_FRAMEWORK_DIR.'/framework/themes/sitePreferences/collections/class.ffAdminScreenLayouts.php');
		$this->addClass('ffAdminScreenLayoutsViewDefault', FF_FRAMEWORK_DIR.'/framework/themes/sitePreferences/collections/class.ffAdminScreenLayoutsViewDefault.php');

		$this->addClass('ffAdminScreenTitlebars', FF_FRAMEWORK_DIR.'/framework/themes/sitePreferences/collections/class.ffAdminScreenTitlebars.php');
		$this->addClass('ffAdminScreenTitlebarsViewDefault', FF_FRAMEWORK_DIR.'/framework/themes/sitePreferences/collections/class.ffAdminScreenTitlebarsViewDefault.php');

		$this->addClass('ffViewDataManager', FF_FRAMEWORK_DIR.'/framework/themes/sitePreferences/class.ffViewDataManager.php');
		$this->addClass('ffOptionsHolder_SitePreferences', FF_FRAMEWORK_DIR.'/framework/themes/sitePreferences/class.ffOptionsHolder_SitePreferences.php');


		



	############################################################################
	# THEME DUMMY CONTENT
	############################################################################
		$this->addClass('ffWPImporter', FF_FRAMEWORK_DIR.'/framework/themes/dummy/class.ffWPImporter.php');
		$this->addClass('ffImportRuleBasic', FF_FRAMEWORK_DIR.'/framework/themes/dummy/class.ffImportRuleBasic.php');
		$this->addClass('ffImportStepBasic', FF_FRAMEWORK_DIR.'/framework/themes/dummy/class.ffImportStepBasic.php');
		$this->addClass('ffImportRuleFactory', FF_FRAMEWORK_DIR.'/framework/themes/dummy/class.ffImportRuleFactory.php');
		$this->addClass('ffImportManager', FF_FRAMEWORK_DIR.'/framework/themes/dummy/class.ffImportManager.php');
		$this->addClass('ffDummyContentManager', FF_FRAMEWORK_DIR.'/framework/themes/dummy/class.ffDummyContentManager.php');

    ############################################################################
	# THEME ONE PAGE
	############################################################################

        $this->addClass('ffMetaBoxOnePageFramework', FF_FRAMEWORK_DIR.'/framework/themes/onePage/metaBoxOnePageFramework/class.ffMetaBoxOnePageFramework.php');
        $this->addClass('ffMetaBoxOnePageFrameworkView', FF_FRAMEWORK_DIR.'/framework/themes/onePage/metaBoxOnePageFramework/class.ffMetaBoxOnePageFrameworkView.php');

        $this->addClass('ffThemeOnePageManager', FF_FRAMEWORK_DIR.'/framework/themes/onePage/class.ffThemeOnePageManager.php');





    ############################################################################
	# THEME MENU OPTIONS
	############################################################################
        $this->addClass('ffMenuOptionsManager', FF_FRAMEWORK_DIR.'/framework/themes/menuOptions/class.ffMenuOptionsManager.php');
        $this->addClass('ffMenuJavascriptSaver', FF_FRAMEWORK_DIR.'/framework/themes/menuOptions/class.ffMenuJavascriptSaver.php');
	}
	
	private function _addConstantFiles() {
		$this->addClass( 'ffConstActions', FF_FRAMEWORK_DIR.'/framework/constants/class.ffConstActions.php');
		$this->addClass( 'ffConstThemeViews', FF_FRAMEWORK_DIR.'/framework/constants/class.ffConstThemeViews.php');
		$this->addClass( 'ffConstHTMLClasses', FF_FRAMEWORK_DIR.'/framework/constants/class.ffConstHTMLClasses.php');
        $this->addClass( 'ffConstQuery', FF_FRAMEWORK_DIR.'/framework/constants/class.ffConstQuery.php');
        $this->addClass( 'ffConstCache', FF_FRAMEWORK_DIR.'/framework/constants/class.ffConstCache.php');
		
	}
	
	############################################################################
	# SHORTCODES FILES
	############################################################################
	private function _addShortcodesFiles() {
		$this->addClass( 'ffShortcodesNamespaceFactory', FF_FRAMEWORK_DIR.'/framework/shortcodes/factories/class.ffShortcodesNamespaceFactory.php');
		$this->addClass( 'ffShortcodeFactory', FF_FRAMEWORK_DIR.'/framework/shortcodes/factories/class.ffShortcodeFactory.php');
		$this->addClass( 'ffShortcodeManager', FF_FRAMEWORK_DIR.'/framework/shortcodes/class.ffShortcodeManager.php');
		$this->addClass( 'ffShortcodeObjectBasic', FF_FRAMEWORK_DIR.'/framework/shortcodes/class.ffShortcodeObjectBasic.php');
        $this->addClass( 'ffShortcodeContentParser', FF_FRAMEWORK_DIR.'/framework/shortcodes/class.ffShortcodeContentParser.php');
	}

    private function _addUserFiles() {
        $this->addClass( 'ffUser', FF_FRAMEWORK_DIR.'/framework/query/users/class.ffUser.php');
        $this->addClass( 'ffUserFactory', FF_FRAMEWORK_DIR.'/framework/query/users/class.ffUserFactory.php');
        $this->addClass( 'ffUserNamespaceFactory', FF_FRAMEWORK_DIR.'/framework/query/users/class.ffUserNamespaceFactory.php');
        $this->addClass( 'ffUserModel', FF_FRAMEWORK_DIR.'/framework/query/users/class.ffUserModel.php');

        $this->addClass( 'ffUserRole', FF_FRAMEWORK_DIR.'/framework/query/users/class.ffUserRole.php');
        $this->addClass( 'ffUserRoleFactory', FF_FRAMEWORK_DIR.'/framework/query/users/class.ffUserRoleFactory.php');
        $this->addClass( 'ffUserRoleManager', FF_FRAMEWORK_DIR.'/framework/query/users/class.ffUserRoleManager.php');
    }

    private function _addDatabaseFiles() {
        $this->addClass( 'ffDatabaseModelBasic', FF_FRAMEWORK_DIR.'/framework/database/class.ffDatabaseModelBasic.php');
    }

	private function _initFrameworkClasses() {
		$this->_addCoreFiles();

		$this->_addAttachmentLayerFiles();
		$this->_addPostLayerFiles();
		$this->_addTaxLayerFiles();
        $this->_addUserFiles();


		$this->_addAssetsFiles();
		$this->_addFileSystemFiles();
		$this->_addOptionsFiles();
		$this->_addDataStorageFiles();
		$this->_addGraphicFiles();
		$this->_addLibsFiles();
		$this->_addComponentFiles();
		$this->_addAdminScreenFiles();
		$this->_addExternFiles();
		$this->_addQueryFiles();
		$this->_addUpdaterFiles();
		$this->_addFrsLibFiles();
		$this->_addShortcodesFiles();
		$this->_addThemeFiles();
		$this->_addConstantFiles();
        $this->_addHelperFiles();

        $this->_addDatabaseFiles();
	}	
	
	private function _requireClass( $path ) {
		require_once( $path );
	}
/******************************************************************************/
/* SETTERS AND GETTERS
/******************************************************************************/
}