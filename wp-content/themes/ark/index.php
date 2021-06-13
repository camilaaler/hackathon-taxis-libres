<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

get_header();

get_template_part('no-ff/fallbacks/blog');

get_footer();