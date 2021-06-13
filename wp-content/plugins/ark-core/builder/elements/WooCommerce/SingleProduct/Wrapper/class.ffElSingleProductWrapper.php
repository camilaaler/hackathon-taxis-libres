<?php

class ffElSingleProductWrapper extends ffThemeBuilderElementBasic {
	protected function _initData() {
		$this->_setData( ffThemeBuilderElement::DATA_ID, 'woocommerce-single-product-wrapper');
		$this->_setData( ffThemeBuilderElement::DATA_NAME, 'WC - Single - Product Wrapper');
		$this->_setData( ffThemeBuilderElement::DATA_HAS_DROPZONE, true);

		$this->_setData( ffThemeBuilderElement::DATA_PICKER_MENU_ID, 'wc-single');
		$this->_setData( ffThemeBuilderElement::DATA_PICKER_TAGS, 'woocommerce, wc, shop, loop, wrapper, single product');
		$this->_setData( ffThemeBuilderElement::DATA_PICKER_ITEM_WIDTH, 0);
	}

	protected function _beforeRenderingAdminWrapper( ffOptionsQueryDynamic $query, $content, ffMultiAttrHelper $multiAttrHelper, ffStdClass $otherData ) {

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
		Please also note, that there are not used templates <code>single-product.php</code> and <code>content-single-product.php</code>
		', 'ark' ) );
		$s->addElement(ffOneElement::TYPE_TABLE_DATA_END);

		$s->addElement(ffOneElement::TYPE_TABLE_DATA_START, '', 'HTML Tag');
		$s->addOption(ffOneOption::TYPE_TEXT,'tag','','div');
		$s->addElement(ffOneElement::TYPE_TABLE_DATA_END);

		$s->addElement(ffOneElement::TYPE_TABLE_DATA_START, '', 'Debug mode');
		$s->addElement( ffOneElement::TYPE_DESCRIPTION, '', '
			Please note, that when you use this element with some extra WooCommerce plugin, you will need manually add
			functions, that are called in the WooCommerce hooks.
			<br>
			In that case - use element <stong>WC - Tools - Hook Debugger</stong> with parameter <strong>Hooks to check</strong> set to:
			<br>
			<pre>woocommerce_before_main_content
woocommerce_before_single_product
woocommerce_before_single_product_summary
woocommerce_single_product_summary
woocommerce_after_single_product_summary
woocommerce_after_single_product
woocommerce_after_main_content
woocommerce_sidebar</pre>
			<br>
			There will be list of hooks and functions, that are not called or not in element.
			<br>
			Hooks and functions used are:
			<pre>woocommerce_output_content_wrapper // in the hook woocommerce_before_main_content
woocommerce_before_single_product // hook
WC_Structured_Data::generate_website_data() // in the hook woocommerce_before_main_content
WC_Structured_Data::generate_product_data() // in the hook woocommerce_single_product_summary
woocommerce_after_single_product // hook
woocommerce_output_content_wrapper_end() // in the hook woocommerce_after_main_content
</pre>
		' );
		$s->addElement(ffOneElement::TYPE_TABLE_DATA_END);

		$s->addElement(ffOneElement::TYPE_TABLE_END);
	}

	protected function _render( ffOptionsQueryDynamic $query, $content, $data, $uniqueId ) {
		$tag = $query->get('tag');

		if( !function_exists('WC') ){
			echo '<'.$tag.' class="woocommerce ark-woocommerce-plugin-disabled"></'.$tag.'>';
			return;
		}

		if( have_posts() ){
			the_post();
		}else{
			echo '<'.$tag.' class="woocommerce ark-woocommerce-single-product-not-found">';
			/**
			 * woocommerce_no_products_found hook.
			 *
			 * @hooked wc_no_products_found - 10
			 */
			do_action( 'woocommerce_no_products_found' );
			echo '</'.$tag.'>';
			return;
		}

		if ( post_password_required() ) {
			echo '<'.$tag.' class="woocommerce ark-woocommerce-password-form">';
			echo get_the_password_form();
			echo '</'.$tag.'>';
			return;
		}

		echo '<'.$tag.' class="woocommerce">';

		/**
		 * @see WooCommerce Template single-product.php and action 'woocommerce_before_main_content'
		 * note there is not included woocommerce_breadcrumb() that should be called
		 * by action 'woocommerce_before_main_content'
		 */
		woocommerce_output_content_wrapper();

		/**
		 * @hooked wc_print_notices - 10
		 */
		do_action( 'woocommerce_before_single_product' );

		/**
		 * Generates WebSite structured data.
		 */
		WooCommerce::instance()->structured_data->generate_website_data();

		/**
		 * Generates Product structured data.
		 */
		WooCommerce::instance()->structured_data->generate_product_data();


		echo '<div id="product-'; the_ID(); echo '" '; post_class('post-wrapper blog-grid-content');  echo '>';
		echo ( $this->_doShortcode( $content ) );
		echo '</div>';

		do_action( 'woocommerce_after_single_product' );

		/**
		 * @see WooCommerce Template single-product.php and action 'woocommerce_after_main_content'
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