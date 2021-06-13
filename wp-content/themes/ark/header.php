<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php do_action('ark_after_body_tag'); ?>
<?php ark_boxed_wrapper_start(); ?>
<?php wp_body_open(); ?>
<?php ark_navigation(); ?>