<?php

class ffDirectDb extends ffBasicObject {
/**********************************************************************************************************************/
/* OBJECTS
/**********************************************************************************************************************/
	private $_tempQueryFilter = null;

/**********************************************************************************************************************/
/* PRIVATE VARIABLES
/**********************************************************************************************************************/


/**********************************************************************************************************************/
/* CONSTRUCT
/**********************************************************************************************************************/


/**********************************************************************************************************************/
/* PUBLIC FUNCTIONS
/**********************************************************************************************************************/
	public function actQuery( $arg ) {
		// blank filter

		return $arg;
	}

	public function disableQueryFilters() {
		global $wp_filter;

		$this->_tempQueryFilter = clone $wp_filter['query'];

		unset( $wp_filter['query'] );
//		var_dump( $this->_tempQueryFilter);
		add_filter('query', array($this, 'actQuery'));
	}

	public function enableQueryfilters() {
		if( $this->_tempQueryFilter ) {
			global $wp_filter;
			$wp_filter['query'] = $this->_tempQueryFilter;

			$this->_tempQueryFilter = null;
		}
	}

	private function _maybeJSON( $value ) {
		if( is_array( $value ) ) {
			return json_encode( $value );
		} else {
			return $value;
		}
	}

	public function setOption( $name, $value ) {
		global $wpdb;

		$value = $this->_maybeJSON( $value );

		$result = $wpdb->query($wpdb->prepare( "SELECT option_value FROM $wpdb->options WHERE option_name = '%s' LIMIT 1", array($name ) ));

		if( $result ) {
			$sql = $wpdb->prepare( "UPDATE `$wpdb->options` SET `option_value` = %s WHERE `option_name` = %s", array($value, $name ) );
			$result = $wpdb->query( $sql );
			return $result;
		} else {
			$sql = "INSERT INTO `$wpdb->options` (`option_name`, `option_value`, `autoload`) VALUES (%s, %s, 'yes') ON DUPLICATE KEY UPDATE `option_name` = VALUES(`option_name`), `option_value` = VALUES(`option_value`), `autoload` = VALUES(`autoload`)";
			$sql = $wpdb->prepare( $sql, array($name, $value ));
			return $wpdb->query( $sql );
		}
	}

	public function setPostMeta( $postId, $metaKey, $metaValue ) {
		global $wpdb;

		$metaValue = $this->_maybeJSON( $metaValue );

		$result = $wpdb->query($wpdb->prepare( "SELECT meta_id FROM $wpdb->postmeta WHERE meta_key = %s AND post_id = %d", array($metaKey, $postId ) ));

		if( $result ) {
			$result = $wpdb->query($wpdb->prepare( "UPDATE `$wpdb->postmeta` SET `meta_value` = %s WHERE `post_id` = %d AND `meta_key` = %s", array($metaValue, $postId, $metaKey ) ));
			return $result;
		} else {
			$sql = "INSERT INTO `$wpdb->postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES (%d, %s, %s)";
			$sql = $wpdb->prepare( $sql, array($postId, $metaKey, $metaValue ));
			return $wpdb->query( $sql );
		}

	}

	public function setPostContent( $postId, $postContent ) {
		global $wpdb;
		$sql = $wpdb->prepare( "UPDATE `$wpdb->posts` SET `post_content` = %s WHERE `id` = %d", array($postContent, $postId ) );
		return $wpdb->query( $sql );
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