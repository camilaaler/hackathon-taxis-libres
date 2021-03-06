<?php

abstract class ffMetaBoxView extends ffBasicObject {
	const USED_META_BOX_COLLECTOR_NAME = 'ff-used-meta-box-classes';
	
	private $_params = null;
	
	private $_visibility = array();
	
	public function setVisibility( $visibility ) {
		$this->_visibility = $visibility;

	}
	
	public function ajaxRequest( ffAjaxRequest $ajaxRequest ) {
		
	}	
	
	public function setParams( $params ) {
		$this->_params = $params;
	}
	
	protected function _getParam( $name, $default = null ) {
		if( isset( $this->_params[ $name ] ) ) {
			return $this->_params[ $name ];
		} else {
			return $default;
		}
	} 
	
	protected function _printVisibility() {
	
		echo '<div class="ff-metabox-visibility">';
			foreach( $this->_visibility as $typeName => $visibilityGroup ) {
				echo '<div class="ff-one-visibility" data-type="'.$typeName.'">';
					foreach( $visibilityGroup as $oneItem ) {
						echo '<div class="ff-one-visibility-item">';
							echo $oneItem;
						echo '</div>';
					}  
				echo '</div>';
			}
		echo '</div>';	
	}
	
	public function render($post) {
        $this->_beforeRendering();
		$container = ffContainer::getInstance();
		$container->getFrameworkScriptLoader()
					->requireFrsLib()
					->requireFrsLibMetaboxes();
		
		echo '<input type="hidden" name="'.ffMetaBoxView::USED_META_BOX_COLLECTOR_NAME.'[]" value="'. str_replace('View','', get_class( $this ) ).'" >';

		$this->_printVisibility();
		$this->_requireAssets();
		
		
		
		echo '<div class="ff-metabox-content">';

		if( $this->_getParam( ffMetaBox::PARAM_NORMALIZE_OPTIONS ) == true ) {

            $classes = array();
            $classes[] = 'ff-metabox-normalize-options';
            if( $this->_getParam( ffMetaBox::PARAM_USE_OLD_CONVERTOR  ) == true ) {

                $classes[] = 'ff-metabox-normalize-options-use-old-convertor';
            }

            if( $this->_getParam( ffMetaBox::PARAM_NORMALIZE_OPTIONS_TO_ONE_INPUT  ) == true ) {

                $classes[] = 'ff-metabox-normalize-options-to-one-input';
            }

            $classesString = implode(' ', $classes);

			echo '<div class="'.$classesString.'">';
		}
		
			$this->_render($post);

		if( $this->_getParam( ffMetaBox::PARAM_NORMALIZE_OPTIONS ) == true ) {
			echo '</div>';
		}
		
		echo '</div>';
	}
	
	public function save($postId) {
		$this->_save($postId);
	}
	
	protected function _requireAssets() {
		
	}

    protected function _beforeRendering() {

    }
	
	abstract protected function _render( $post);
	
	abstract protected function _save( $postId );
}