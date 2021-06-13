<?php

class ffElHookDebugger extends ffThemeBuilderElementBasic {
	protected function _initData() {
		$this->_setData( ffThemeBuilderElement::DATA_ID, 'woocomerce-hook-debugger');
		$this->_setData( ffThemeBuilderElement::DATA_NAME, 'WC - Tools - Hook Debugger');
		$this->_setData( ffThemeBuilderElement::DATA_HAS_DROPZONE, false);
		$this->_setData( ffThemeBuilderElement::DATA_CAN_BE_CACHED, false);

		$this->_setData( ffThemeBuilderElement::DATA_PICKER_MENU_ID, 'wc-tools');
		$this->_setData( ffThemeBuilderElement::DATA_PICKER_TAGS, 'woocommerce, wc, tools, debugger');
		$this->_setData( ffThemeBuilderElement::DATA_PICKER_ITEM_WIDTH, 0);
	}

	protected function _beforeRenderingAdminWrapper( ffOptionsQueryDynamic $query, $content, ffMultiAttrHelper $multiAttrHelper, ffStdClass $otherData ) {

	}

	protected function _getElementGeneralOptions( $s ) {
		$s->addElement( ffOneElement::TYPE_TABLE_START );

		$s->addElement(ffOneElement::TYPE_TABLE_DATA_START, '', 'Note');
		$s->addElement( ffOneElement::TYPE_DESCRIPTION, '', __('Please note, that all usual action and hooks, that WooCommerce uses cannot be called.', 'ark' ) );
		$s->addElement(ffOneElement::TYPE_TABLE_DATA_END);

		$s->addElement(ffOneElement::TYPE_TABLE_DATA_START, '', 'Hooks to Check');
		$s->addOption(ffOneOption::TYPE_TEXTAREA_STRICT, 'stuff-to-show','','woocommerce_before_main_content
woocommerce_before_single_product
woocommerce_before_single_product_summary
woocommerce_single_product_summary
woocommerce_after_single_product_summary
woocommerce_after_single_product
woocommerce_after_main_content
woocommerce_sidebar');
		$s->addElement(ffOneElement::TYPE_TABLE_DATA_END);
		$s->addElement(ffOneElement::TYPE_TABLE_DATA_START, '', 'Hooks and Functions used');
		$s->addOption(ffOneOption::TYPE_TEXTAREA_STRICT, 'stuff-to-check','','woocommerce_output_content_wrapper // in the hook woocommerce_before_main_content
woocommerce_before_single_product // hook
WC_Structured_Data::generate_website_data() // in the hook woocommerce_before_main_content
WC_Structured_Data::generate_product_data() // in the hook woocommerce_single_product_summary
woocommerce_after_single_product // hook
woocommerce_output_content_wrapper_end() // in the hook woocommerce_after_main_content
');
		$s->addElement(ffOneElement::TYPE_TABLE_DATA_END);

		$s->addElement(ffOneElement::TYPE_TABLE_END);
	}

	protected function _getWCFunctionDescription( $func ){
		switch( $func ){

			/* in Product Single */

			case 'woocommerce_breadcrumb':
				return 'Print the WooCommerce Breadcrumb.<br>Use element <strong>SEO Microdata Breadcrumbs</strong>';

			case 'woocommerce_show_product_sale_flash':
				return 'Print the product sale flash.<br>Use element <strong>WooCommerce Single Product - Sale Flash</strong>';

			case 'woocommerce_show_product_images':
				return 'Print the product image before the single product summary.<br>Use element <strong>WooCommerce Single Product - Images</strong>';

			case 'woocommerce_template_single_title':
				return 'Print the product title.<br>Use element <strong>WooCommerce Single Product - Title</strong>';

			case 'woocommerce_template_single_rating':
				return 'Print the product rating.<br>Use element <strong>WooCommerce Single Product - Rating</strong>';

			case 'woocommerce_template_single_price':
				return 'Print the product price.<br>Use element <strong>WooCommerce Single Product - Price</strong>';

			case 'woocommerce_template_single_excerpt':
				return 'Print the product short description (excerpt).<br>Use element <strong>WooCommerce Single Product - Excerpt</strong>';

			case 'woocommerce_template_single_add_to_cart':
				return 'Print the single product add to cart action.<br>Use element <strong>WooCommerce Single Product - Add to Cart</strong>';

			case 'woocommerce_template_single_meta':
				return 'Print the product meta.<br>Use element <strong>WooCommerce Single Product - Meta</strong>';

			case 'woocommerce_template_single_sharing':
				return 'Print the product sharing.<br>Use element <strong>WooCommerce Single Product - Sharing</strong>';

			case 'woocommerce_output_product_data_tabs':
				return 'Print the product tabs.<br>Use element <strong>WooCommerce Single Product - Data Tabs</strong>';

			case 'woocommerce_upsell_display':
				return 'Print product up sells.<br>Use element <strong>WooCommerce Single Product - Upsell Display Loop Wrapper</strong>';

			case 'woocommerce_output_related_products':
				return 'Print the related products.<br>Use element <strong>WooCommerce Single Product - Related Products Loop Wrapper</strong>';

			case 'woocommerce_get_sidebar':
				return 'Print the shop sidebar template.<br>Use element <strong>Sidebar</strong>';

			/* Product Archive */

			case 'woocommerce_taxonomy_archive_description':
				return 'Show an archive description on taxonomy archives.<br>Use element <strong>WooCommerce Product Archive - Archive Description</strong>';

			case 'woocommerce_product_archive_description':
				return 'Show a shop page description on product archives.<br>Use element <strong>WooCommerce Product Archive - Archive Description</strong>';

			case 'woocommerce_template_loop_product_link_open':
				return 'Link wrapper start.<br>Is used in elements in the loop';

			case 'woocommerce_template_loop_product_link_close':
				return 'Link wrapper end.<br>Is used in elements in the loop';

			case 'woocommerce_show_product_loop_sale_flash':
				return 'Print the product sale flash.<br>Use element <strong>WooCommerce Archive Product Loop - Sale Flash</strong>';

			case 'woocommerce_template_loop_product_thumbnail':
				return 'Print the product thumbnail image.<br>Use element <strong>WooCommerce Archive Product Loop - Thumbnail</strong>';

			case 'woocommerce_template_loop_product_title':
				return 'Print the product title.<br>Use element <strong>WooCommerce Archive Product Loop - Title</strong>';

			case 'woocommerce_template_loop_rating':
				return 'Print the product rating.<br>Use element <strong>WooCommerce Archive Product Loop - Rating</strong>';

			case 'woocommerce_template_loop_price':
				return 'Print the product price.<br>Use element <strong>WooCommerce Archive Product Loop - Price</strong>';

			case 'woocommerce_template_loop_add_to_cart':
				return 'Print the product add to cart button.<br>Use element <strong>WooCommerce Archive Product Loop - Add To Cart</strong>';

			default:
				return 'Unknown function from the plugin.<br>Use element <strong>HTML and PHP Code</strong> in the PHP mode and call this function.';
		}
	}

	protected function _isKnownWCFunction( $func ){
		switch( $func ){
			/* in Product Single */

			case 'woocommerce_breadcrumb':
			case 'woocommerce_show_product_sale_flash':
			case 'woocommerce_show_product_images':
			case 'woocommerce_template_single_title':
			case 'woocommerce_template_single_rating':
			case 'woocommerce_template_single_price':
			case 'woocommerce_template_single_excerpt':
			case 'woocommerce_template_single_add_to_cart':
			case 'woocommerce_template_single_meta':
			case 'woocommerce_template_single_sharing':
			case 'woocommerce_output_product_data_tabs':
			case 'woocommerce_upsell_display':
			case 'woocommerce_output_related_products':
			case 'woocommerce_get_sidebar':

			/* Product Archive */

			case 'woocommerce_taxonomy_archive_description':
			case 'woocommerce_product_archive_description':
			case 'woocommerce_template_loop_product_link_open':
			case 'woocommerce_template_loop_product_link_close':

			case 'woocommerce_show_product_loop_sale_flash':
			case 'woocommerce_template_loop_product_thumbnail':
			case 'woocommerce_template_loop_product_title':
			case 'woocommerce_template_loop_rating':
			case 'woocommerce_template_loop_price':
			case 'woocommerce_template_loop_add_to_cart':

				return true;
			default:
				return false;
		}
	}

	protected function _showAllHookedFunctions( $show_dirty, $check_dirty ){

		$show_dirty = trim($show_dirty);
		$show = array();
		if( ! empty($show_dirty) ) {
			$show_dirty = explode("\n", $show_dirty);
			foreach ( $show_dirty as $item ){
				$item = str_replace('()','', $item);
				$item = str_replace(' ','', $item);
				$item = explode( '//', $item);
				$item = $item[0];
				$item = trim( $item );
				$show[] = $item;
			}
		}

		$check_dirty = trim($check_dirty);
		$check = array();
		if( ! empty($check_dirty) ) {
			$check_dirty = explode("\n", $check_dirty);
			foreach ( $check_dirty as $item ){
				$item = str_replace('()','', $item);
				$item = str_replace(' ','', $item);
				$item = explode( '//', $item);
				$item = $item[0];
				$item = trim( $item );
				$check[] = $item;
			}
		}

		echo '<div>';
		echo '<table class="table"><thead><tr><th>Hook</th><th>Priority</th><th>Function</th></tr></thead><tbody>';
		foreach( $show as $hook ){
			$this->_showFunctionsOnHook( $hook, $check );
		}
		echo '</tbody></table>';
		echo '</div>';
	}

	protected function _showFunctionsOnHook( $hook, $check ){
		global $wp_filter;

		$is_hook_added = false;
		if( in_array($hook, $check) ) {
			$hook_title = '<del style="color:green">' . $hook . '</del>';
			$is_hook_added = true;
		}else{
			$hook_title = '<u style="color:orange">' . $hook . '</u>';
		}

		if( empty( $wp_filter[ $hook ] ) ){
			echo '<tr>';
			echo '<td>'.$hook_title.'</td>';
			echo '<td colspan="2">No function hooked</td>';
			echo '</tr>';
			return;
		}

		foreach( $wp_filter[ $hook ]->callbacks as $priority => $all_functions ){
			foreach( $all_functions as $func ){
				echo '<tr>';
				echo '<td>'.$hook_title.'</td>';
				$hook_title = '&nbsp;';
				echo '<td>'.$priority.'</td>';
				echo '<td>';

				$func_title = '';

				if( is_array( $func['function'] ) ) {
					if( is_object( $func['function'][0] ) ) {
						$func_title = get_class($func['function'][0]) . '::' . $func['function'][1];
					} else if( is_string( $func['function'][0] ) ) {
						$func_title = $func['function'][0] . '::' . $func['function'][1];
					} else {
						echo '<pre>';
						print_r( $func['function'] );
						echo '</pre>';
					}
				} else if( is_string( $func['function'] ) ) {
					$func_title = $func['function'];
				} else {
					echo '<pre>';
					print_r( $func['function'] );
					echo '</pre>';
				}

				if( ! empty( $func_title ) ){
					if( $is_hook_added or in_array( $func_title, $check ) ){
						echo '<del style="color:green">' . $func_title . '()</del>';
					}else{
						if( $this->_isKnownWCFunction($func_title) ){
							echo '<u style="color:orange">' . $func_title . '()</u>';
						}else{
							echo '<strong style="color:red">' . $func_title . '()</strong>';
						}
						echo '<br>';
						echo $this->_getWCFunctionDescription($func_title);
					}
				}

				echo '</td>';
				echo '</tr>';
			}
		}
	}

	protected function _render( ffOptionsQueryDynamic $query, $content, $data, $uniqueId ) {
		echo '<div class="woocommerce-hooks">';
		if( is_user_logged_in() and current_user_can('administrator') ) {
			$this->_showAllHookedFunctions(
				$query->getWithoutComparationDefault('stuff-to-show','') ,
				$query->getWithoutComparationDefault('stuff-to-check','')
			);
		}
		echo '</div>';
	}


	protected function _renderContentInfo_JS() {
		?>
		<script data-type="ffscript">
			function ( query, options, $elementPreview, $element ) {
				query.addPlainText('WooCommerce');
				query.addBreak();
				query.addBreak();
				query.addPlainText('Hook and functions Debugger');
				query.addBreak();
				query.addText('stuff-to-show');
				query.addBreak();
				query.addText('stuff-to-check');
			}
		</script data-type="ffscript">
		<?php
	}

}