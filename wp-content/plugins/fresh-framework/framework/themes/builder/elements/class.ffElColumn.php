<?php

class ffElColumn extends ffThemeBuilderElementBasic {
	protected function _initData() {
		$this->_setData( ffThemeBuilderElement::DATA_ID, 'column');
		$this->_setData( ffThemeBuilderElement::DATA_NAME, 'Column');
		$this->_setData( ffThemeBuilderElement::DATA_HAS_DROPZONE, true);

		$this->_setData( ffThemeBuilderElement::DATA_PICKER_MENU_ID, 'grid');
		$this->_setData( ffThemeBuilderElement::DATA_PICKER_TAGS, 'grid, bootstrap, column, col');
		$this->_setData( ffThemeBuilderElement::DATA_PICKER_ITEM_WIDTH, 0);


//		$this->_addDropzoneBlacklistedElement('column');


		$this->_addParentWhitelistedElement('row');
		$this->_addParentWhitelistedElement('section');

		$this->_addTab('Advanced', array($this, '_advancedTab'));
	}

	public function getPreviewImageUrl() {
		return ffContainer()->getWPLayer()->getFrameworkUrl() . '/framework/themes/builder/elements/column.jpg';
	}

	protected function _getElementGeneralOptions( $s ) {

		$s->addElement( ffOneElement::TYPE_TABLE_START );

			$breakpoints = array(
				'xs' => 'Phone (XS)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
				'sm' => 'Tablet (SM)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
				'md' => 'Laptop (MD)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
				'lg' => 'Desktop (LG)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
			);

			/////////////////////////////////////////////////////////////////////////////////////
			// Width
			/////////////////////////////////////////////////////////////////////////////////////

			$s->addElement(ffOneElement::TYPE_TABLE_DATA_START, '', 'Column Width' );

				$default_values = array(
					'xs' => '12' ,
					'sm' => 'unset' ,
					'md' => '4' ,
					'lg' => 'unset' ,
				);

				$prev_bp = '';
				foreach( $breakpoints as $bp => $bp_title ){

					$opt = $s->addOption(ffOneOption::TYPE_SELECT, $bp, $bp_title, $default_values[$bp] );
					if ( 'xs' != $bp ){
						$opt->addSelectValue($prev_bp, 'unset');
					}
					$opt->addSelectNumberRange(1,12);
					
					$s->addElement(ffOneElement::TYPE_HTML, '', ' / 12');
					// if ( 'md' == $bp ){
					// 	$s->addElement(ffOneElement::TYPE_HTML, '', '<sup> *</sup>');
					// }
					$s->addElement( ffOneElement::TYPE_NEW_LINE );

					// $prev_bp = 'Inherit from ' . strtoupper($bp);
					$prev_bp = 'Inherit';

				}

				$s->addElement( ffOneElement::TYPE_NEW_LINE );
				$s->addElement( ffOneElement::TYPE_DESCRIPTION, '', 'TIP: You can change the largest specified column width directly in the page builder by clicking on the left and right arrows in the Column element.');

			$s->addElement(ffOneElement::TYPE_TABLE_DATA_END);

			
			/////////////////////////////////////////////////////////////////////////////////////
			// Center Content
			/////////////////////////////////////////////////////////////////////////////////////
			$s->addElement(ffOneElement::TYPE_TABLE_DATA_START, '', 'Vertical Centering'.ffArkAcademyHelper::getInfo(71) );
				$s->addOption(ffOneOption::TYPE_CHECKBOX,'is-centered','Vertically center the content inside this column',0);
				$s->addElement( ffOneElement::TYPE_DESCRIPTION, '', 'Only works when the parent Row/Section element is set to <code>equalize the height of all inner Columns</code> or if you manually set a fixed height for this column (in pixels).');
			$s->addElement(ffOneElement::TYPE_TABLE_DATA_END);


			$s->addElement(ffOneElement::TYPE_TABLE_DATA_START, '', 'Equalize Height'.ffArkAcademyHelper::getInfo(72) );
				$s->addOption(ffOneOption::TYPE_CHECKBOX,'not-equalize','Do not equalize the height of this Column',0);
			$s->addElement(ffOneElement::TYPE_TABLE_DATA_END);




			$s->addElement(ffOneElement::TYPE_TABLE_DATA_START, '', 'Background Clip' );
				$s->addOption(ffOneOption::TYPE_CHECKBOX,'is-bg-clipped','Clip column paddings from background',0);
			$s->addElement(ffOneElement::TYPE_TABLE_DATA_END);

			/////////////////////////////////////////////////////////////////////////////////////
			// Information
			/////////////////////////////////////////////////////////////////////////////////////

			$s->addElement(ffOneElement::TYPE_TABLE_DATA_START, '', 'Information');
				// $s->addElement( ffOneElement::TYPE_DESCRIPTION, '', 'All breakpoint related settings will be applied for that particular breakpoint and every other breakpoint that is larger. So for example the same Column Width applied on the Tablet (SM) breakpoint will be applied on the Laptop (MD) and Desktop (LG) breakpoints as well, but not on the Phone (XS) breakpoint. If you want to change this behaviour you can simply overwrite it by specifying a different value in the larger breakpoint.');
				// $s->addElement( ffOneElement::TYPE_NEW_LINE );
				// $s->addElement( ffOneElement::TYPE_DESCRIPTION, '', 'Column <strong>Offset</strong> uses left padding to offset the Column.');
				// $s->addElement( ffOneElement::TYPE_NEW_LINE );
				// $s->addElement( ffOneElement::TYPE_DESCRIPTION, '', 'Column <strong>Pull</strong> and <strong>Push</strong> allows you to change the order of your Columns for different breakpoints.');
				// $s->addElement( ffOneElement::TYPE_NEW_LINE );
				$s->addElement( ffOneElement::TYPE_DESCRIPTION, '', 'More detailed information about the Bootstrap Grid can be found here: <a target="_blank" href="http://getbootstrap.com/css/#grid">http://getbootstrap.com/css/#grid</a>');
				// $s->addElement( ffOneElement::TYPE_NEW_LINE );
				// $s->addElement(ffOneElement::TYPE_HTML, '', '–––––––––');
				// $s->addElement( ffOneElement::TYPE_DESCRIPTION, '', '* This will apply "clearfix" after this Column to reset the floating. So the next Column after this one will be pushed onto a new line.');
				
			$s->addElement(ffOneElement::TYPE_TABLE_DATA_END);


		$s->addElement( ffOneElement::TYPE_TABLE_END );



	}


