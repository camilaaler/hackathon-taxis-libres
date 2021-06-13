<?php

class ffElSingleProductRelatedProducts extends ffAbstractElProductLoop {
	protected $_wcElementTitle;
	protected $_wcElementQuery;
	protected $_wcElementContent;

	protected function _initData() {
		$this->_setData( ffThemeBuilderElement::DATA_ID, 'woocommerce-single-product-related-products');
		$this->_setData( ffThemeBuilderElement::DATA_NAME, 'WC - Single - Related Products');
		$this->_setData( ffThemeBuilderElement::DATA_HAS_DROPZONE, true);
		$this->_setData( ffThemeBuilderElement::DATA_CAN_BE_CACHED, false);

		$this->_setData( ffThemeBuilderElement::DATA_PICKER_MENU_ID, 'wc-single');
		$this->_setData( ffThemeBuilderElement::DATA_PICKER_TAGS, 'woocommerce, wc, sale flash, single product');
		$this->_setData( ffThemeBuilderElement::DATA_PICKER_ITEM_WIDTH, 0);
	}

	/**
	 * @param $s ffOneStructure|ffThemeBuilderOptionsExtender
	 * @return mixed
	 */
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

		$s->addElement(ffOneElement::TYPE_TABLE_DATA_START, '', 'Title');
		$s->addOption(ffOneOption::TYPE_CHECKBOX, 'use-title', 'Use Title &nbsp;', 1);
		$s->addOption(ffOneOption::TYPE_TEXT, 'title', '', __( 'Related products', 'woocommerce' ) );

		$this->_getElementLoopOptions( $s );

		$this->_getElementColumnOptions( $s );

		$this->_getElementProductInTheLoopWrapper( $s );

		$s->addElement(ffOneElement::TYPE_TABLE_END);
	}

	public function setTemplateToEmpty( $template, $template_name, $template_path ){
		if( 'single-product/related.php' == $template_name ){
			return $this->getEmptyWCTemplatePath();
		}
		return $template;
	}

	public function simulateTemplate($template_name, $template_path, $located, $args){
		if( 'single-product/related.php' != $template_name ){
			return;
		}

		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args );
		}

		if ( $related_products ){
			echo '<section class="related products">';
				if( !empty( $this->_wcElementTitle ) ) {
					echo '<h2>'.$this->_wcElementTitle.'</h2>';
				}
				woocommerce_product_loop_start();
				foreach ( $related_products as $related_product ){
					$post_object = get_post( $related_product->get_id() );
					$GLOBALS['post'] = $post_object;
//					setup_postdata( $GLOBALS['post'] =& $post_object );
					setup_postdata( $GLOBALS['post']);

					global $product;

					// Ensure visibility
					if ( empty( $product ) || ! $product->is_visible() ) {
						continue;
					}

					$classes = get_post_class( '', NULL );
					if (($key = array_search('first', $classes)) !== false) {
						unset($classes[$key]);
					}
					if (($key = array_search('last', $classes)) !== false) {
						unset($classes[$key]);
					}

					$classes = ' class="' . join( ' ', $classes ) . '"';

					$this->_advancedToggleBoxStart( $this->_wcElementQuery, 'product-wrapper' );
					echo '<li' . $classes . '>';
					echo ( $this->_doShortcode( $this->_wcElementContent ) );
					echo '</li>';
					$this->_advancedToggleBoxEnd( $this->_wcElementQuery, 'product-wrapper' );

				}
				woocommerce_product_loop_end();
			echo '</section>';
		}
		wp_reset_postdata();
	}

	protected function _render( ffOptionsQueryDynamic $query, $content, $data, $uniqueId ) {
		$is_wrapped = $query->get('is-wrapped');
		$tag = $query->get('tag');

		if( $query->get('use-title') ) {
			$this->_wcElementTitle = trim( $query->get( 'title' ) );
		}

		$this->_renderCSSForColumns( $query->get('grid') );
		$this->_wcElementQuery = $query;
		$this->_wcElementContent = $content;

		$limit   = $query->get('loop limit');
		$orderby = $query->get('loop orderby');
		$order   = $query->get('loop order');

		if( $is_wrapped ) {
			echo '<'.$tag.' class="ark-woocommerce-single-product-related-products">';
		}

		//woocommerce_output_related_products();

		$args = array(
			'posts_per_page' => $limit,
			'columns'        => 999,
			'orderby'        => $orderby,
			'order'          => $order,
		);

		add_filter( 'woocommerce_locate_template', array($this, 'setTemplateToEmpty'), 10, 3 );
		add_action( 'woocommerce_after_template_part', array($this, 'simulateTemplate'), 10, 4 );
		woocommerce_related_products( apply_filters( 'woocommerce_output_related_products_args', $args ) );
		remove_action( 'woocommerce_after_template_part', array($this, 'simulateTemplate'), 10 );
		remove_filter( 'woocommerce_locate_template', array($this, 'setTemplateToEmpty'), 10 );

		if( $is_wrapped ){
			echo '</'.$tag.'>';
		}
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