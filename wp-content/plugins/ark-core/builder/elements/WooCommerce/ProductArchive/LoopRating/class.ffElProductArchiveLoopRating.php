<?php

class ffElProductArchiveLoopRating extends ffThemeBuilderElementBasic {
	protected function _initData() {
		$this->_setData( ffThemeBuilderElement::DATA_ID, 'woocommerce-archive-product-rating');
		$this->_setData( ffThemeBuilderElement::DATA_NAME, 'WC - Archive - Item - Rating');
		$this->_setData( ffThemeBuilderElement::DATA_HAS_DROPZONE, false);
		$this->_setData( ffThemeBuilderElement::DATA_CAN_BE_CACHED, false);

		$this->_setData( ffThemeBuilderElement::DATA_PICKER_MENU_ID, 'wc-archive-item');
		$this->_setData( ffThemeBuilderElement::DATA_PICKER_TAGS, 'woocommerce, wc, archive, rating');
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


			$s->addElement( ffOneElement::TYPE_TABLE_DATA_START, '',  __('Star Colors', 'ark') );
				$s->startSection('star-colors');
					$s->addElement(ffOneElement::TYPE_HTML, '', '<div class="row"><div class="col-md-6">' );
						$s->addOptionNL( ffOneOption::TYPE_COLOR_PICKER_WITH_LIB, 'color', '', '')
							->addParam( ffOneOption::PARAM_TITLE_AFTER, __('Stars', 'ark') );
						$s->addOptionNL( ffOneOption::TYPE_COLOR_PICKER_WITH_LIB, 'inactive-color', '', '')
							->addParam( ffOneOption::PARAM_TITLE_AFTER, __('Inactive Stars', 'ark') );
					$s->addElement(ffOneElement::TYPE_HTML, '', '</div><div class="col-md-6">' );
						$s->addOptionNL( ffOneOption::TYPE_COLOR_PICKER_WITH_LIB, 'color-hover', '', '')
							->addParam( ffOneOption::PARAM_TITLE_AFTER, __('Stars Hover', 'ark') );
						$s->addOptionNL( ffOneOption::TYPE_COLOR_PICKER_WITH_LIB, 'inactive-color-hover', '', '')
							->addParam( ffOneOption::PARAM_TITLE_AFTER, __('Inactive Stars Hover', 'ark') );
					$s->addElement(ffOneElement::TYPE_HTML, '', '</div></div>' );
				$s->endSection();
			$s->addElement(ffOneElement::TYPE_TABLE_DATA_END);


		$s->addElement(ffOneElement::TYPE_TABLE_END);
	}

	protected function _render( ffOptionsQueryDynamic $query, $content, $data, $uniqueId ) {
		$is_wrapped = $query->get('is-wrapped');
		$tag = $query->get('tag');
		$has_link = $query->get('is-wrapped-by-link');
		if( $is_wrapped ) {
			echo '<'.$tag.' class="ark-woocommerce-single-product-rating">';
		}
		if($has_link){
			woocommerce_template_loop_product_link_open();
		}
		woocommerce_template_loop_rating();
		if($has_link){
			woocommerce_template_loop_product_link_close();
		}
		if( $is_wrapped ){
			echo '</'.$tag.'>';
		}

		/* normal */
		$this->_renderCSSRule( 'color', $query->getColor('star-colors color'), '.star-rating > span');
		$this->_renderCSSRule( 'color', $query->getColor('star-colors inactive-color'), '.star-rating:before');

		/* hover */
		$this->_renderCSSRule( 'color', $query->getColor('star-colors color-hover'), '.star-rating:hover > span');
		$this->_renderCSSRule( 'color', $query->getColor('star-colors inactive-color-hover'), '.star-rating:hover:before');

	}


	protected function _renderContentInfo_JS() {
		?>
		<script data-type="ffscript">
			function ( query, options, $elementPreview, $element ) {
				query.addPlainText('WooCommerce - Product Archive Loop');
				query.addBreak();
				query.addHeadingSm(null, 'Rating');
			}
		</script data-type="ffscript">
		<?php
	}

}