<?php 
class WCRA_TextConfiguratorPage
{
	public function __construct()
	{
		$this->init_options_menu();
	}
	function init_options_menu()
	{
		if( function_exists('acf_add_options_page') ) 
		{
			/*acf_add_options_page(array(
				'page_title' 	=> 'Menu name',
				'menu_title'	=> 'Menu name',
				'menu_slug' 	=> 'wcuf-option-menu',
				'capability'	=> 'edit_posts',
				'icon_url'      => 'dashicons-upload',
				'redirect'		=> false
			));*/
			
			 acf_add_options_sub_page(array(
				'page_title' 	=> 'Role-O-Matic Texts',
				'menu_title'	=> 'Role-O-Matic Texts',
				'parent_slug'	=> 'woocommerce-role-by-amount-spent',
			));
			
		}
	}
}
?>