	/**
	 * @param $s ffThemeBuilderOptionsExtender
	 */
	protected function _advancedTab( $s ) {

		$s->addElement( ffOneElement::TYPE_TABLE_START );

			$breakpoints = array(
				'xs' => 'Phone (XS)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
				'sm' => 'Tablet (SM)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
				'md' => 'Laptop (MD)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
				'lg' => 'Desktop (LG)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
			);
			$prev_bp = '';

			/////////////////////////////////////////////////////////////////////////////////////
			// Last Column
			/////////////////////////////////////////////////////////////////////////////////////

			$s->addElement(ffOneElement::TYPE_TABLE_DATA_START, '', 'Last Column'.ffArkAcademyHelper::getInfo(86) );

				$default_values = array(
					'xs' => 'no' ,
					'sm' => 'unset' ,
					'md' => 'unset' ,
					'lg' => 'unset' ,
				);

				foreach( $breakpoints as $bp => $bp_title ){

					$opt = $s->addOption(  ffOneOption::TYPE_SELECT, $bp.'-last', $bp_title, $default_values[$bp] );
					if ( 'xs' != $bp ){
						$opt->addSelectValue($prev_bp, 'unset');
					}
					$opt->addSelectValue('No', 'no');
					$opt->addSelectValue('Yes', 'yes');
					
					$s->addElement( ffOneElement::TYPE_NEW_LINE );

					// $prev_bp = 'Inherit from ' . strtoupper($bp);
					$prev_bp = 'Inherit';

				}

				$s->addElement( ffOneElement::TYPE_NEW_LINE );
				$s->addElement( ffOneElement::TYPE_DESCRIPTION, '', 'This will apply "clearfix" after this Column to reset the floating. So the next Column after this one will be pushed onto a new line.');

			$s->addElement(ffOneElement::TYPE_TABLE_DATA_END);

			/////////////////////////////////////////////////////////////////////////////////////
			// Offset
			/////////////////////////////////////////////////////////////////////////////////////

			$s->addElement(ffOneElement::TYPE_TABLE_DATA_START, '', 'Column Offset'.ffArkAcademyHelper::getInfo(87) );

				$default_values = array(
					'xs' => 'unset' ,
					'sm' => 'unset' ,
					'md' => 'unset' ,
					'lg' => 'unset' ,
				);

				foreach( $breakpoints as $bp => $bp_title ){

					$opt = $s->addOption(  ffOneOption::TYPE_SELECT, $bp.'-offset', $bp_title, $default_values[$bp] );
					if ( 'xs' != $bp ){
						$opt->addSelectValue($prev_bp, 'unset');
						$opt->addSelectValue('0', '0');
					} else {
						$opt->addSelectValue('0', 'unset');
					}
					$opt->addSelectNumberRange(1,12);
					
					$s->addElement(ffOneElement::TYPE_HTML, '', ' / 12');
					
					$s->addElement( ffOneElement::TYPE_NEW_LINE );

					// $prev_bp = 'Inherit from ' . strtoupper($bp);
					$prev_bp = 'Inherit';

				}

				$s->addElement( ffOneElement::TYPE_NEW_LINE );
				$s->addElement( ffOneElement::TYPE_DESCRIPTION, '', 'Offset uses left padding to offset the Column.');

			$s->addElement(ffOneElement::TYPE_TABLE_DATA_END);

			/////////////////////////////////////////////////////////////////////////////////////
			// Pull
			/////////////////////////////////////////////////////////////////////////////////////

			$s->addElement(ffOneElement::TYPE_TABLE_DATA_START, '', 'Column Pull Left' );

				$default_values = array(
					'xs' => 'unset' ,
					'sm' => 'unset' ,
					'md' => 'unset' ,
					'lg' => 'unset' ,
				);

				foreach( $breakpoints as $bp => $bp_title ){

					$opt = $s->addOption(  ffOneOption::TYPE_SELECT, $bp.'-pull', $bp_title, $default_values[$bp] );
					if ( 'xs' != $bp ){
						$opt->addSelectValue($prev_bp, 'unset');
						$opt->addSelectValue('0', '0');
					} else {
						$opt->addSelectValue('0', 'unset');
					}
					$opt->addSelectNumberRange(1,12);
					
					$s->addElement(ffOneElement::TYPE_HTML, '', ' / 12');
					
					$s->addElement( ffOneElement::TYPE_NEW_LINE );

					// $prev_bp = 'Inherit from ' . strtoupper($bp);
					$prev_bp = 'Inherit';

				}

				$s->addElement( ffOneElement::TYPE_NEW_LINE );
				$s->addElement( ffOneElement::TYPE_DESCRIPTION, '', 'Pull allows you to change the order of your Columns for different breakpoints.');

			$s->addElement(ffOneElement::TYPE_TABLE_DATA_END);

			/////////////////////////////////////////////////////////////////////////////////////
			// Push
			/////////////////////////////////////////////////////////////////////////////////////

			$s->addElement(ffOneElement::TYPE_TABLE_DATA_START, '', 'Column Push Right' );

				$default_values = array(
					'xs' => 'unset' ,
					'sm' => 'unset' ,
					'md' => 'unset' ,
					'lg' => 'unset' ,
				);

				foreach( $breakpoints as $bp => $bp_title ){

					$opt = $s->addOption(  ffOneOption::TYPE_SELECT, $bp.'-push', $bp_title, $default_values[$bp] );
					if ( 'xs' != $bp ){
						$opt->addSelectValue($prev_bp, 'unset');
						$opt->addSelectValue('0', '0');
					} else {
						$opt->addSelectValue('0', 'unset');
					}
					$opt->addSelectNumberRange(1,12);
					
					$s->addElement(ffOneElement::TYPE_HTML, '', ' / 12');
					
					$s->addElement( ffOneElement::TYPE_NEW_LINE );

					// $prev_bp = 'Inherit from ' . strtoupper($bp);
					$prev_bp = 'Inherit';

				}

				$s->addElement( ffOneElement::TYPE_NEW_LINE );
				$s->addElement( ffOneElement::TYPE_DESCRIPTION, '', 'Push allows you to change the order of your Columns for different breakpoints.');

			$s->addElement(ffOneElement::TYPE_TABLE_DATA_END);

			/////////////////////////////////////////////////////////////////////////////////////
			// Overlap
			/////////////////////////////////////////////////////////////////////////////////////

			$s->addElement(ffOneElement::TYPE_TABLE_DATA_START, '', 'Column Overlap' );

				$default_values = array(
					'xs' => 'no' ,
					'sm' => 'unset' ,
					'md' => 'unset' ,
					'lg' => 'unset' ,
				);

				foreach( $breakpoints as $bp => $bp_title ){

					$opt = $s->addOption(  ffOneOption::TYPE_SELECT, $bp.'-overlap', $bp_title, $default_values[$bp] );
					if ( 'xs' != $bp ){
						$opt->addSelectValue($prev_bp, 'unset');
					}
					$opt->addSelectValue('No', 'no');
					$opt->addSelectValue('Yes', 'yes');
					
					$s->addElement( ffOneElement::TYPE_NEW_LINE );

					// $prev_bp = 'Inherit from ' . strtoupper($bp);
					$prev_bp = 'Inherit';

				}

				$s->addElement( ffOneElement::TYPE_NEW_LINE );
				$s->addElement( ffOneElement::TYPE_DESCRIPTION, '', 'Overlap allows you to absolutely position this Column over other Columns. Please use Pull and Push options above as well in order to adjust the spacing. It might also be necessary to use the Last Column option to reset the floating if you plan on adding more Columns onto a new row, otherwise the later Columns might end up completely hidden under the former row of Columns.');

			$s->addElement(ffOneElement::TYPE_TABLE_DATA_END);

			/////////////////////////////////////////////////////////////////////////////////////
			// Information
			/////////////////////////////////////////////////////////////////////////////////////

			$s->addElement(ffOneElement::TYPE_TABLE_DATA_START, '', 'Information');
				// $s->addElement( ffOneElement::TYPE_DESCRIPTION, '', 'All breakpoint related settings will be applied for that particular breakpoint and every other breakpoint that is larger. So for example the same Column Width applied on the Tablet (SM) breakpoint will be applied on the Laptop (MD) and Desktop (LG) breakpoints as well, but not on the Phone (XS) breakpoint. If you want to change this behaviour you can simply overwrite it by specifying a different value in the larger breakpoint.');
				// $s->addElement( ffOneElement::TYPE_NEW_LINE );
				// $s->addElement( ffOneElement::TYPE_DESCRIPTION, '', 'Column <strong>Offset</strong> uses left padding to offset the Column.');
				// $s->addElement( ffOneElement::TYPE_NEW_LINE );
				// $s->addElement( ffOneElement::TYPE_DESCRIPTION, '', 'Column <strong>Pull</strong> and <strong>Push</strong> allows you to change the order of your Columns for different breakpoints.');
				// $s->addElement( ffOneElement::TYPE_NEW_LINE );
				$s->addElement( ffOneElement::TYPE_DESCRIPTION, '', 'More detailed information about the Bootstrap Grid can be found here: <a target="_blank" href="http://getbootstrap.com/css/#grid">http://getbootstrap.com/css/#grid</a>');
				// $s->addElement( ffOneElement::TYPE_NEW_LINE );
				// $s->addElement(ffOneElement::TYPE_HTML, '', '–––––––––');
				// $s->addElement( ffOneElement::TYPE_DESCRIPTION, '', '* This will apply "clearfix" after this Column to reset the floating. So the next Column after this one will be pushed onto a new line.');
				
			$s->addElement(ffOneElement::TYPE_TABLE_DATA_END);


		$s->addElement( ffOneElement::TYPE_TABLE_END );

	}

