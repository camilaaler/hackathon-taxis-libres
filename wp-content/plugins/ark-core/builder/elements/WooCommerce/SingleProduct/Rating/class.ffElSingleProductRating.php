<?php

class ffElSingleProductRating extends ffThemeBuilderElementBasic {
	protected function _initData() {
		$this->_setData( ffThemeBuilderElement::DATA_ID, 'woocommerce-single-product-rating');
		$this->_setData( ffThemeBuilderElement::DATA_NAME, 'WC - Single - Rating');
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

			$s->addElement( ffOneElement::TYPE_TABLE_DATA_START, '',  __('Star Colors', 'ark') );
				$s->startSection('star-colors');
					$s->addOptionNL( ffOneOption::TYPE_COLOR_PICKER_WITH_LIB, 'color', '', '')
						->addParam( ffOneOption::PARAM_TITLE_AFTER, __('Stars', 'ark') );
					$s->addOptionNL( ffOneOption::TYPE_COLOR_PICKER_WITH_LIB, 'inactive-color', '', '')
						->addParam( ffOneOption::PARAM_TITLE_AFTER, __('Inactive Stars', 'ark') );
				$s->endSection();
				$s->addOptionNL( ffOneOption::TYPE_COLOR_PICKER_WITH_LIB, 'link-to-reviews-color', '', '')
				  ->addParam( ffOneOption::PARAM_TITLE_AFTER, __('Link to reviews', 'ark') );
			$s->addElement(ffOneElement::TYPE_TABLE_DATA_END);

		$s->addElement(ffOneElement::TYPE_TABLE_END);
	}

	protected function _render( ffOptionsQueryDynamic $query, $content, $data, $uniqueId ) {
		$is_wrapped = $query->get('is-wrapped');
		$tag = $query->get('tag');
		if( $is_wrapped ) {
			echo '<'.$tag.' class="ark-woocommerce-single-product-rating">';
		}
		woocommerce_template_single_rating();
		if( $is_wrapped ){
			echo '</'.$tag.'>';
		}

		$this->_renderCSSRule( 'color', $query->getColor('star-colors color'), '.star-rating > span');
		$this->_renderCSSRule( 'color', $query->getColor('star-colors inactive-color'), '.star-rating:before');
		$this->_renderCSSRule( 'color', $query->getColor('link-to-reviews-color'), '.woocommerce-review-link');


	}


	protected function _renderContentInfo_JS() {
		?>
		<script data-type="ffscript">
			function ( query, options, $elementPreview, $element ) {
				query.addPlainText('WooCommerce - Single Product');
				query.addBreak();
				query.addHeadingSm(null, 'Rating');
			}
		</script data-type="ffscript">
		<?php
	}

}