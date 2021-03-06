<?php

class ffOneStructure extends ffBasicObject {
	/******************************************************************************/
	/* VARIABLES AND CONSTANTS
	 /******************************************************************************/
	private $_data = array();	// stores sections and options

    private $_referenceSections = array();

	private $_currentSectionBuffer = array(); 	// pointer to the most current
	// section, so we can add
	// more things ( opt and sect)
	// there

	private $_name = null;
	
	/**
	 * 
	 * @var ffOneOption_Factory
	 */
	private $_oneOptionFactory = null;

    private $_uniqueHash = null;

	/**
	 * 
	 * @var ffOneSection_Factory
	 */
	private $_oneSectionFactory = null;

	/**
	 * @var ffOptionsStructureHelper
	 */
	private $_optionsStructureHelper = null;
	
	/******************************************************************************/
	/* CONSTRUCT AND PUBLIC FUNCTIONS
	 /******************************************************************************/
	/**
	 * ffOneStructure constructor.
	 * @param null $name
	 * @param ffOneOption_Factory $oneOptionFactory
	 * @param ffOneSection_Factory $oneSectionFactory
	 * @param ffOptionsStructureHelper $optionsStructureHelper
	 */
	public function __construct( $name = null, ffOneOption_Factory $oneOptionFactory, ffOneSection_Factory $oneSectionFactory, $optionsStructureHelper ) {
		$this->_name = $name;
		$this->_setOneoptionfactory($oneOptionFactory);
		$this->_setOnesectionfactory($oneSectionFactory);
		$this->_setOptionsStructureHelper( $optionsStructureHelper );
		$optionsStructureHelper->setStructure( $this );
	}

    public function __sleep() {
//        $this->_oneOptionFactory = null;
//        $this->_oneSectionFactory = null;

        $toReturn = array();
        $toReturn[] = '_data';
        $toReturn[] = '_currentSectionBuffer';
        $toReturn[] = '_name';
        $toReturn[] = '_uniqueHash';
//        $toReturn['_data'] = $this->_data;
//        $toReturn['_currentSectionBuffer'] = $this->_currentSectionBuffer;
//        $toReturn['_name'] = $this->_name;
//        $toReturn['_uniqueHash'] = $this->_uniqueHash;

        return $toReturn;
    }

    public function __wakeup() {
        $optionsFactory = ffContainer()->getOptionsFactory();
        $this->_oneOptionFactory = $optionsFactory->getOneOptionFactory();
        $this->_oneSectionFactory = $optionsFactory->getOneSectionFactory();
    }


    public function startReferenceSection( $id, $type = null ) {
        $newSection = $this->_getOnesectionfactory()->createOneSection($id, $type);
        $newSection->addParam('is-reference', true);

        if( empty( $this->_currentSectionBuffer ) ) {
			$this->_data[] = $newSection;
		} else {
            if( !isset($this->_referenceSections[ $id ]) ) {
                $this->_getCurrentSection()->addSection( $newSection );
                $this->_referenceSections[ $id ] = $newSection;
            } else {
                $this->_getCurrentSection()->addSection( $this->_referenceSections[ $id ] );
            }

		}

		$this->_addSectionToBuffer($newSection);
		return $newSection;
    }

    public function endReferenceSection() {
        $this->_removeSectionFromBuffer();
    }

	/**
	 *
	 * @param string $id
	 * @param string $type
	 * @return ffOneSection
	 */
	public function startSection( $id, $type = null ) {
		$newSection = $this->_getOnesectionfactory()->createOneSection($id, $type);

		if( empty( $this->_currentSectionBuffer ) ) {
			$this->_data[] = $newSection;
		} else {
			$this->_getCurrentSection()->addSection( $newSection );
		}

		$this->_addSectionToBuffer($newSection);

		return $newSection;
	}
	
