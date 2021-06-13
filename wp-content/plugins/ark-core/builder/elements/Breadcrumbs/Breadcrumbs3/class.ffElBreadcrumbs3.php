<?php

/**
 * @link http://demo.freshface.net/html/h-ark/HTML/shortcodes_breadcrumbs.html#scrollTo__600
 */

class ffElBreadcrumbs3 extends ffThemeBuilderElementBasic {
	protected function _initData() {
		$this->_setData( ffThemeBuilderElement::DATA_ID, 'breadcrumbs-3');
		$this->_setData( ffThemeBuilderElement::DATA_NAME, esc_attr( __('Titlebar 3', 'ark' ) ) );
		$this->_setData( ffThemeBuilderElement::DATA_HAS_DROPZONE, false);
		$this->_setData( ffThemeBuilderElement::DATA_HAS_CONTENT_PARAMS, true);
		$this->_setData( ffThemeBuilderElement::DATA_CAN_BE_CACHED, false);

		$this->_setData( ffThemeBuilderElement::DATA_PICKER_MENU_ID, 'titlebar');
		$this->_setData( ffThemeBuilderElement::DATA_PICKER_TAGS, 'breadcrumbs, titlebar, title, bar');
		$this->_setData( ffThemeBuilderElement::DATA_PICKER_ITEM_WIDTH, 2);

		$this->_setColor('light');
	}

	protected function _getElementGeneralOptions( $s ) {

		$s->addElement( ffOneElement::TYPE_TABLE_START );

			$s->addElement(ffOneElement::TYPE_TABLE_DATA_START, '', ark_wp_kses( __('Info'.ffArkAcademyHelper::getInfo(116), 'ark' ) ) );
				$s->addElement( ffOneElement::TYPE_DESCRIPTION,'', ark_wp_kses( __('This element can be used to print the breadcrumbs navigation for your visitors.', 'ark' ) ));
			$s->addElement(ffOneElement::TYPE_TABLE_DATA_END);

			$this->_getBlock( ffThemeBlConst::PAGETITLE )->injectOptions( $s );

			$s->addElement( ffOneElement::TYPE_TABLE_DATA_START, '', ark_wp_kses( __('Title Colors', 'ark' ) ) );

				$s->addOptionNL( ffOneOption::TYPE_COLOR_PICKER_WITH_LIB, 'title-color', '' , '#ffffff')
					->addParam( ffOneOption::PARAM_TITLE_AFTER, ark_wp_kses( __('Title', 'ark' ) ) );

			$s->addElement( ffOneElement::TYPE_TABLE_DATA_END);

			$s->addElement( ffOneElement::TYPE_TABLE_DATA_START, '', ark_wp_kses( __('Breadcrumbs', 'ark' ) ) );
				$s->addOption( ffOneOption::TYPE_TEXT, 'breadcrumbs-home', '', get_bloginfo('name') )
					->addParam( ffOneOption::PARAM_TITLE_AFTER, ark_wp_kses( __('Front Page Label', 'ark' ) ) );
				$s->addElement(ffOneElement::TYPE_DESCRIPTION,'', ark_wp_kses( __('This is the first titlebar item that automatically links to your front page', 'ark' ) ) );
			$s->addElement( ffOneElement::TYPE_TABLE_DATA_END);

			$s->addElement( ffOneElement::TYPE_TABLE_DATA_START, '', ark_wp_kses( __('Breadcrumbs Colors', 'ark' ) ) );

				$s->addOptionNL( ffOneOption::TYPE_COLOR_PICKER_WITH_LIB, 'bc-parent-item', '' , '#ffffff')
					->addParam( ffOneOption::PARAM_TITLE_AFTER, ark_wp_kses( __('Parent Item', 'ark' ) ) );

				$s->addOptionNL( ffOneOption::TYPE_COLOR_PICKER_WITH_LIB, 'bc-parent-item-hover', '' , '[1]')
					->addParam( ffOneOption::PARAM_TITLE_AFTER, ark_wp_kses( __('Parent Item - Hover', 'ark' ) ) );

				$s->addOptionNL( ffOneOption::TYPE_COLOR_PICKER_WITH_LIB, 'bc-separator', '' , '#ffffff')
					->addParam( ffOneOption::PARAM_TITLE_AFTER, ark_wp_kses( __('Separator Item', 'ark' ) ) );

				$s->addOptionNL( ffOneOption::TYPE_COLOR_PICKER_WITH_LIB, 'bc-current-item', '' , '[1]')
					->addParam( ffOneOption::PARAM_TITLE_AFTER, ark_wp_kses( __('Current Item', 'ark' ) ) );

			$s->addElement( ffOneElement::TYPE_TABLE_DATA_END);

			$s->addElement( ffOneElement::TYPE_TABLE_DATA_START, '', ark_wp_kses( __('Titlebar Colors', 'ark' ) ) );

				$s->addOptionNL( ffOneOption::TYPE_COLOR_PICKER_WITH_LIB, 'bg-color', '' , 'rgba(128,128,128,0.5)')
					->addParam( ffOneOption::PARAM_TITLE_AFTER, ark_wp_kses( __('Background', 'ark' ) ) );

			$s->addElement( ffOneElement::TYPE_TABLE_DATA_END);

		$s->addElement( ffOneElement::TYPE_TABLE_END );

	}

