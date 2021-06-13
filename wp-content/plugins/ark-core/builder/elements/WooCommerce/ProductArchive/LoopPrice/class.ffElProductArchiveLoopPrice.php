<?php

class ffElProductArchiveLoopPrice extends ffThemeBuilderElementBasic {
	protected function _initData() {
		$this->_setData( ffThemeBuilderElement::DATA_ID, 'woocommerce-archive-product-price');
		$this->_setData( ffThemeBuilderElement::DATA_NAME, 'WC - Archive - Item - Price');
		$this->_setData( ffThemeBuilderElement::DATA_HAS_DROPZONE, false);
		$this->_setData( ffThemeBuilderElement::DATA_CAN_BE_CACHED, false);

		$this->_setData( ffThemeBuilderElement::DATA_PICKER_MENU_ID, 'wc-archive-item');
		$this->_setData( ffThemeBuilderElement::DATA_PICKER_TAGS, 'woocommerce, wc, archive, price');
		$this->_setData( ffThemeBuilderElement::DATA_PICKER_ITEM_WIDTH, 0);
	}

	protected function _getElementGeneralOptions( $s ) {
		$s->addElement( ffOneElement::TYPE_TABLE_START );

		$s->addElement(ffOneElement::TYPE_TABLE_DATA_START, '', 'Note');
		$s->addElement(ffOneElement::TYPE_DESCRIPTION, '', 'This element should be placed in the:' );
		$s->addElement(ffOneElement::TYPE_DESCRIPTION, '', '&nbsp; <strong> &bull; WC - Archive - Products Loop</strong>' );
		$s->addElement(ffOneElement::TYPE_DESCRIPTION, '', '&nbsp; <strong> &bull; WC - Single - Related Products</strong>' );
		$s->addElement(ffOneElement::TYPE_DESCRIPTION, '', '&nbsp; <strong> &bull; WC - Single - Upsell</strong>' );
		$s->addElement(ffOneElement::TYPE_DESCRIPTION, '', '&nbsp; <strong> &bull; WC - Page - Products Loop (Custom)</strong>' );
		$s->addElement(ffOneElement::TYPE_TABLE_DATA_END);

		$s->addElement(ffOneElement::TYPE_TABLE_DATA_START, '', 'Wrapping');
		$s->addOption(ffOneOption::TYPE_CHECKBOX, 'is-wrapped', 'Wrap by &nbsp;', 1);
		$s->addOption(ffOneOption::TYPE_TEXT, 'tag', '', 'div');
		$s->addElement(ffOneElement::TYPE_DESCRIPTION, '', 'If you have problems - for example - after you added the new plugin, check this option.' );
		$s->addOption(ffOneOption::TYPE_CHECKBOX, 'is-wrapped-by-link', 'Wrap link to single product page', 1);
		$s->addElement(ffOneElement::TYPE_TABLE_DATA_END);

			$s->addElement( ffOneElement::TYPE_TABLE_DATA_START, '',  __('Colors', 'ark') );
				$s->startSection('price');
					$s->addOptionNL( ffOneOption::TYPE_COLOR_PICKER_WITH_LIB, 'color', '', '')
						->addParam( ffOneOption::PARAM_TITLE_AFTER, __('Price', 'ark') );
					$s->addOptionNL( ffOneOption::TYPE_COLOR_PICKER_WITH_LIB, 'color-hover', '', '')
						->addParam( ffOneOption::PARAM_TITLE_AFTER, __('Price Hover', 'ark') );
				$s->endSection();
				$s->startSection('price-before');
					$s->addOptionNL( ffOneOption::TYPE_COLOR_PICKER_WITH_LIB, 'color', '', '')
						->addParam( ffOneOption::PARAM_TITLE_AFTER, __('Price Before Sale', 'ark') );
					$s->addOptionNL( ffOneOption::TYPE_COLOR_PICKER_WITH_LIB, 'color-hover', '', '')
						->addParam( ffOneOption::PARAM_TITLE_AFTER, __('Price Before Sale Hover', 'ark') );
				$s->endSection();
			$s->addElement(ffOneElement::TYPE_TABLE_DATA_END);

		$s->addElement(ffOneElement::TYPE_TABLE_END);
	}

	protected function _render( ffOptionsQueryDynamic $query, $content, $data, $uniqueId ) {
		$is_wrapped = $query->get('is-wrapped');
		$tag = $query->get('tag');
		$has_link = $query->get('is-wrapped-by-link');
		if( $is_wrapped ) {
			echo '<'.$tag.' class="ark-woocommerce-single-product-price">';
		}
		if($has_link){
			woocommerce_template_loop_product_link_open();
		}
		woocommerce_template_loop_price();
		if($has_link){
			woocommerce_template_loop_product_link_close();
		}
		if( $is_wrapped ){
			echo '</'.$tag.'>';
		}


		$this->_renderCSSRuleImportant( 'color', $query->getColor('price color'), '.price');
		$this->_renderCSSRuleImportant( 'color', $query->getColor('price color-hover'), '.price:hover');
		$this->_renderCSSRuleImportant( 'color', $query->getColor('price color-hover'), 'a:hover .price');

		if( $query->getColor('price-before color') or $query->getColor('price-before color-hover') ){
			$this->_renderCSSRuleImportant( 'opacity', '1', '.price del');
		}
		$this->_renderCSSRuleImportant( 'color', $query->getColor('price-before color'), '.price del');
		$this->_renderCSSRuleImportant( 'color', $query->getColor('price-before color-hover'), '.price:hover del');
		$this->_renderCSSRuleImportant( 'color', $query->getColor('price-before color-hover'), 'a:hover .price del');

	}


	protected function _renderContentInfo_JS() {
		?>
		<script data-type="ffscript">
			function ( query, options, $elementPreview, $element ) {
				query.addPlainText('WooCommerce - Product Archive Loop');
				query.addBreak();
				query.addHeadingSm(null, 'Price');
			}
		</script data-type="ffscript">
		<?php
	}

}