	protected function _beforeRenderingAdminWrapper( ffOptionsQueryDynamic $query, $content, ffMultiAttrHelper $multiAttrHelper, ffStdClass $otherData ) {
		$multiAttrHelper->addParam('class', 'ffb-element-col-4');
	}

	/**
	 * @param ffOptionsQueryDynamic $query
	 */
	private function _getCachedColumn( $query ) {
		$cache = ffContainer()->getDataStorageCache();
		$queryHash = $query->getHash();

		$finalName = 'col-prop-' . $queryHash;

		$namespace = ffThemeBuilderCache::CACHE_NAMESPACE;

		$cachedDataJSON = $cache->getOption( $namespace, $finalName);



		if( !empty( $cachedDataJSON ) ) {
			$cachedData = json_decode( $cachedDataJSON, true );

			return $cachedData;
		} else {
			return null;
		}
	}

	/**
	 * @param ffOptionsQueryDynamic $query
	 */
	private function _setCachedColumn( $data, $query ) {
		$cache = ffContainer()->getDataStorageCache();
		$queryHash = $query->getHash();
		$finalName = 'col-prop-' . $queryHash;

		$namespace = ffThemeBuilderCache::CACHE_NAMESPACE;

		$cachedDataJSON = json_encode( $data );

		$cache->setOption( $namespace, $finalName, $cachedDataJSON);
	}

