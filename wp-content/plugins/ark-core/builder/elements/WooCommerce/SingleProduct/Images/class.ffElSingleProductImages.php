<?php

class ffElSingleProductImages extends ffThemeBuilderElementBasic {
	protected function _initData() {
		$this->_setData( ffThemeBuilderElement::DATA_ID, 'woocommerce-single-product-images');
		$this->_setData( ffThemeBuilderElement::DATA_NAME, 'WC - Single - Images');
		$this->_setData( ffThemeBuilderElement::DATA_HAS_DROPZONE, false);
		$this->_setData( ffThemeBuilderElement::DATA_CAN_BE_CACHED, false);

		$this->_setData( ffThemeBuilderElement::DATA_PICKER_MENU_ID, 'wc-single');
		$this->_setData( ffThemeBuilderElement::DATA_PICKER_TAGS, 'woocommerce, wc, images, single product');
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

			$s->addElement( ffOneElement::TYPE_TABLE_DATA_START, '',  __('Spaces', 'ark') );
				$s->addOptionNL( ffOneOption::TYPE_TEXT, 'space-right', '', '10')
					->addParam( ffOneOption::PARAM_TITLE_AFTER, ark_wp_kses( __('Custom horizontal gap between small images (in px)', 'ark' ) ) );
				$s->addOptionNL( ffOneOption::TYPE_TEXT, 'space-top', '', '10')
					->addParam( ffOneOption::PARAM_TITLE_AFTER, ark_wp_kses( __('Custom vertical gap between image and previews (in px)', 'ark' ) ) );
			$s->addElement(ffOneElement::TYPE_TABLE_DATA_END);

		$s->addElement(ffOneElement::TYPE_TABLE_END);
	}

	protected function _render( ffOptionsQueryDynamic $query, $content, $data, $uniqueId ) {
		$is_wrapped = $query->get('is-wrapped');
		$tag = $query->get('tag');
		if( $is_wrapped ) {
			echo '<'.$tag.' class="ark-woocommerce-single-product-images">';
		}
		woocommerce_show_product_images();
		echo '<style>.ark-woocommerce-single-product-images .woocommerce-product-gallery{width:100% !important;}</style>';
		if( $is_wrapped ){
			echo '</'.$tag.'>';
		}

		$space_lr  = 0.5 * $query->get('space-right');
		$space_top = 1 * $query->get('space-top');

		$this->_renderCSSRuleImportant( 'margin', '0 -'.$space_lr.'px ' , 'ol');
		$this->_renderCSSRule( 'position', 'relative' , 'ol');
		$this->_renderCSSRule( 'padding', $space_top.'px '.$space_lr.'px 0 '.$space_lr.'px' , 'ol>li');

	}


	protected function _renderContentInfo_JS() {
		?>
		<script data-type="ffscript">
			function ( query, options, $elementPreview, $element ) {
				query.addPlainText('WooCommerce - Single Product');
				query.addBreak();
				query.addHeadingSm(null, 'Images');
			}
		</script data-type="ffscript">
		<?php
	}

}