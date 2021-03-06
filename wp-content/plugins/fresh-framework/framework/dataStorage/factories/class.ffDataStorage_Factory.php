<?php

class ffDataStorage_Factory extends ffFactoryAbstract {
	/**
	 * 
	 * @var ffWPLayer
	 */
	private $_WPLayer = null;

	
	public function __construct(ffClassLoader $classLoader, ffWPLayer $WPLayer ) {
		$this->_setWplayer($WPLayer);
		parent::__construct($classLoader);
	}

	public function createOptionsCollection() {
		$arrayQueryFactory = new ffArrayQueryFactory( $this->_getClassloader() );
		$optionsCollection = new ffOptionsCollection(
			$this->createDataStorageWPOptionsNamespace(),
			$arrayQueryFactory
		);

		$themeNameInternal = ffContainer()->getEnvironment()->getThemeVariable( ffEnvironment::THEME_NAME);
//		var_Dump( $themeName );
//		die();

		$optionsCollection->setThemeName( $themeNameInternal );

		return $optionsCollection;
	}
	
	/**
	 * 
	 * @return ffDataStorage_WPPostMetas
	 */
	public function createDataStorageWPPostMetas() {
		$this->_getClassloader()->loadClass('ffIDataStorage');
		$this->_getClassloader()->loadClass('ffDataStorage');
		$this->_getClassloader()->loadClass('ffDataStorage_WPPostMetas');
		$dataStorage = new ffDataStorage_WPPostMetas( $this->_getWplayer() );
		return $dataStorage;
	}	
	
	public function createDataStoragePostTypeRegistrator() {
		$this->_getClassloader()->loadClass('ffDataStorage_OptionsPostTypeRegistrator');
		
		$registrator = new ffDataStorage_OptionsPostTypeRegistrator( ffContainer::getInstance()->getPostTypeRegistratorManager() );
		
		return $registrator;
	}

    public function createDataStorageTheme() {
        $classLoader = $this->_getClassloader();
        $classLoader->loadClass('ffIDataStorage');
		$classLoader->loadClass('ffDataStorage_Theme');


        $dataStorageTheme = new ffDataStorage_Theme();

        return $dataStorageTheme;
//			$this->_dataStorageCache = new ffDataStorage_Cache( $this->getFileSystem(), $this->getWPLayer(), $this->getDataStorageFactory()->createDataStorageWPOptionsNamespace() );
//        $this->_getClassloader()
    }
	
	public function createDataStorageOptionsPostType() {
		$this->_getClassloader()->loadClass('ffDataStorage_OptionsPostType');
		$this->_getClassloader()->loadClass('ffDataStorage_OptionsPostTypeRegistrator');
		return new ffDataStorage_OptionsPostType( 
			ffContainer::getInstance()->getPostLayer(),
			$this->_getWplayer()
		);
	}
	
	private $_postStorages = array();
	
	public function createDataStorageOptionsPostType_NamespaceFacade( $namespace = null, $returnExisting = false ) {
		
		$this->_getClassloader()->loadClass('ffDataStorage_OptionsPostType_NamespaceFacade');
		if( $returnExisting ) {
			if( !isset( $this->_postStorages[ $namespace] ) ) {
				
				$this->_getClassloader()->loadClass('ffDataStorage_OptionsPostType_NamespaceFacade');
				
				$dataStorage = new ffDataStorage_OptionsPostType_NamespaceFacade( $this->createDataStorageOptionsPostType(), $namespace );
				
				$this->_postStorages[ $namespace ] = $dataStorage;
			}
			
			return $this->_postStorages[ $namespace ];
		} else {

			$dataStorage = new ffDataStorage_OptionsPostType_NamespaceFacade( $this->createDataStorageOptionsPostType(), $namespace );
			return $dataStorage;
			
		}
	}
	
	public function createDataStorageWPPostMetas_NamespaceFacade( $namespace = null ) {
		$this->_getClassloader()->loadClass('ffDataStorage_WPPostMetas_NamespaceFacade');
		
		$WPPostMetas = $this->createDataStorageWPPostMetas();
		
		$WPPostMetas_NamespaceFacade = new ffDataStorage_WPPostMetas_NamespaceFacade($WPPostMetas, $namespace);
		
		return $WPPostMetas_NamespaceFacade;
	}
	
	/**
	 * 
	 * @return ffDataStorage_WPOptions
	 */
	public function createDataStorageWPOptions() {
		$this->_getClassloader()->loadClass('ffIDataStorage');
		$this->_getClassloader()->loadClass('ffDataStorage');
		$this->_getClassloader()->loadClass('ffDataStorage_WPOptions');
		$dataStorage = new ffDataStorage_WPOptions( $this->_getWplayer() );
		return $dataStorage;
	}
	
	public function createDataStorageWPOptionsNamespace( $namespace = null ) {
		$this->_getClassloader()->loadClass('ffDataStorage_WPOptions_NamespaceFacade');
			
		$WPOptions = $this->createDataStorageWPOptions();
		
		$WPOptions_NamespaceFacade = new ffDataStorage_WPOptions_NamespaceFacede($WPOptions, $namespace);
		
		return $WPOptions_NamespaceFacade;
	}

	public function createDataStorageWPSiteOptions() {
		$this->_getClassloader()->loadClass('ffIDataStorage');
		$this->_getClassloader()->loadClass('ffDataStorage');
		$this->_getClassloader()->loadClass('ffDataStorage_WPSiteOptions');
		$dataStorage = new ffDataStorage_WPSiteOptions( $this->_getWplayer() );
		return $dataStorage;
	}

	public function createDataStorageWPSiteOptionsNamespace( $namespace = null ) {
		$this->_getClassloader()->loadClass('ffDataStorage_WPSiteOptions_NamespaceFacade');

		$WPSiteOptions = $this->createDataStorageWPSiteOptions();

		$WPSiteOptions_NamespaceFacade = new ffDataStorage_WPSiteOptions_NamespaceFacade($WPSiteOptions, $namespace);

		return $WPSiteOptions_NamespaceFacade;
	}

	
	/**
	 * @return ffWPLayer
	 */
	protected function _getWplayer() {
		return $this->_WPLayer;
	}
	
	/**
	 * @param ffWPLayer $WPLayer
	 */
	protected function _setWplayer(ffWPLayer $WPLayer) {
		$this->_WPLayer = $WPLayer;
		return $this;
	}
	
	
	
}