	public function insertStructure( $structure ) {
		$data = $structure->getData();
		if( is_array( $data ) ) {
			foreach( $data as $newSection ) {
				if( empty( $this->_currentSectionBuffer ) ) {
					$this->_data[] = $newSection;
				} else {
					$this->_getCurrentSection()->addSection( $newSection );
					$this->_removeSectionFromBuffer();
				}
			}
		}
	}

	/**
	 * End section
	 */
	public function endSection() {
		$section = $this->_removeSectionFromBuffer();
        unset( $section );
	}
	
	

	/**
	 *
	 * @param string $type
	 * @param string $id
	 * @param string $title
	 * @param string $defaultValue
	 * @param string $description
	 * @return ffOneOption
	 */
	public function addOption( $type, $id, $title = '', $defaultValue = '', $description = '' ) {
        $this->_addToHash( $title );
		$newOption = $this->_getOneoptionfactory()->createOneOption( $type, $id, $title, $defaultValue, $description );
		$this->_getCurrentSection()->addOption($newOption);

		return $newOption;
	}

    public function addOptionNL( $type, $id, $title = '', $defaultValue = '', $description = '' ) {

        $newOption = $this->addOption(  $type, $id, $title, $defaultValue, $description );
        $this->addElement( ffOneElement::TYPE_NEW_LINE);

        return $newOption;

    }
	
	public function addElement( $type, $id = '', $title = '', $description = '' ) {
		$newElement = $this->_getOneoptionfactory()->createOneElement($type, $id, $title, $description);
		$this->_getCurrentSection()->addElement( $newElement );
		
		return $newElement;
	}

    public function getUniqueHash() {
        return md5( $this->_uniqueHash );
    }

	/******************************************************************************/
	/* PRIVATE FUNCTIONS
	 /******************************************************************************/
	/**
	 *
	 * @return ffOneSection
	 */
	private function _getCurrentSection() {
		if( empty( $this->_currentSectionBuffer ) ) {
			return null;
		} else {
			return end( $this->_currentSectionBuffer );
		}
	}

    private function _addToHash( $string ) {
        if( $this->_uniqueHash == null ) {
            $this->_uniqueHash = '';
        }

        $this->_uniqueHash .= $string;
    }

	private function _addSectionToBuffer( $newSection ) {
		$this->_currentSectionBuffer[] = $newSection;
	}

	private function _removeSectionFromBuffer() {
		return array_pop( $this->_currentSectionBuffer );
	}
	/******************************************************************************/
	/* SETTERS AND GETTERS
	 /******************************************************************************/
	public function getData() {
		return $this->_data;
	}

	public function getType() {
		return ffOneSection::TYPE_NORMAL;
	}

	public function isContainer() {
		return true;
	}

	/**
	 * @return ffOneOption_Factory
	 */
	protected function _getOneoptionfactory() {
		return $this->_oneOptionFactory;
	}
	
	/**
	 * @param ffOneOption_Factory $_oneOptionFactory
	 */
	protected function _setOneoptionfactory(ffOneOption_Factory $oneOptionFactory) {
		$this->_oneOptionFactory = $oneOptionFactory;
		return $this;
	}
	
	/**
	 * @return ffOneSection_Factory
	 */
	protected function _getOnesectionfactory() {
		return $this->_oneSectionFactory;
	}
	
	/**
	 * @param ffOneSection_Factory $_oneSectionFactory
	 */
	protected function _setOnesectionfactory(ffOneSection_Factory $oneSectionFactory) {
		$this->_oneSectionFactory = $oneSectionFactory;
		return $this;
	}

	/**
	 * @return ffOptionsStructureHelper
	 */
	public function getOptionsStructureHelper() {
		return $this->_optionsStructureHelper;
	}

	/**
	 * @param ffOptionsStructureHelper $optionsStructureHelper
	 */
	private function _setOptionsStructureHelper($optionsStructureHelper) {
		$this->_optionsStructureHelper = $optionsStructureHelper;
	}
	


}