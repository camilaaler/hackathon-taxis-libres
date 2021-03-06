<?php

class ffMenuJavascriptSaver extends ffBasicObject {
	/**********************************************************************************************************************/
	/* OBJECTS
	/**********************************************************************************************************************/

	/**
	 * @var ffScriptEnqueuer
	 */
	private $_scriptEnqueuer = null;

	/**
	 * @var ffRequest
	 */
	private $_request = null;
	/**********************************************************************************************************************/
	/* PRIVATE VARIABLES
	/**********************************************************************************************************************/

	private $_menuHasBeenEnabled = false;

	/**********************************************************************************************************************/
	/* CONSTRUCT
	/**********************************************************************************************************************/
	public function __construct( ffScriptEnqueuer $scriptEnqueuer, ffRequest $request) {
		$this->_setScriptEnqueuer( $scriptEnqueuer );
		$this->_setRequest( $request );
	}
	/**********************************************************************************************************************/
	/* PUBLIC FUNCTIONS
	/**********************************************************************************************************************/

	public function enableMenuJavascriptSave() {
		if( $this->_menuHasBeenEnabled == true ) {
			return;
		}

		$this->_menuHasBeenEnabled = true;
		$this->_getScriptEnqueuer()->addScriptFramework('ff-menu-javascript-save', '/framework/themes/menuOptions/assets/menuJavascriptSave.js');

		$this->_unserializeOurPostVariable();
	}


	/**********************************************************************************************************************/
	/* PUBLIC PROPERTIES
	/**********************************************************************************************************************/

	/**********************************************************************************************************************/
	/* PRIVATE FUNCTIONS
	/**********************************************************************************************************************/
	private function _unserializeOurPostVariable() {


		if( $this->_getRequest()->post('ff-navigation-menu-serialized') ) {


			$ourPostSerialized = $this->_getRequest()->post('ff-navigation-menu-serialized');
			$postUnserialized = array();
			$postUnserializedPHP = array();

			$this->_customParseString($ourPostSerialized, $postUnserialized);
			// gren $varToPrint = ( $postUnserialized ); $myfile = fopen(getcwd() . "/debug.txt", "a"); fwrite($myfile, (print_r($varToPrint, true)) . "\n\n"); fclose($myfile);



			if( $this->_getRequest()->get('menu') != null ) {
				$postUnserialized['menu'] = $this->_getRequest()->get('menu');
			}


			$this->_getRequest()->setPost( $postUnserialized );
			$this->_getRequest()->setRequest( $postUnserialized );


		}

	}

	private function _customParseString($string, &$result) {
		if($string==='') return false;
		$result = array();

		$pairs = explode('&', $string);

		foreach ($pairs as $pair) {

			// use the original parse_str() on each element
			parse_str($pair, $params);

			// $k=key($params); if(!isset($result[$k])) { $result+=$params; } else { $result[$k]+=$params[$k]; } // gren
			$result = $this->_array_merge_recursive_distinct($result, $params); // gren
		}

		return true;
	}

	// @author Gabriel Sobrinho <gabriel (dot) sobrinho (at) gmail (dot) com> gren (http://php.net/manual/en/function.array-merge-recursive.php)
	private function _array_merge_recursive_distinct ( array &$array1, array &$array2 ) {
		$merged = $array1;
		foreach ( $array2 as $key => &$value ) {
			if ( is_array ( $value ) && isset ( $merged [$key] ) && is_array ( $merged [$key] ) ) {
				$merged [$key] = $this->_array_merge_recursive_distinct ( $merged [$key], $value );
			} else {
				$merged [$key] = $value;
			}
		}
		return $merged;
	}


	/**********************************************************************************************************************/
	/* PRIVATE GETTERS & SETTERS
	/**********************************************************************************************************************/
	/**
	 * @return ffScriptEnqueuer
	 */
	private function _getScriptEnqueuer()
	{
		return $this->_scriptEnqueuer;
	}

	/**
	 * @param ffScriptEnqueuer $scriptEnqueuer
	 */
	private function _setScriptEnqueuer($scriptEnqueuer)
	{
		$this->_scriptEnqueuer = $scriptEnqueuer;
	}

	/**
	 * @return ffRequest
	 */
	private function _getRequest()
	{
		return $this->_request;
	}

	/**
	 * @param ffRequest $request
	 */
	private function _setRequest($request)
	{
		$this->_request = $request;
	}


}