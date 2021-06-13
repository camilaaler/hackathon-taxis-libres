<?php

class ffElProductsExtended extends ffAbstractElProductLoop {
	protected $_wcElementQuery;
	protected $_wcElementContent;

	protected function _initData() {
		$this->_setData( ffThemeBuilderElement::DATA_ID, 'woocommerce-products-extended');
		$this->_setData( ffThemeBuilderElement::DATA_NAME, 'WC - Page - Products Loop (Custom)');
		$this->_setData( ffThemeBuilderElement::DATA_HAS_DROPZONE, true);
		$this->_setData( ffThemeBuilderElement::DATA_CAN_BE_CACHED, false);

		$this->_setData( ffThemeBuilderElement::DATA_PICKER_MENU_ID, 'wc-page');
		$this->_setData( ffThemeBuilderElement::DATA_PICKER_TAGS, 'woocommerce, wc, woocommerce products extended, on sale, best selling, top rated, loop wrapper');
		$this->_setData( ffThemeBuilderElement::DATA_PICKER_ITEM_WIDTH, 0);
	}

	/**
	 * @param $s ffOneStructure|ffThemeBuilderOptionsExtender
	 * @return mixed
	 */
	protected function _getElementGeneralOptions( $s ) {
		$s->addElement( ffOneElement::TYPE_TABLE_START );

		$s->addElement( ffOneElement::TYPE_TABLE_DATA_START, '', ark_wp_kses( __('Products Settings', 'ark' ) ) );
			$s->startSection('loop');
				$select = $s->addOptionNL(ffOneOption::TYPE_SELECT, 'show','');
				$select->addSelectValue('All products', '' );
				$select->addSelectValue('On sale products', 'on_sale' );
				$select->addSelectValue('Best selling products', 'best_selling' );
				$select->addSelectValue('Top rated products', 'top_rated' );
				$select->addParam( ffOneOption::PARAM_TITLE_AFTER, ark_wp_kses( __('Show', 'ark' ) ) );

				$select = $s->addOptionNL(ffOneOption::TYPE_SELECT, 'orderby', '', 'title');
				$select->addSelectValue('The date the product was published', 'date' );
				$select->addSelectValue('The post ID of the product', 'ID' );
				$select->addSelectValue('The Menu Order, if set (lower numbers display first)', 'menu_order' );
				$select->addSelectValue('The number of purchases', 'popularity' );
				$select->addSelectValue('Randomly order the products on page load (may not work with sites that use caching)', 'rand' );
				$select->addSelectValue('The product title', 'title' );
				$select->addParam( ffOneOption::PARAM_TITLE_AFTER, ark_wp_kses( __('Order by', 'ark' ) ) );

				$select = $s->addOptionNL(ffOneOption::TYPE_SELECT, 'order', '', 'asc');
				$select->addSelectValue('Ascending ( 1, 2, 3 )', 'asc' );
				$select->addSelectValue('Descending ( 3, 2, 1 )', 'desc' );
				$select->addParam( ffOneOption::PARAM_TITLE_AFTER, ark_wp_kses( __('Order', 'ark' ) ) );

				$s->addOption( ffOneOption::TYPE_TEXT, 'limit', '', '12' )
					->addParam( ffOneOption::PARAM_TITLE_AFTER, __('Maximum Product Count', 'ark') );

				$s->addOption( ffOneOption::TYPE_TEXTAREA_STRICT, 'additional_attributes', 'Content Product Attributes (for advanced users)', '' );
				$s->addElement(ffOneElement::TYPE_DESCRIPTION, '', 'You can find these attributes in the WooCommerce <a href="https://docs.woocommerce.com/document/woocommerce-shortcodes/#content-product-attributes" target="_blank">help page in the section Content Product Attributes.</a>' );
				$s->addElement(ffOneElement::TYPE_DESCRIPTION, '', 'Use in format <code>cat_operator="NOT IN" category="11,12,13"</code>' );

			$s->endSection();
		$s->addElement( ffOneElement::TYPE_TABLE_DATA_END);

		$this->_getElementColumnOptions( $s );

		$this->_getElementProductInTheLoopWrapper( $s );

		$s->addElement(ffOneElement::TYPE_TABLE_END);
	}

	public function setTemplateToEmpty( $template, $template_name, $template_path ){

//		echo "\n\n\n".$template_path."\n\n\n";

		if( ( 'content' == $template_name ) and ( 'product' == $template_path ) ){
			return $this->getEmptyWCTemplatePath();
		}
		return $template;
	}

	public function simulateTemplate($template, $template_name, $template_path){
		if( 'content' != $template_name ){
			return;
		}

		if( 'product' != $template_path ){
			return;
		}

		$this->_advancedToggleBoxStart( $this->_wcElementQuery, 'product-wrapper' );
		echo '<li ';
		post_class();
		echo '>';
		echo ( $this->_doShortcode( $this->_wcElementContent ) );
		echo '</li>';
		$this->_advancedToggleBoxEnd( $this->_wcElementQuery, 'product-wrapper' );
	}

	protected function _render( ffOptionsQueryDynamic $query, $content, $data, $uniqueId ) {
		$this->_renderCSSForColumns( $query->get('grid') );
		$this->_wcElementQuery = $query;
		$this->_wcElementContent = $content;

		echo '<div class="ark-woocommerce-product-extended">';

		/**
		 * see difference in functions - see hooks
		 * wc_get_template_part();
		 * wc_get_template();
		 *
		 * WC_Shortcodes::products();
		 * WC_Shortcode_Products::get_content();
		 *
		 * Sorry it is not possible in another way :(
		 */

		add_filter( 'wc_get_template_part', array($this, 'setTemplateToEmpty'), 10, 3 );
		add_filter( 'wc_get_template_part', array($this, 'simulateTemplate'), 10, 3 );


		$shortcode_atts = array();
		$shortcode_atts[] = 'columns="999"';

		switch ( $query->get('loop show') ){
			case 'on_sale':
				$shortcode_atts[] = 'on_sale="true"';
				break;
			case 'best_selling':
				$shortcode_atts[] = 'best_selling="true"';
				break;
			case 'top_rated':
				$shortcode_atts[] = 'top_rated="true"';
				break;
		}

		$shortcode_atts[] = 'orderby="' . $query->get('loop orderby') . '"';
		$shortcode_atts[] = 'order="' . $query->get('loop order') . '"';
		$shortcode_atts[] = 'limit="' . $query->get('loop limit') . '"';
		$shortcode_atts[] = $query->get('loop additional_attributes');

		$shortcode = '[products ';
		$shortcode .= implode( ' ', $shortcode_atts);
		$shortcode .= ']';

		echo '<!-- '.$shortcode.' -->';
		echo do_shortcode( $shortcode );

		add_filter( 'wc_get_template_part', array($this, 'simulateTemplate'), 10 );
		remove_filter( 'wc_get_template_part', array($this, 'setTemplateToEmpty'), 10 );

		echo '</div>';
	}


	protected function _renderContentInfo_JS() {
		?>
		<script data-type="ffscript">
			function ( query, options, $elementPreview, $element ) {
			}
		</script data-type="ffscript">
		<?php
	}

}