	protected function _render( ffOptionsQueryDynamic $query, $content, $data, $uniqueId ) {

		$cachedData = $this->_getCachedColumn( $query );

		$shouldPrint = $cachedData;


		if( $shouldPrint == null ) {

			$classes = array();
			$clearfix_classes = array();
			$hasClearfix = false;
			$lastClearfix = 'no';
			$hasOverlap = false;
			$lastOverlap = 'no';

			foreach (array('xs', 'sm', 'md', 'lg') as $bp) {
				$col = $query->get($bp);
				if ($col != 'unset') {
					$classes[] = 'col-' . $bp . '-' . $col;
				}
				$offset = $query->get($bp . '-offset');
				if ($offset != 'unset') {
					$classes[] = 'col-' . $bp . '-offset-' . $offset;
				}
				$pull = $query->get($bp . '-pull');
				if ($pull != 'unset') {
					$classes[] = 'col-' . $bp . '-pull-' . $pull;
				}
				$push = $query->get($bp . '-push');
				if ($push != 'unset') {
					$classes[] = 'col-' . $bp . '-push-' . $push;
				}

				switch ( $query->get($bp . '-last') ) {
					case 'unset':
						if ( 'no' == $lastClearfix ) {
							break;
						}
					case 'yes':
						$hasClearfix = true;
						$clearfix_classes[] = 'visible-' . $bp;
						$lastClearfix = 'yes';
						break;
					case 'no':
						$lastClearfix = 'no';
						break;
				}

				$overlap_default_values = array(
					'xs' => 'no' ,
					'sm' => 'unset' ,
					'md' => 'unset' ,
					'lg' => 'unset' ,
				);

				$overlapDefaultValue = $overlap_default_values[$bp];

				switch ( $query->getWithoutComparationDefault($bp . '-overlap', $overlapDefaultValue) ) {
					case 'unset':
						if ( 'no' == $lastOverlap ) {
							break;
						}
					case 'yes':
						$hasOverlap = true;
						$classes[] = 'fg-overlap-' . $bp;
						$lastOverlap = 'yes';
						break;
					case 'no':
						$lastOverlap = 'no';
						break;
				}

			}

			$isBgClipped = $query->getWithoutComparationDefault( 'is-bg-clipped', false );

			if( $isBgClipped ) {
				$classes[] = 'fg-bg-is-clipped';
			}

			if( $query->getWithoutComparationDefault('not-equalize', 0) ) {
				$classes[] = 'fg-col-not-match';
			}


			ob_start();
			echo '<div class="fg-col ' . esc_attr(implode(' ', $classes)) . '">';
			if( $isBgClipped ) {
				echo '<div class="fg-bg-clipped">';
			}


				if($query->get('is-centered')){
					$oneWrapperPrinted = true;
					echo '<div class="fg-vcenter-wrapper">';
						echo '<div class="fg-vcenter">';
				}
			$cachedData['start'] = ob_get_contents();
			ob_end_clean();

		}

		echo $cachedData['start'];

		$matchColumnHeight = $this->_getStatusHolder()->getMatchColumnsHeight();

			if( $matchColumnHeight ) {
				$atLeastOneWrapperPrinted = true;
				echo '<div class="fg-match-column-inside-wrapper">';
			}

					echo $this->_doShortcode( $content );


			if( $matchColumnHeight ) {
				echo '</div>';
			}

		if( $shouldPrint == null ) {
			ob_start();
			if ($query->get('is-centered')) {
				echo '</div>';
				echo '</div>';
			}

			if ($isBgClipped) {
				echo '</div>';
			}
			echo '</div>';
			if ($hasClearfix) {
				echo '<div class="clearfix ' . esc_attr(implode(' ', $clearfix_classes)) . '"></div>';
			}
			$cachedData['end'] = ob_get_contents();
			ob_end_clean();
		}

		echo $cachedData['end'];

		if( $shouldPrint == null ) {
			$this->_setCachedColumn( $cachedData, $query);
		}

	}

