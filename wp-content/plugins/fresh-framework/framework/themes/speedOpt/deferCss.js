/**
 * Created by thomas on 09/04/2018.
 */
(function($){

    var defaultCssJSON = '{"animationDelay":"0s","animationDirection":"normal","animationDuration":"0s","animationFillMode":"none","animationIterationCount":"1","animationName":"none","animationPlayState":"running","animationTimingFunction":"ease","backgroundAttachment":"scroll","backgroundBlendMode":"normal","backgroundClip":"border-box","backgroundColor":"rgba(0, 0, 0, 0)","backgroundImage":"none","backgroundOrigin":"padding-box","backgroundPosition":"0% 0%","backgroundRepeat":"repeat","backgroundSize":"auto","borderBottomColor":"rgb(0, 0, 0)","borderBottomLeftRadius":"0px","borderBottomRightRadius":"0px","borderBottomStyle":"none","borderBottomWidth":"0px","borderCollapse":"separate","borderImageOutset":"0px","borderImageRepeat":"stretch","borderImageSlice":"100%","borderImageSource":"none","borderImageWidth":"1","borderLeftColor":"rgb(0, 0, 0)","borderLeftStyle":"none","borderLeftWidth":"0px","borderRightColor":"rgb(0, 0, 0)","borderRightStyle":"none","borderRightWidth":"0px","borderTopColor":"rgb(0, 0, 0)","borderTopLeftRadius":"0px","borderTopRightRadius":"0px","borderTopStyle":"none","borderTopWidth":"0px","bottom":"auto","boxShadow":"none","boxSizing":"content-box","breakAfter":"auto","breakBefore":"auto","breakInside":"auto","captionSide":"top","clear":"none","clip":"auto","color":"rgb(0, 0, 0)","content":"","cursor":"auto","direction":"ltr","display":"block","emptyCells":"show","float":"none","fontFamily":"Times","fontKerning":"auto","fontSize":"16px","fontStretch":"100%","fontStyle":"normal","fontVariant":"normal","fontVariantLigatures":"normal","fontVariantCaps":"normal","fontVariantNumeric":"normal","fontVariantEastAsian":"normal","fontWeight":"400","height":"0px","imageRendering":"auto","isolation":"auto","justifyItems":"normal","justifySelf":"auto","left":"auto","letterSpacing":"normal","lineHeight":"normal","listStyleImage":"none","listStylePosition":"outside","listStyleType":"disc","marginBottom":"0px","marginLeft":"0px","marginRight":"0px","marginTop":"0px","maxHeight":"none","maxWidth":"none","minHeight":"0px","minWidth":"0px","mixBlendMode":"normal","objectFit":"fill","objectPosition":"50% 50%","offsetDistance":"0px","offsetPath":"none","offsetRotate":"auto 0deg","opacity":"1","orphans":"2","outlineColor":"rgb(0, 0, 0)","outlineOffset":"0px","outlineStyle":"none","outlineWidth":"0px","overflowAnchor":"auto","overflowWrap":"normal","overflowX":"visible","overflowY":"visible","paddingBottom":"0px","paddingLeft":"0px","paddingRight":"0px","paddingTop":"0px","pointerEvents":"auto","position":"static","resize":"none","right":"auto","scrollBehavior":"auto","speak":"normal","tableLayout":"auto","tabSize":"8","textAlign":"start","textAlignLast":"auto","textDecoration":"none solid rgb(0, 0, 0)","textDecorationLine":"none","textDecorationStyle":"solid","textDecorationColor":"rgb(0, 0, 0)","textDecorationSkipInk":"auto","textUnderlinePosition":"auto","textIndent":"0px","textRendering":"auto","textShadow":"none","textSizeAdjust":"auto","textOverflow":"clip","textTransform":"none","top":"auto","touchAction":"auto","transitionDelay":"0s","transitionDuration":"0s","transitionProperty":"all","transitionTimingFunction":"ease","unicodeBidi":"normal","verticalAlign":"baseline","visibility":"visible","whiteSpace":"normal","widows":"2","width":"1431px","willChange":"auto","wordBreak":"normal","wordSpacing":"0px","wordWrap":"normal","zIndex":"auto","zoom":"1","WebkitAppearance":"none","backfaceVisibility":"visible","WebkitBorderHorizontalSpacing":"0px","WebkitBorderImage":"none","WebkitBorderVerticalSpacing":"0px","WebkitBoxAlign":"stretch","WebkitBoxDecorationBreak":"slice","WebkitBoxDirection":"normal","WebkitBoxFlex":"0","WebkitBoxFlexGroup":"1","WebkitBoxLines":"single","WebkitBoxOrdinalGroup":"1","WebkitBoxOrient":"horizontal","WebkitBoxPack":"start","WebkitBoxReflect":"none","columnCount":"auto","columnGap":"normal","columnRuleColor":"rgb(0, 0, 0)","columnRuleStyle":"none","columnRuleWidth":"0px","columnSpan":"none","columnWidth":"auto","alignContent":"normal","alignItems":"normal","alignSelf":"auto","flexBasis":"auto","flexGrow":"0","flexShrink":"1","flexDirection":"row","flexWrap":"nowrap","justifyContent":"normal","WebkitFontSmoothing":"auto","gridAutoColumns":"auto","gridAutoFlow":"row","gridAutoRows":"auto","gridColumnEnd":"auto","gridColumnStart":"auto","gridTemplateAreas":"none","gridTemplateColumns":"none","gridTemplateRows":"none","gridRowEnd":"auto","gridRowStart":"auto","gridColumnGap":"0px","gridRowGap":"0px","WebkitHighlight":"none","hyphens":"manual","WebkitHyphenateCharacter":"auto","WebkitLineBreak":"auto","WebkitLineClamp":"none","WebkitLocale":"auto","WebkitMarginBeforeCollapse":"collapse","WebkitMarginAfterCollapse":"collapse","WebkitMaskBoxImage":"none","WebkitMaskBoxImageOutset":"0px","WebkitMaskBoxImageRepeat":"stretch","WebkitMaskBoxImageSlice":"0 fill","WebkitMaskBoxImageSource":"none","WebkitMaskBoxImageWidth":"auto","WebkitMaskClip":"border-box","WebkitMaskComposite":"source-over","WebkitMaskImage":"none","WebkitMaskOrigin":"border-box","WebkitMaskPosition":"0% 0%","WebkitMaskRepeat":"repeat","WebkitMaskSize":"auto","order":"0","perspective":"none","perspectiveOrigin":"715.5px 0px","WebkitPrintColorAdjust":"economy","WebkitRtlOrdering":"logical","shapeOutside":"none","shapeImageThreshold":"0","shapeMargin":"0px","WebkitTapHighlightColor":"rgba(0, 0, 0, 0.4)","WebkitTextCombine":"none","WebkitTextDecorationsInEffect":"none","WebkitTextEmphasisColor":"rgb(0, 0, 0)","WebkitTextEmphasisPosition":"over right","WebkitTextEmphasisStyle":"none","WebkitTextFillColor":"rgb(0, 0, 0)","WebkitTextOrientation":"vertical-right","WebkitTextSecurity":"none","WebkitTextStrokeColor":"rgb(0, 0, 0)","WebkitTextStrokeWidth":"0px","transform":"none","transformOrigin":"715.5px 0px","transformStyle":"flat","WebkitUserDrag":"auto","WebkitUserModify":"read-only","userSelect":"auto","WebkitWritingMode":"horizontal-tb","WebkitAppRegion":"no-drag","bufferedRendering":"auto","clipPath":"none","clipRule":"nonzero","mask":"none","filter":"none","floodColor":"rgb(0, 0, 0)","floodOpacity":"1","lightingColor":"rgb(255, 255, 255)","stopColor":"rgb(0, 0, 0)","stopOpacity":"1","colorInterpolation":"sRGB","colorInterpolationFilters":"linearRGB","colorRendering":"auto","fill":"rgb(0, 0, 0)","fillOpacity":"1","fillRule":"nonzero","markerEnd":"none","markerMid":"none","markerStart":"none","maskType":"luminance","shapeRendering":"auto","stroke":"none","strokeDasharray":"none","strokeDashoffset":"0px","strokeLinecap":"butt","strokeLinejoin":"miter","strokeMiterlimit":"4","strokeOpacity":"1","strokeWidth":"1px","alignmentBaseline":"auto","baselineShift":"0px","dominantBaseline":"auto","textAnchor":"start","writingMode":"horizontal-tb","vectorEffect":"none","paintOrder":"fill stroke markers","d":"none","cx":"0px","cy":"0px","x":"0px","y":"0px","r":"0px","rx":"auto","ry":"auto","caretColor":"rgb(0, 0, 0)","lineBreak":"auto"}';

    var defaultCSS = JSON.parse( defaultCssJSON );

    function getNotDefaultCss( newStyle ) {

        var nonDefault = [];

        for( var i in newStyle ) {
            var newValue = newStyle[i];
            var oldValue = defaultCSS[i];

            if( newValue != oldValue ) {
                nonDefault[ i ] = newValue;
            }
        }

        return nonDefault;

    }


    $.fn.getStyleObject = function(){
        var dom = this.get(0);
        var style;
        var returns = {};
        if(window.getComputedStyle){
            var camelize = function(a,b){
                return b.toUpperCase();
            };
            style = window.getComputedStyle(dom, null);
            for(var i = 0, l = style.length; i < l; i++){
                var prop = style[i];
                var camel = prop.replace(/\-([a-z])/g, camelize);
                var val = style.getPropertyValue(prop);
                returns[camel] = val;
            };
            return returns;
        };
        if(style = dom.currentStyle){
            for(var prop in style){
                returns[prop] = style[prop];
            };
            return returns;
        };
        return this.css();
    }

    var nameToStyle = function( name ) {

        var newName = '';

        for( var i in name ) {

            var char = name[i];

            if( char == char.toUpperCase() ) {
                newName = newName + '-';

            }
            newName = newName + char.toLowerCase();

        }

        return newName;
    };

    var getSelector = function ($item ) {

        var selectors = [];

        $item.parents().each(function(){

            if( $(this).prop('tagName') == 'BODY' ||  $(this).prop('tagName') == 'HTML' ) {
                return;
            }

            //console.log( $(this).prop('tagName') );

            var clases = $(this).attr('class');
            if( !clases) {
                return;
            }

            clases = clases.split(' ');


            for( var i in clases ) {
                var oneClass = clases[i];


                if( oneClass.indexOf('ffb-') != -1 ) {
                    selectors.push('.'+oneClass);
                }
            }

            //var oneClass = '.' + clases.split(' ').join('.');
            //selectors.push(oneClass);

        });

        selectors = selectors.reverse();

        selectors = selectors.join(' ');


        var itemClasses =$item.attr('class').split(' ');

        var newClass = [];

        for( var i in itemClasses ) {
            newClassxx = itemClasses[i];

            if( newClassxx.length > 0 ) {
                newClass.push( newClassxx);
            }
        }

        itemClasses = '.'+newClass.join('.')

        selectors = selectors + ' ' + itemClasses;

        return selectors;
    }


    $(document).ready(function(){

        $('.ffb-id-qgoa7in').each(function(){

            var offset = $(this).offset();

            if( offset.top > 1200) {
                return;
            }

            var stylesAll = $(this).getStyleObject();
            var styles = getNotDefaultCss( stylesAll );

            var css = [];
            for( var styleName in styles ) {
                var styleValue = styles[styleName];

                var styleNameClean = nameToStyle( styleName );

                css.push (styleNameClean + ': ' + styleValue + ';');
            }

            var allCss = css.join("\n");

            var selectors = getSelector( $(this));

            allCss = selectors + ' {' + "\n" + allCss + '}' + "\n";

            console.log( allCss );


            return false;
        });


        //var cssStyles = $('div').getStyleObject();

        //console.log( JSON.stringify(cssStyles) );

        //
        //var maxHeight = 1200;
        //
        //
        //var offset = $('.ffb-id-qgoa7in').offset();
        //
        //var cssStyles = $('.ffb-id-qgoa7in').getStyleObject();

        //var different= getNotDefaultCss( cssStyles );

        //console.log( different);

        //
        //console.log( cssStyles);

    });

})(jQuery);
