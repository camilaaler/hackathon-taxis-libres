<?php

class ffElProductArchiveArchiveTitle extends ffThemeBuilderElementBasic {
	protected function _initData() {
		$this->_setData( ffThemeBuilderElement::DATA_ID, 'woocommerce-product-archive--archive-title');
		$this->_setData( ffThemeBuilderElement::DATA_NAME, 'WC - Archive - Current Taxonomy Title');
		$this->_setData( ffThemeBuilderElement::DATA_HAS_DROPZONE, false);
		$this->_setData( ffThemeBuilderElement::DATA_CAN_BE_CACHED, false);

		$this->_setData( ffThemeBuilderElement::DATA_PICKER_MENU_ID, 'wc-archive');
		$this->_setData( ffThemeBuilderElement::DATA_PICKER_TAGS, 'woocommerce, archive, wc, title, heading');
		$this->_setData( ffThemeBuilderElement::DATA_PICKER_ITEM_WIDTH, 0);
	}

	protected function _getElementGeneralOptions( $s ) {
		$s->addElement( ffOneElement::TYPE_TABLE_START );

		$s->addElement(ffOneElement::TYPE_TABLE_DATA_START, '', 'Wrapping');
		$s->addOption(ffOneOption::TYPE_CHECKBOX, 'is-wrapped', 'Wrap by &nbsp;', 1);
		$s->addOption(ffOneOption::TYPE_TEXT, 'tag', '', 'h1');
		$s->addElement(ffOneElement::TYPE_DESCRIPTION, '', 'If you have problems - for example - after you added the new plugin, check this option.' );
		$s->addElement(ffOneElement::TYPE_TABLE_DATA_END);

		$s->addElement(ffOneElement::TYPE_TABLE_END);
	}

	protected function _render( ffOptionsQueryDynamic $query, $content, $data, $uniqueId ) {
		$is_wrapped = $query->get('is-wrapped');
		$tag = $query->get('tag');
		if( $is_wrapped ) {
			echo '<'.$tag.' class="ark-woocommerce-product-archive-title woocommerce-products-header__title page-title">';
		}
		woocommerce_page_title();
		if( $is_wrapped ){
			echo '</'.$tag.'>';
		}
	}


	protected function _renderContentInfo_JS() {
		?>
		<script data-type="ffscript">
			function ( query, options, $elementPreview, $element ) {
				query.addPlainText('WooCommerce - Product Archive');
				query.addBreak();
				query.addHeadingSm(null, 'Archive Title');
			}
		</script data-type="ffscript">
		<?php
	}

}