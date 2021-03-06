<?php

function ark_get_font_selectors_uglify_selectors($selectors){
	$selectors = str_replace( array("\n","\r"), '', $selectors );
	return $selectors;
}

function ark_get_font_selectors($font){
	switch ($font) {
		case 'code': return 'code, kbd, pre, samp';
		case 'custom-font-1': return '.custom-font-1';
		case 'custom-font-2': return '.custom-font-2';
		case 'custom-font-3': return '.custom-font-3';
		case 'custom-font-4': return '.custom-font-4';
		case 'custom-font-5': return '.custom-font-5';
		case 'custom-font-6': return '.custom-font-6';
		case 'custom-font-7': return '.custom-font-7';
		case 'custom-font-8': return '.custom-font-8';
		case 'body': return ark_get_font_selectors_uglify_selectors('body,
p,
.ff-richtext,
li,
li a,
a,
h1, h2, h3, h4, h5, h6 ,

.progress-box-v1 .progress-title,
.progress-box-v2 .progress-title,
.team-v5-progress-box .progress-title,
.pricing-list-v1 .pricing-list-v1-header-title,
.team-v3 .progress-box .progress-title,

.rating-container .caption > .label,

.theme-portfolio .cbp-l-filters-alignRight,
.theme-portfolio .cbp-l-filters-alignLeft,
.theme-portfolio .cbp-l-filters-alignCenter,

.theme-portfolio .cbp-filter-item,

.theme-portfolio .cbp-l-loadMore-button .cbp-l-loadMore-link,
.theme-portfolio .cbp-l-loadMore-button .cbp-l-loadMore-button-link,
.theme-portfolio .cbp-l-loadMore-text .cbp-l-loadMore-link,
.theme-portfolio .cbp-l-loadMore-text .cbp-l-loadMore-button-link,

.theme-portfolio-v2 .cbp-l-filters-alignRight .cbp-filter-item,

.theme-portfolio-v3 .cbp-l-filters-button .cbp-filter-item,

.zeus .tp-bullet-title');



		case 'body-alt': return ark_get_font_selectors_uglify_selectors('.blog-classic .blog-classic-label,

.blog-classic .blog-classic-subtitle,

.blog-grid .blog-grid-title-el,

.blog-grid .blog-grid-title-el .blog-grid-title-link,

.blog-grid .blog-grid-supplemental-title,
.op-b-blog .blog-grid-supplemental-title,

.blog-grid .blog-grid-supplemental-category,
.blog-grid-supplemental .blog-grid-supplemental-title a,

.blog-teaser .blog-teaser-category .blog-teaser-category-title,
.blog-teaser .blog-teaser-category .blog-teaser-category-title a,
.news-v8 .news-v8-category a,

.news-v1 .news-v1-heading .news-v1-heading-title > a,

.news-v1 .news-v1-quote:before,

.news-v2 .news-v2-subtitle,
.news-v2 .news-v2-subtitle a,

.ff-news-v3-meta-data,
.ff-news-v3-meta-data a,

.news-v3 .news-v3-content .news-v3-subtitle,

.news-v6 .news-v6-subtitle,

.news-v7 .news-v7-subtitle,

.news-v8 .news-v8-category,

.blog-simple-slider .op-b-blog-title,
.blog-simple-slider .op-b-blog-title a,

.heading-v1 .heading-v1-title,
.heading-v1 .heading-v1-title p,
.testimonials-v7 .testimonials-v7-title .sign,
.team-v3 .team-v3-member-position,

.heading-v1 .heading-v1-subtitle,
.heading-v1 .heading-v1-subtitle p,

.heading-v2 .heading-v2-text,
.heading-v2 .heading-v2-text p,

.heading-v3 .heading-v3-text,
.heading-v3 .heading-v3-text p,

.heading-v4 .heading-v4-subtitle,
.heading-v4 .heading-v4-subtitle p,

.newsletter-v2 .newsletter-v2-title span.sign,

.quote-socials-v1 .quote-socials-v1-quote,
.quote-socials-v1 .quote-socials-v1-quote p,

.sliding-bg .sliding-bg-title,

.timeline-v4 .timeline-v4-subtitle, .timeline-v4 .timeline-v4-subtitle a,

.counters-v2 .counters-v2-subtitle,

.icon-box-v2 .icon-box-v2-body-subtitle,

.i-banner-v1 .i-banner-v1-heading .i-banner-v1-member-position,

.i-banner-v1 .i-banner-v1-quote,

.i-banner-v3 .i-banner-v3-subtitle,

.newsletter-v2 .newsletter-v2-title:before,

.piechart-v1 .piechart-v1-body .piechart-v1-body-subtitle,

.pricing-list-v1 .pricing-list-v1-body .pricing-list-v1-header-subtitle,

.pricing-list-v2 .pricing-list-v2-header-title,

.pricing-list-v3 .pricing-list-v3-text,

.promo-block-v2 .promo-block-v2-text,
.promo-block-v2 .promo-block-v2-text p,

.promo-block-v3 .promo-block-v3-subtitle,

.services-v1 .services-v1-subtitle,

.services-v10 .services-v10-no,

.services-v11 .services-v11-subtitle,

.slider-block-v1 .slider-block-v1-subtitle,

.team-v3 .team-v3-header .team-v3-member-position,

.team-v4 .team-v4-content .team-v4-member-position,

.testimonials-v1 .testimonials-v1-author-position,

.testimonials-v3 .testimonials-v3-subtitle:before,
.testimonials-v3 .testimonials-v3-subtitle span.sign,

.testimonials-v3 .testimonials-v3-author,

.testimonials-v5 .testimonials-v5-quote-text,
.testimonials-v5 .testimonials-v5-quote-text p,

.testimonials-v6 .testimonials-v6-element .testimonials-v6-position,

.testimonials-v6 .testimonials-v6-quote-text,
.testimonials-v6 .testimonials-v6-quote-text p,

.testimonials-v7 .testimonials-v7-title:before,

.testimonials-v7 .testimonials-v7-author,

.testimonials-v7-title-span,

.footer .footer-testimonials .footer-testimonials-quote:before,

.animated-headline-v1 .animated-headline-v1-subtitle,
.news-v3 .news-v3-content .news-v3-subtitle,
.news-v3 .news-v3-content .news-v3-subtitle a,

.theme-ci-v1 .theme-ci-v1-item .theme-ci-v1-title');
	}
	return '';
}