	protected function _renderContentInfo_JS() {
		?>
		<script data-type="ffscript">
			function ( query, options, $elementPreview, $element, blocks, elementModel, elementView ) {

				var getBreakpointName = function() {
					var options = elementView.getOptionsData();

					var breakpoints = new Array();
					var breakpointToReturn = 'xs';

					breakpoints.push( 'lg' );
					breakpoints.push( 'md' );
					breakpoints.push( 'sm' );
					breakpoints.push( 'xs' );

					for( var i in breakpoints ) {
						var oneBp = breakpoints[i];

						if( options.o.gen[oneBp] != 'unset' ) {
							breakpointToReturn = oneBp;

							break;
						}
					}

					return breakpointToReturn;
				};

				var getColumnValue = function() {
					var breakpoint = getBreakpointName( options );

					var options = elementView.getOptionsData();

					return options.o.gen[breakpoint];
				};

				var setColumnValue = function( value ) {
					var breakpoint = getBreakpointName( options );
					var options = elementView.getOptionsData();

					options.o.gen[breakpoint] = value;

					elementView.setOptionsData( options );

				};

				var changeColumnAppearance = function( value ) {
					setColumnValue( value );
					changeColumnName( value )

					elementView.canvasChanged()

					for( var i = 1; i <= 12; i++ ) {
						$element.removeClass('ffb-element-col-' + i);
					}

					$element.addClass( 'ffb-element-col-' + value );
				};

				var changeColumnName = function( value ) {
					var $header = $element.find('.ffb-header-name:first');
					$header.html( value + ' / 12');
					$header.attr('title', value + ' / 12' );
				};

				var currentValue = getColumnValue();
				changeColumnAppearance( currentValue );


				$element.find('.action-column-smaller:first').off('click').click(function() {
					var currentValue = parseInt(getColumnValue());

					if( currentValue == 1 ) {
						return false;
					}

					/* this animation is probably annoying?

					 // Animate In

					 $element.addClass('ffb-element-column-anim-width');
					 $element.one('transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd', function(){
					 $element.removeClass('ffb-element-column-anim-width');
					 });

					 */

					currentValue--;
					changeColumnAppearance( currentValue );
				});

				$element.find('.action-column-bigger:first').off('click').click(function() {
					var currentValue = parseInt(getColumnValue());

					if( currentValue == 12 ) {
						return false;
					}

					/* this animation is probably annoying?

					 // Animate In

					 $element.addClass('ffb-element-column-anim-width');
					 $element.one('transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd', function(){
					 $element.removeClass('ffb-element-column-anim-width');
					 });

					 */

					currentValue++;
					changeColumnAppearance( currentValue );
				});

			}
		</script data-type="ffscript">
		<?php
	}
}