	protected function _beforeRenderingAdminWrapper( ffOptionsQueryDynamic $query, $content, ffMultiAttrHelper $multiAttrHelper, ffStdClass $otherData ) {

	}

	protected function _render( ffOptionsQueryDynamic $query, $content, $data, $uniqueId ) {

		$bg_selectors = array(
			'.breadcrumbs-v3' => 'bg-color',
		);

		foreach( $bg_selectors as $selector => $c ){
			$color = $query->getColor( $c );
			if( $color ) {
				$this->getAssetsRenderer()->createCssRule()
					->setAddWhiteSpaceBetweenSelectors(false)
					->addSelector($selector, false)
					->addParamsString('background-color:' . $color . ';');
			}
		}

		$text_color_selectors = array(
			' .breadcrumbs-v3-left-wing .breadcrumbs-v3-title' => 'title-color',
			' .breadcrumbs-v3-links > li > a' => 'bc-parent-item',
			' .breadcrumbs-v3-links > li > a:hover' => 'bc-parent-item-hover',
			' .breadcrumbs-v3-links > li + li:before' => 'bc-separator',
			' .breadcrumbs-v3-links > li.active' => 'bc-current-item',
		);

		foreach( $text_color_selectors as $selector => $c ){
			$color = $query->getColor( $c );
			if( $color ) {
				$this->getAssetsRenderer()->createCssRule()
					->setAddWhiteSpaceBetweenSelectors(false)
					->addSelector($selector, false)
					->addParamsString('color:' . $color . ';');
			}
		}

		echo '<section class="breadcrumbs-v3">';

		$breadcrumbsCollection = ffContainer()->getLibManager()->createBreadcrumbs()->generateBreadcrumbs();

		echo '<div class="breadcrumbs-v3-left-wing">';
		echo '<h2 class="breadcrumbs-v3-title">';
		$this->_getBlock( ffThemeBlConst::PAGETITLE )->render( $query );
		echo '</h2>';
		echo '</div>';

		echo '<ol class="breadcrumbs-v3-links">';
		foreach( $breadcrumbsCollection as $oneItem ) {

			if( $oneItem->queryType == ffConstQuery::HOME ) {
				$oneItem->name = $query->get('breadcrumbs-home');
				if( empty($oneItem->name) ){
					continue;
				}
			}

			if( $oneItem->isSelected ) {
				echo '<li class="active">' . ark_wp_kses($oneItem->name) . '</li>';
			} else {
				echo '<li><a href="'. esc_attr($oneItem->url) .'">'. ark_wp_kses($oneItem->name) .'</a></li>';
			}
		}
		echo '</ol>';

		echo '</section>';

	}

	protected function _renderContentInfo_JS() {
		?>
		<script data-type="ffscript">
			function ( query, options, $elementPreview, $element, preview ) {
				query.addHeadingLg( null, 'Breadcrumbs' );
			}
		</script data-type="ffscript">
		<?php
	}


}