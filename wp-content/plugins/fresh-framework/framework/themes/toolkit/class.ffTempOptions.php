<?php

class ffTempOptions extends ffBasicObject {
/**********************************************************************************************************************/
/* OBJECTS
/**********************************************************************************************************************/
	private static $_instance = null;

/**********************************************************************************************************************/
/* PRIVATE VARIABLES
/**********************************************************************************************************************/
	private $_data = array();

	private $_defaultNamespace = 'def';
/**********************************************************************************************************************/
/* CONSTRUCT
/**********************************************************************************************************************/


/**********************************************************************************************************************/
/* PUBLIC FUNCTIONS
/**********************************************************************************************************************/
	public static function getInstance() {
		if( self::$_instance == null ) {
			self::$_instance = new ffTempOptions();
		}

		return self::$_instance;
	}

	public function setData( $name, $value ) {
		return $this->setDataNamespace( $this->_defaultNamespace, $name, $value);
	}

	public function getData( $name, $default = null ) {
		return $this->getDataNamespace( $this->_defaultNamespace, $name, $default);
	}

	public function setDataNamespace( $namespace, $name, $value ) {
		if( !isset( $this->_data[ $namespace ] ) ) {
			$this->_data[ $namespace ] = array();
		}

		$this->_data[$namespace][$name] = $value;
	}

	public function getDataNamespace( $namespace, $name, $default = null ) {
		if( !isset( $this->_data[ $namespace ] ) || !isset( $this->_data[ $namespace ][$name] )) {
			return $default;
		}

		return $this->_data[ $namespace ][$name];
	}
/**********************************************************************************************************************/
/* PUBLIC PROPERTIES
/**********************************************************************************************************************/


/**********************************************************************************************************************/
/* PRIVATE FUNCTIONS
/**********************************************************************************************************************/


/**********************************************************************************************************************/
/* ABSTRACT FUNCTIONS
/**********************************************************************************************************************/


/**********************************************************************************************************************/
/* PRIVATE GETTERS & SETTERS
/**********************************************************************************************************************/
}

function ff_set_tmp_opt( $name, $value ) {
	return ffTempOptions::getInstance()->setData( $name, $value );
}

function ff_get_tmp_opt( $name, $default = null ) {
	return ffTempOptions::getInstance()->getData( $name, $default);
}

function ff_set_tmp_opt_namespace($namespace, $name, $value ) {
	return ffTempOptions::getInstance()->setDataNamespace($namespace, $name, $value );
}

function ff_get_tmp_opt_namespace($namespace, $name, $default = null ) {
	return ffTempOptions::getInstance()->getDataNamespace($namespace, $name, $default);
}