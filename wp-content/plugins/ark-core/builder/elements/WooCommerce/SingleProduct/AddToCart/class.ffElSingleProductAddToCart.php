<?php

class ffElSingleProductAddToCart extends ffThemeBuilderElementBasic {
	protected function _initData() {
		$this->_setData( ffThemeBuilderElement::DATA_ID, 'woocommerce-single-product-add-to-cart');
		$this->_setData( ffThemeBuilderElement::DATA_NAME, 'WC - Single - Add To Cart');
		$this->_setData( ffThemeBuilderElement::DATA_HAS_DROPZONE, false);
		$this->_setData( ffThemeBuilderElement::DATA_CAN_BE_CACHED, false);

		$this->_setData( ffThemeBuilderElement::DATA_PICKER_MENU_ID, 'wc-single');
		$this->_setData( ffThemeBuilderElement::DATA_PICKER_TAGS, 'woocommerce, wc, add to cart, single product');
		$this->_setData( ffThemeBuilderElement::DATA_PICKER_ITEM_WIDTH, 0);
	}

	protected function _getElementGeneralOptions( $s ) {
		$s->addElement( ffOneElement::TYPE_TABLE_START );

		$s->addElement(ffOneElement::TYPE_TABLE_DATA_START, '', 'Note');
		$s->addElement(ffOneElement::TYPE_DESCRIPTION, '', 'This element should be placed in the <strong>WC - Single - Product Wrapper</strong>.' );
		$s->addElement(ffOneElement::TYPE_TABLE_DATA_END);

		$s->addElement(ffOneElement::TYPE_TABLE_DATA_START, '', 'Wrapping');
		$s->addOption(ffOneOption::TYPE_CHECKBOX, 'is-wrapped', 'Wrap by &nbsp;', 1);
		$s->addOption(ffOneOption::TYPE_TEXT, 'tag', '', 'div');
		$s->addElement(ffOneElement::TYPE_DESCRIPTION, '', 'If you have problems - for example - after you added the new plugin, check this option.' );
		$s->addElement(ffOneElement::TYPE_TABLE_DATA_END);

			$s->addElement( ffOneElement::TYPE_TABLE_DATA_START, '',  __('Button Colors Active', 'ark') );
				$s->startSection('button');
					$s->addElement(ffOneElement::TYPE_HTML, '', '<div class="row"><div class="col-md-6">' );
						$s->addOptionNL( ffOneOption::TYPE_COLOR_PICKER_WITH_LIB, 'color', '', '')
							->addParam( ffOneOption::PARAM_TITLE_AFTER, __('Text', 'ark') );
						$s->addOptionNL( ffOneOption::TYPE_COLOR_PICKER_WITH_LIB, 'border-color', '', '')
							->addParam( ffOneOption::PARAM_TITLE_AFTER, __('Border', 'ark') );
						$s->addOptionNL( ffOneOption::TYPE_COLOR_PICKER_WITH_LIB, 'bg-color', '', '')
							->addParam( ffOneOption::PARAM_TITLE_AFTER, __('Background', 'ark') );
					$s->addElement(ffOneElement::TYPE_HTML, '', '</div><div class="col-md-6">' );
						$s->addOptionNL( ffOneOption::TYPE_COLOR_PICKER_WITH_LIB, 'color-hover', '', '')
							->addParam( ffOneOption::PARAM_TITLE_AFTER, __('Text Hover', 'ark') );
						$s->addOptionNL( ffOneOption::TYPE_COLOR_PICKER_WITH_LIB, 'border-color-hover', '', '')
							->addParam( ffOneOption::PARAM_TITLE_AFTER, __('Border Hover', 'ark') );
						$s->addOptionNL( ffOneOption::TYPE_COLOR_PICKER_WITH_LIB, 'bg-color-hover', '', '')
						->addParam( ffOneOption::PARAM_TITLE_AFTER, __('Background Hover', 'ark') );
					$s->addElement(ffOneElement::TYPE_HTML, '', '</div></div>' );
				$s->endSection();
			$s->addElement(ffOneElement::TYPE_TABLE_DATA_END);

			$s->addElement( ffOneElement::TYPE_TABLE_DATA_START, '',  __('Button Colors Inactive', 'ark') );
				$s->startSection('button-inactive');
					$s->addElement(ffOneElement::TYPE_DESCRIPTION, '', 'Inactive Button before you did not choose variant in Variable product' );
					$s->addElement(ffOneElement::TYPE_HTML, '', '<div class="row"><div class="col-md-6">' );
						$s->addOptionNL( ffOneOption::TYPE_COLOR_PICKER_WITH_LIB, 'color', '', '')
							->addParam( ffOneOption::PARAM_TITLE_AFTER, __('Text', 'ark') );
						$s->addOptionNL( ffOneOption::TYPE_COLOR_PICKER_WITH_LIB, 'border-color', '', '')
							->addParam( ffOneOption::PARAM_TITLE_AFTER, __('Border', 'ark') );
						$s->addOptionNL( ffOneOption::TYPE_COLOR_PICKER_WITH_LIB, 'bg-color', '', '')
							->addParam( ffOneOption::PARAM_TITLE_AFTER, __('Background', 'ark') );
					$s->addElement(ffOneElement::TYPE_HTML, '', '</div><div class="col-md-6">' );
						$s->addOptionNL( ffOneOption::TYPE_COLOR_PICKER_WITH_LIB, 'color-hover', '', '')
							->addParam( ffOneOption::PARAM_TITLE_AFTER, __('Text Hover', 'ark') );
						$s->addOptionNL( ffOneOption::TYPE_COLOR_PICKER_WITH_LIB, 'border-color-hover', '', '')
							->addParam( ffOneOption::PARAM_TITLE_AFTER, __('Border Hover', 'ark') );
						$s->addOptionNL( ffOneOption::TYPE_COLOR_PICKER_WITH_LIB, 'bg-color-hover', '', '')
							->addParam( ffOneOption::PARAM_TITLE_AFTER, __('Background Hover', 'ark') );
					$s->addElement(ffOneElement::TYPE_HTML, '', '</div></div>' );
				$s->endSection();
			$s->addElement( ffOneElement::TYPE_TABLE_DATA_END);		$s->addElement(ffOneElement::TYPE_TABLE_END);
	}

