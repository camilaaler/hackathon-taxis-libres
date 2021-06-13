<?php

class ffElSingleProductSaleFlash extends ffThemeBuilderElementBasic {
	protected function _initData() {
		$this->_setData( ffThemeBuilderElement::DATA_ID, 'woocommerce-single-product-sale-flash');
		$this->_setData( ffThemeBuilderElement::DATA_NAME, 'WC - Single - Sale');
		$this->_setData( ffThemeBuilderElement::DATA_HAS_DROPZONE, false);
		$this->_setData( ffThemeBuilderElement::DATA_CAN_BE_CACHED, false);

		$this->_setData( ffThemeBuilderElement::DATA_PICKER_MENU_ID, 'wc-single');
		$this->_setData( ffThemeBuilderElement::DATA_PICKER_TAGS, 'woocommerce, wc, sale flash, single product');
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

			$s->addElement( ffOneElement::TYPE_TABLE_DATA_START, '',  __('Sale Colors', 'ark') );
				$s->startSection('sale-colors');
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


		$s->addElement(ffOneElement::TYPE_TABLE_END);
	}

	protected function _render( ffOptionsQueryDynamic $query, $content, $data, $uniqueId ) {
		$is_wrapped = $query->get('is-wrapped');
		$tag = $query->get('tag');
		if( $is_wrapped ) {
			echo '<'.$tag.' class="ark-woocommerce-single-product-sale-flash">';
		}
		woocommerce_show_product_sale_flash();
		if( $is_wrapped ){
			echo '</'.$tag.'>';
		}

		/* normal */
		$this->_renderCSSRuleImportant( 'color', $query->getColor('sale-colors color'), '.onsale');
		$this->_renderCSSRuleImportant( 'background-color', $query->getColor('sale-colors bg-color'), '.onsale');
		if( $query->getColor('sale-colors border-color') ) {
			$this->_renderCSSRuleImportant( 'border', '1px solid' . $query->getColor( 'sale-colors border-color' ), '.onsale' );
		}

		/* hover */
		$this->_renderCSSRuleImportant( 'color', $query->getColor('sale-colors color-hover'), '.onsale:hover');
		$this->_renderCSSRuleImportant( 'background-color', $query->getColor('sale-colors bg-color-hover'), '.onsale:hover');
		if( $query->getColor('sale-colors border-color-hover') ) {
			$this->_renderCSSRuleImportant( 'border-color', '1px solid' . $query->getColor( 'sale-colors border-color-hover' ), '.onsale:hover' );
		}
	}


	protected function _renderContentInfo_JS() {
		?>
		<script data-type="ffscript">
			function ( query, options, $elementPreview, $element ) {
				query.addPlainText('WooCommerce - Single Product');
				query.addBreak();
				query.addHeadingSm(null, 'Sale Flash');
			}
		</script data-type="ffscript">
		<?php
	}

}