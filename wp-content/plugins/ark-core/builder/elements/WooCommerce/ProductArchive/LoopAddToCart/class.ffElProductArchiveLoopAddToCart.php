<?php

class ffElProductArchiveLoopAddToCart extends ffThemeBuilderElementBasic {
	protected function _initData() {
		$this->_setData( ffThemeBuilderElement::DATA_ID, 'woocommerce-archive-product-loop-add-to-cart');
		$this->_setData( ffThemeBuilderElement::DATA_NAME, 'WC - Archive - Item - Add To Cart');
		$this->_setData( ffThemeBuilderElement::DATA_HAS_DROPZONE, false);
		$this->_setData( ffThemeBuilderElement::DATA_CAN_BE_CACHED, false);

		$this->_setData( ffThemeBuilderElement::DATA_PICKER_MENU_ID, 'wc-archive-item');
		$this->_setData( ffThemeBuilderElement::DATA_PICKER_TAGS, 'woocommerce, wc, archive, add to cart');
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
				$s->addElement(ffOneElement::TYPE_DESCRIPTION, '', 'This element is not just Add to Cart button - there are actually also buttons like "Select options" (when product has attributes) and "View cart" button that loads by AJAX after buyer adds item to cart.' );
			$s->addElement(ffOneElement::TYPE_TABLE_DATA_END);
			$s->addElement(ffOneElement::TYPE_TABLE_DATA_START, '', 'Wrapping');
				$s->addOption(ffOneOption::TYPE_CHECKBOX, 'is-wrapped', 'Wrap by &nbsp;', 1);
				$s->addOption(ffOneOption::TYPE_TEXT, 'tag', '', 'div');
				$s->addElement(ffOneElement::TYPE_DESCRIPTION, '', 'If you have problems - for example - after you added the new plugin, check this option.' );
			$s->addElement(ffOneElement::TYPE_TABLE_DATA_END);

			$s->addElement( ffOneElement::TYPE_TABLE_DATA_START, '',  __('Button Colors<br>Add To Cart', 'ark') );
				$s->startSection('add-to-cart');
					$s->addElement(ffOneElement::TYPE_DESCRIPTION, '', 'Button for classic products' );
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

			$s->addElement( ffOneElement::TYPE_TABLE_DATA_START, '',  __('Button Colors<br>View Cart', 'ark') );
				$s->startSection('view-cart');
					$s->addElement(ffOneElement::TYPE_DESCRIPTION, '', 'Button shows after buyer clicks on "Add to Cart" button for classic products' );
					$s->addElement(ffOneElement::TYPE_DESCRIPTION, '', 'If you set any border color, then link will behave as button' );
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
			$s->addElement( ffOneElement::TYPE_TABLE_DATA_END);

			$s->addElement( ffOneElement::TYPE_TABLE_DATA_START, '',  __('Button Colors<br>Select Options', 'ark') );
				$s->startSection('select-options');
					$s->addElement(ffOneElement::TYPE_DESCRIPTION, '', 'Button for variable products' );
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

			$s->addElement( ffOneElement::TYPE_TABLE_DATA_START, '',  __('Button Colors<br>View Products', 'ark') );
				$s->startSection('view-products');
					$s->addElement(ffOneElement::TYPE_DESCRIPTION, '', 'Button for grouped product' );
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

			$s->addElement( ffOneElement::TYPE_TABLE_DATA_START, '',  __('Button Colors<br>Affiliate Product', 'ark') );
				$s->startSection('affiliate-products');
					$s->addElement(ffOneElement::TYPE_DESCRIPTION, '', 'Button for External / Affiliate Products' );
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
		if( $is_wrapped ){
			echo '<'.$tag.' class="ark-woocommerce-single-product-add-to-cart">';
		}
		woocommerce_template_loop_add_to_cart();
		if( $is_wrapped ){
			echo '</'.$tag.'>';
		}

		/* add-to-cart */

		if( $query->getColor('add-to-cart border-color') or $query->getColor('add-to-cart border-color-hover') ){
			$this->_renderCSSRule( 'border', '1px solid transparent', 'a.product_type_simple.add_to_cart_button');
		}

		/* normal */
		$this->_renderCSSRule( 'color', $query->getColor('add-to-cart color'), 'a.product_type_simple.add_to_cart_button');
		$this->_renderCSSRule( 'color', $query->getColor('add-to-cart color'), 'a.product_type_simple.add_to_cart_button:after');
		$this->_renderCSSRule( 'background-color', $query->getColor('add-to-cart bg-color'), 'a.product_type_simple.add_to_cart_button');
		$this->_renderCSSRule( 'border-color', $query->getColor('add-to-cart border-color'), 'a.product_type_simple.add_to_cart_button');

		/* loading */
		$this->_renderCSSRule( 'color', $query->getColor('add-to-cart color-hover'), 'a.product_type_simple.add_to_cart_button.loading');
		$this->_renderCSSRule( 'color', $query->getColor('add-to-cart color-hover'), 'a.product_type_simple.add_to_cart_button.loading:after');
		$this->_renderCSSRule( 'background-color', $query->getColor('add-to-cart bg-color-hover'), 'a.product_type_simple.add_to_cart_button.loading');
		$this->_renderCSSRule( 'border-color', $query->getColor('add-to-cart border-color-hover'), 'a.product_type_simple.add_to_cart_button.loading');

		/* hover */
		$this->_renderCSSRule( 'color', $query->getColor('add-to-cart color-hover'), 'a.product_type_simple.add_to_cart_button:hover');
		$this->_renderCSSRule( 'color', $query->getColor('add-to-cart color-hover'), 'a.product_type_simple.add_to_cart_button:hover:after');
		$this->_renderCSSRule( 'background-color', $query->getColor('add-to-cart bg-color-hover'), 'a.product_type_simple.add_to_cart_button:hover');
		$this->_renderCSSRule( 'border-color', $query->getColor('add-to-cart border-color-hover'), 'a.product_type_simple.add_to_cart_button:hover');




		/* view-cart */

		if( $query->getColor('view-cart border-color') or $query->getColor('view-cart border-color-hover') ){
			$r = $this->_getAssetsRenderer()->createCssRule();
			$r->addSelector( 'a.added_to_cart' );
			$r->addParamsString('
				font-size: 0.875em;
				margin: .75em 0 0 0;
				line-height: 1;
				cursor: pointer;
				position: relative;
				text-decoration: none;
				overflow: visible;
				padding: .4635em .75em;
				font-weight: 700;
				border-radius: 3px;
				border: 1px solid transparent;
				left: auto;
				white-space: nowrap;
				display: inline-block;
				background-image: none;
				-webkit-box-shadow: none;
				box-shadow: none;
				text-shadow: none;
			' );
		}

		/* normal */
		$this->_renderCSSRule( 'color', $query->getColor('view-cart color'), 'a.added_to_cart');
		$this->_renderCSSRule( 'color', $query->getColor('view-cart color'), 'a.added_to_cart:after');
		$this->_renderCSSRule( 'background-color', $query->getColor('view-cart bg-color'), 'a.added_to_cart');
		$this->_renderCSSRule( 'border-color', $query->getColor('view-cart border-color'), 'a.added_to_cart');

		/* hover */
		$this->_renderCSSRule( 'color', $query->getColor('view-cart color-hover'), 'a.added_to_cart:hover');
		$this->_renderCSSRule( 'color', $query->getColor('view-cart color-hover'), 'a.added_to_cart:hover:after');
		$this->_renderCSSRule( 'background-color', $query->getColor('view-cart bg-color-hover'), 'a.added_to_cart:hover');
		$this->_renderCSSRule( 'border-color', $query->getColor('view-cart border-color-hover'), 'a.added_to_cart:hover');



		/* select-options */

		/* normal */
		$this->_renderCSSRule( 'color', $query->getColor('select-options color'), 'a.product_type_variable.add_to_cart_button');
		$this->_renderCSSRule( 'color', $query->getColor('select-options color'), 'a.product_type_variable.add_to_cart_button:after');
		$this->_renderCSSRule( 'background-color', $query->getColor('select-options bg-color'), 'a.product_type_variable.add_to_cart_button');
		$this->_renderCSSRule( 'border-color', $query->getColor('select-options border-color'), 'a.product_type_variable.add_to_cart_button');

		/* hover */
		$this->_renderCSSRule( 'color', $query->getColor('select-options color-hover'), 'a.product_type_variable.add_to_cart_button:hover');
		$this->_renderCSSRule( 'color', $query->getColor('select-options color-hover'), 'a.product_type_variable.add_to_cart_button:hover:after');
		$this->_renderCSSRule( 'background-color', $query->getColor('select-options bg-color-hover'), 'a.product_type_variable.add_to_cart_button:hover');
		$this->_renderCSSRule( 'border-color', $query->getColor('select-options border-color-hover'), 'a.product_type_variable.add_to_cart_button:hover');


		/* view-products */

		/* normal */
		$this->_renderCSSRule( 'color', $query->getColor('view-products color'), 'a.button.product_type_grouped');
		$this->_renderCSSRule( 'color', $query->getColor('view-products color'), 'a.button.product_type_grouped:after');
		$this->_renderCSSRule( 'background-color', $query->getColor('view-products bg-color'), 'a.button.product_type_grouped');
		$this->_renderCSSRule( 'border-color', $query->getColor('view-products border-color'), 'a.button.product_type_grouped');

		/* hover */
		$this->_renderCSSRule( 'color', $query->getColor('view-products color-hover'), 'a.button.product_type_grouped:hover');
		$this->_renderCSSRule( 'color', $query->getColor('view-products color-hover'), 'a.button.product_type_grouped:hover:after');
		$this->_renderCSSRule( 'background-color', $query->getColor('view-products bg-color-hover'), 'a.button.product_type_grouped:hover');
		$this->_renderCSSRule( 'border-color', $query->getColor('view-products border-color-hover'), 'a.button.product_type_grouped:hover');



		/* affiliate-products */

		/* normal */
		$this->_renderCSSRule( 'color', $query->getColor('affiliate-products color'), 'a.button.product_type_external');
		$this->_renderCSSRule( 'color', $query->getColor('affiliate-products color'), 'a.button.product_type_external:after');
		$this->_renderCSSRule( 'background-color', $query->getColor('affiliate-products bg-color'), 'a.button.product_type_external');
		$this->_renderCSSRule( 'border-color', $query->getColor('affiliate-products border-color'), 'a.button.product_type_external');

		/* hover */
		$this->_renderCSSRule( 'color', $query->getColor('affiliate-products color-hover'), 'a.button.product_type_external:hover');
		$this->_renderCSSRule( 'color', $query->getColor('affiliate-products color-hover'), 'a.button.product_type_external:hover:after');
		$this->_renderCSSRule( 'background-color', $query->getColor('affiliate-products bg-color-hover'), 'a.button.product_type_external:hover');
		$this->_renderCSSRule( 'border-color', $query->getColor('affiliate-products border-color-hover'), 'a.button.product_type_external:hover');


	}


	protected function _renderContentInfo_JS() {
		?>
		<script data-type="ffscript">
			function ( query, options, $elementPreview, $element ) {
				query.addPlainText('WooCommerce - Product Archive Loop');
				query.addBreak();
				query.addHeadingSm(null, 'Add to Cart');
			}
		</script data-type="ffscript">
		<?php
	}

}