	protected function _render( ffOptionsQueryDynamic $query, $content, $data, $uniqueId ) {
		$is_wrapped = $query->get('is-wrapped');
		$tag = $query->get('tag');
		if( $is_wrapped ){
			echo '<'.$tag.' class="ark-woocommerce-single-product-add-to-cart">';
		}
		woocommerce_template_single_add_to_cart();
		echo '<style>.ark-woocommerce-single-product-add-to-cart .label{font-size:inherit;color:inherit;}</style>';
		if( $is_wrapped ){
			echo '</'.$tag.'>';
		}

		/* button */

		/* normal */
		$this->_renderCSSRuleImportant( 'color', $query->getColor('button color'), '.single_add_to_cart_button');
		$this->_renderCSSRuleImportant( 'background-color', $query->getColor('button bg-color'), '.single_add_to_cart_button');
		$this->_renderCSSRuleImportant( 'border-color', $query->getColor('button border-color'), '.single_add_to_cart_button');

		/* hover */
		$this->_renderCSSRuleImportant( 'color', $query->getColor('button color-hover'), '.single_add_to_cart_button:hover');
		$this->_renderCSSRuleImportant( 'background-color', $query->getColor('button bg-color-hover'), '.single_add_to_cart_button:hover');
		$this->_renderCSSRuleImportant( 'border-color', $query->getColor('button border-color-hover'), '.single_add_to_cart_button:hover');

		/* button - inactive */

		/* normal */
		$this->_renderCSSRuleImportant( 'color', $query->getColor('button-inactive color'), '.single_add_to_cart_button.disabled');
		$this->_renderCSSRuleImportant( 'background-color', $query->getColor('button-inactive bg-color'), '.single_add_to_cart_button.disabled');
		$this->_renderCSSRuleImportant( 'border-color', $query->getColor('button-inactive border-color'), '.single_add_to_cart_button.disabled');

		/* hover */
		$this->_renderCSSRuleImportant( 'color', $query->getColor('button-inactive color-hover'), '.single_add_to_cart_button.disabled:hover');
		$this->_renderCSSRuleImportant( 'background-color', $query->getColor('button-inactive bg-color-hover'), '.single_add_to_cart_button.disabled:hover');
		$this->_renderCSSRuleImportant( 'border-color', $query->getColor('button-inactive border-color-hover'), '.single_add_to_cart_button.disabled:hover');

	}


	protected function _renderContentInfo_JS() {
		?>
		<script data-type="ffscript">
			function ( query, options, $elementPreview, $element ) {
				query.addPlainText('WooCommerce - Single Product');
				query.addBreak();
				query.addHeadingSm(null, 'Add to Cart');
			}
		</script data-type="ffscript">
		<?php
	}

}