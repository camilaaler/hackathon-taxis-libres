<?php

class ffElProductArchiveWrapper extends ffAbstractElProductLoop {
	protected $_queryForCatWrapper;

	protected function _initData() {
		$this->_setData( ffThemeBuilderElement::DATA_ID, 'woocommerce-product-archive-wrapper');
		$this->_setData( ffThemeBuilderElement::DATA_NAME, 'WC - Archive - Products Loop');
		$this->_setData( ffThemeBuilderElement::DATA_HAS_DROPZONE, true);

		$this->_setData( ffThemeBuilderElement::DATA_PICKER_MENU_ID, 'wc-archive');
		$this->_setData( ffThemeBuilderElement::DATA_PICKER_TAGS, 'woocommerce, wc, shop, loop, product, archive, wrapper');
		$this->_setData( ffThemeBuilderElement::DATA_PICKER_ITEM_WIDTH, 0);
	}

	protected function _fillSelectWithNumberOfColumns( $select, $columns ) {

		foreach( $columns as $oneColumn ) {
			$select->addSelectValue( $oneColumn, $oneColumn );
		}

	}

	protected function _getElementGeneralOptions( $s ) {
		$s->addElement( ffOneElement::TYPE_TABLE_START );

		$s->addElement(ffOneElement::TYPE_TABLE_DATA_START, '', 'Note');
		$s->addElement( ffOneElement::TYPE_DESCRIPTION, '', __('
		
		Please note, that all usual action and hooks, that WooCommerce uses cannot be called.
		<br><br>
		This element does not use all hooks and actions, sometimes it is replaced only with some functions in it.
		<br><br>
		This solution is required for better customization - For example - if you wish to have product breadcrumbs on different place or just if you wish to remove it.
		<br><br>
		But there is price for it - there may be problems with compatibility with some WooCommerce plugins, that uses these hooks.
		 <br><br>
		Please also note, that there are not used templates <code>archive-product.php</code> and <code>content-product.php</code>
		', 'ark' ) );
		$s->addElement(ffOneElement::TYPE_TABLE_DATA_END);

		$s->addElement(ffOneElement::TYPE_TABLE_DATA_START, '', 'HTML Tag');
		$s->addOption(ffOneOption::TYPE_TEXT,'tag','','div');
		$s->addElement(ffOneElement::TYPE_TABLE_DATA_END);

		$this->_getElementColumnOptions( $s );

		$s->addElement(ffOneElement::TYPE_TABLE_DATA_START, '', 'Category Wrapper');
			$s->startAdvancedToggleBox('category-wrapper', 'Category Wrapper');
				$s->addElement( ffOneElement::TYPE_DESCRIPTION, '', 'This is the Category Wrapper that you can edit and style');
				$s->addOption(ffOneOption::TYPE_HIDDEN, 'blank', '', 'blank');
			$s->endAdvancedToggleBox();
		$s->addElement( ffOneElement::TYPE_TABLE_DATA_END);

		$s->addElement(ffOneElement::TYPE_TABLE_DATA_START, '', 'Product Wrapper');
			$s->startAdvancedToggleBox('product-wrapper', 'Product Wrapper');
				$s->addElement( ffOneElement::TYPE_DESCRIPTION, '', 'This is the Product Wrapper (in the Loop) that you can edit and style');
				$s->addOption(ffOneOption::TYPE_HIDDEN, 'blank', '', 'blank');
			$s->endAdvancedToggleBox();
		$s->addElement( ffOneElement::TYPE_TABLE_DATA_END);

		$s->addElement(ffOneElement::TYPE_TABLE_DATA_START, '', 'Debug mode');
		$s->addElement( ffOneElement::TYPE_DESCRIPTION, '', '
			Please note, that when you use this element with some extra WooCommerce plugin, you will need manually add
			functions, that are called in the WooCommerce hooks.
			<br>
			In that case - use element <stong>WC - Tools - Hook Debugger</stong> with parameter <strong>Hooks to check</strong> set to:
			<br>
			<pre>woocommerce_before_main_content
woocommerce_archive_description
woocommerce_before_shop_loop
woocommerce_shop_loop
woocommerce_before_shop_loop_item
woocommerce_before_shop_loop_item_title
woocommerce_shop_loop_item_title
woocommerce_after_shop_loop_item_title
woocommerce_after_shop_loop_item
woocommerce_after_shop_loop
woocommerce_no_products_found
woocommerce_sidebar</pre>
			<br>
			There will be list of hooks and functions, that are not called or not in element.
			<br>
			Hooks and functions used are:
			<pre>woocommerce_output_content_wrapper // in the hook woocommerce_before_main_content
WC_Structured_Data::generate_website_data() // in the hook woocommerce_before_main_content
woocommerce_before_shop_loop // hook
woocommerce_shop_loop // hook
woocommerce_after_shop_loop // hook
woocommerce_no_products_found // hook
woocommerce_output_content_wrapper_end() // in the hook woocommerce_after_main_content</pre>
		' );
		$s->addElement(ffOneElement::TYPE_TABLE_DATA_END);

		$s->addElement(ffOneElement::TYPE_TABLE_END);
	}

	public function removeClassesFromCategories($classes, $class, $category){
		if (($key = array_search('first', $classes)) !== false) {
			unset($classes[$key]);
		}
		if (($key = array_search('last', $classes)) !== false) {
			unset($classes[$key]);
		}
		return $classes;
	}

	public function startWrappingWCCat(){
		$this->_advancedToggleBoxStart( $this->_queryForCatWrapper, 'category-wrapper' );
	}

	public function endWrappingWCCat(){
		$this->_advancedToggleBoxEnd( $this->_queryForCatWrapper, 'category-wrapper' );
	}

	protected function _render( ffOptionsQueryDynamic $query, $content, $data, $uniqueId ) {
		$tag = $query->get('tag');

		$this->_renderCSSForColumns( $query->get('grid') );

		if( !function_exists('WC') ){
			echo '<'.$tag.' class="woocommerce ark-woocommerce-plugin-disabled"></'.$tag.'>';
			return;
		}

		echo '<'.$tag.' class="woocommerce">';

		/**
		 * @see WooCommerce Template archive-product.php and action 'woocommerce_before_main_content'
		 * note there is not included woocommerce_breadcrumb() that should be called
		 * by action 'woocommerce_before_main_content'
		 */

		/**
		 * Calls /plugins/woocommerce/templates/loop/loop-start.php
		 */
		woocommerce_output_content_wrapper();

		/**
		 * Generates WebSite structured data.
		 */
		WooCommerce::instance()->structured_data->generate_website_data();

		if ( have_posts() ){

			/**
			 * woocommerce_before_shop_loop hook.
			 *
			 * @hooked wc_print_notices - 10
			 * @hooked woocommerce_result_count - 20
			 * @hooked woocommerce_catalog_ordering - 30
			 */
			do_action( 'woocommerce_before_shop_loop' );

			/**
			 * Calls /plugins/woocommerce/templates/loop/loop-start.php
			 * and that prints:
			 * <ul class="products">
			 */
			woocommerce_product_loop_start();

			add_filter( 'product_cat_class', array($this , 'removeClassesFromCategories' ), 10, 3 );

			$this->_queryForCatWrapper = $query;
			add_filter( 'woocommerce_before_template_part', array( $this, 'startWrappingWCCat' ) );
			add_filter( 'woocommerce_after_template_part', array( $this, 'endWrappingWCCat' ) );
			woocommerce_product_subcategories();
			remove_filter( 'woocommerce_before_template_part', array( $this, 'startWrappingWCCat' ) );
			remove_filter( 'woocommerce_after_template_part', array( $this, 'endWrappingWCCat' ) );

			remove_filter( 'product_cat_class', array($this , 'removeClassesFromCategories' ), 10 );

			while ( have_posts() ){
				the_post();

				/**
				 * woocommerce_shop_loop hook.
				 * @hooked WC_Structured_Data::generate_product_data() - 10
				 */
				do_action( 'woocommerce_shop_loop' );

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

				$this->_advancedToggleBoxStart( $query, 'product-wrapper' );
				echo '<li' . $classes . '>';
				echo ( $this->_doShortcode( $content ) );
				echo '</li>';
				$this->_advancedToggleBoxEnd( $query, 'product-wrapper' );
			}

			/**
			 * Calls /plugins/woocommerce/templates/loop/loop-end.php
			 * and that prints:
			 * </ul>
			 */
			woocommerce_product_loop_end();

			/**
			 * woocommerce_after_shop_loop hook.
			 *
			 * @hooked woocommerce_pagination - 10
			 */
			do_action( 'woocommerce_after_shop_loop' );

		} elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) {

			/**
			 * woocommerce_no_products_found hook.
			 *
			 * @hooked wc_no_products_found - 10
			 */
			do_action( 'woocommerce_no_products_found' );

		}

		/**
		 * @see WooCommerce Template archive-product.php and action 'woocommerce_after_main_content'
		 */
		woocommerce_output_content_wrapper_end();

		echo '</'.$tag.'>';
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