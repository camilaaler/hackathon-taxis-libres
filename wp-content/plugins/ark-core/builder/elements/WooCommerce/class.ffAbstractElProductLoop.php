<?php

abstract class ffAbstractElProductLoop extends ffThemeBuilderElementBasic {

	protected function _fillSelectWithNumberOfColumns( $select, $columns ) {
		foreach( $columns as $oneColumn ) {
			$select->addSelectValue( $oneColumn, $oneColumn );
		}
	}

	protected function _getElementColumnOptions( $s ) {
		$xs = 1;
		$sm = 3;
		$md = 4;
		$lg = 4;

		$numberOfColumns = array(1,2,3,4,5,6,7,8,9);

		$s->addElement( ffOneElement::TYPE_TABLE_DATA_START, '', ark_wp_kses( __('Columns Settings', 'ark' ) ) );

			$s->startSection('grid');

				$select = $s->addOptionNL(ffOneOption::TYPE_SELECT, 'xs', 'Phone (XS)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $xs);
				$this->_fillSelectWithNumberOfColumns( $select, $numberOfColumns );
				$select->addParam( ffOneOption::PARAM_TITLE_AFTER, ark_wp_kses( __('Column(s)', 'ark' ) ) );

				$select = $s->addOptionNL(ffOneOption::TYPE_SELECT, 'sm', 'Tablet (SM)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $sm);
				$this->_fillSelectWithNumberOfColumns( $select, $numberOfColumns );
				$select->addParam( ffOneOption::PARAM_TITLE_AFTER, ark_wp_kses( __('Column(s)', 'ark' ) ) );

				$select = $s->addOptionNL(ffOneOption::TYPE_SELECT, 'md', 'Laptop (MD)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $md);
				$this->_fillSelectWithNumberOfColumns( $select, $numberOfColumns );
				$select->addParam( ffOneOption::PARAM_TITLE_AFTER, ark_wp_kses( __('Column(s)', 'ark' ) ) );

				$select = $s->addOptionNL(ffOneOption::TYPE_SELECT, 'lg', 'Desktop (LG)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $lg);
				$this->_fillSelectWithNumberOfColumns( $select, $numberOfColumns );
				$select->addParam( ffOneOption::PARAM_TITLE_AFTER, ark_wp_kses( __('Column(s)', 'ark' ) ) );

				$s->addElement(ffOneElement::TYPE_NEW_LINE);
				$s->addOptionNL( ffOneOption::TYPE_TEXT, 'space', '', '30')
				  ->addParam( ffOneOption::PARAM_TITLE_AFTER, ark_wp_kses( __('Custom horizontal gap between columns (in px)', 'ark' ) ) );
				$s->addOptionNL( ffOneOption::TYPE_TEXT, 'space-vertical', '', '30')
				  ->addParam( ffOneOption::PARAM_TITLE_AFTER, ark_wp_kses( __('Custom vertical gap between columns (in px)', 'ark' ) ) );

			$s->endSection();

		$s->addElement( ffOneElement::TYPE_TABLE_DATA_END);
	}

	/**
	 * @param $s ffOneStructure|ffThemeBuilderOptionsExtender
	 * @return mixed
	 */
	protected function _getElementLoopOptions( $s ){
		$s->addElement( ffOneElement::TYPE_TABLE_DATA_START, '', ark_wp_kses( __('Products Settings', 'ark' ) ) );
			$s->startSection('loop');

				$select = $s->addOptionNL(ffOneOption::TYPE_SELECT, 'orderby','');
				$select->addSelectValue('Random', 'rand' );
				$select->addSelectValue('Title', 'title' );
				$select->addSelectValue('ID', 'ID' );
				$select->addSelectValue('Date', 'date' );
				$select->addSelectValue('Modified Date', 'modified' );
				$select->addSelectValue('Menu order', 'menu_order' );
//				$select->addSelectValue('Price', 'price' );
				$select->addParam( ffOneOption::PARAM_TITLE_AFTER, ark_wp_kses( __('Order by', 'ark' ) ) );

				$select = $s->addOptionNL(ffOneOption::TYPE_SELECT, 'order', '');
				$select->addSelectValue('Ascending ( 1, 2, 3 )', 'asc' );
				$select->addSelectValue('Descending ( 3, 2, 1 )', 'desc' );
				$select->addParam( ffOneOption::PARAM_TITLE_AFTER, ark_wp_kses( __('Order', 'ark' ) ) );

				$s->addOption( ffOneOption::TYPE_TEXT, 'limit', '', '4' )
					->addParam( ffOneOption::PARAM_TITLE_AFTER, __('Maximum Product Count', 'ark') );

			$s->endSection();
		$s->addElement( ffOneElement::TYPE_TABLE_DATA_END);
	}

	/**
	 * @param $s ffOneStructure|ffThemeBuilderOptionsExtender
	 * @return mixed
	 */
	protected function _getElementProductInTheLoopWrapper( $s ){
		$s->addElement(ffOneElement::TYPE_TABLE_DATA_START, '', 'Product Wrapper');
			$s->startAdvancedToggleBox('product-wrapper', 'Product Wrapper');
				$s->addElement( ffOneElement::TYPE_DESCRIPTION, '', 'This is the Product Wrapper (in the Loop) that you can edit and style');
				$s->addOption(ffOneOption::TYPE_HIDDEN, 'blank', '', 'blank');
			$s->endAdvancedToggleBox();
		$s->addElement( ffOneElement::TYPE_TABLE_DATA_END);
	}

	protected function _renderCSSForColumns( $query ){

		/* Width on breakpoints */

		$bps = array(
			'xs' => 'max-width:767px',
			'sm' => 'min-width:768px) and (max-width:991px',
			'md' => 'min-width:992px) and (max-width:1199px',
			'lg' => 'min-width:1200px',
		);

		foreach ( $bps as $bp => $bp_css ) {
			$columns = absint( $query->get( $bp ) );

			$width = floor( 10000 / $columns ) / 100;

			$css = '';
			$css .= 'width:' . $width . '% !important;';



				// OLD CODE
					//$css .= 'margin: 0 !important';

				// NEW CODE, SEE SUPPORT TICKET #1296914122
					$css .= 'margin: 0';



			$r = $this->_getAssetsRenderer()->createCssRule();
			$r->addSelector( 'ul.products li.product' );
			$r->addBreakpoint($bp_css);
			$r->addParamsString( $css );

			$r = $this->_getAssetsRenderer()->createCssRule();
			$r->addSelector( 'ul.products li.product:nth-child(' . $columns . 'n+1)' );
			$r->addBreakpoint($bp_css);
			$r->addParamsString( 'clear:both;' );
		}


		/* Spaces */

		$spaceBetweenColumns = $query->get('space');

		if( $spaceBetweenColumns !== '' ) {
			$spaceBetweenColumns = floatval($spaceBetweenColumns);
			$oneHalf = $spaceBetweenColumns / 2;

			$rowCss = '';
			$rowCss .= 'margin-left: -' . $oneHalf .'px;';
			$rowCss .= 'margin-right: -' .$oneHalf .'px;';

			$colCss = '';
			$colCss .= 'padding-left:' . $oneHalf .'px;';
			$colCss .= 'padding-right:' .$oneHalf .'px;';


			$this->_getAssetsRenderer()
			     ->createCssRule()
			     ->addSelector('ul.products')
			     ->addParamsString($rowCss );

			$this->_getAssetsRenderer()
			     ->createCssRule()
			     ->addSelector('ul.products li.product')
			     ->addParamsString($colCss );
		}


		$spaceVertical = $query->get('space-vertical');

		if( $spaceVertical != '' ) {
			$spaceVertical = absint( $spaceVertical );

			$this->_getAssetsRenderer()
			     ->createCssRule()
			     ->addSelector('ul.products li.product')
			     ->addParamsString('margin-bottom:' . $spaceVertical . 'px;');
		}
	}

	protected function getEmptyWCTemplatePath(){
		return dirname( __FILE__) . DIRECTORY_SEPARATOR . 'empty-wc-template.php';
	}

}