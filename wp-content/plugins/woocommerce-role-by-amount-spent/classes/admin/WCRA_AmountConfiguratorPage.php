<?php 
class WCRA_AmountConfiguratorPage
{
	public static $page_id = "role-o-matic_page_acf-options-amounts-configurator";
	public static $page_url_par = "acf-options-amounts-configurator";
	public function __construct()
	{
		//add_filter('acf/init', array(&$this,'init_options_menu'));
		$this->init_options_menu();
	}
	function init_options_menu()
	{
		if( function_exists('acf_add_options_page') ) 
		{
			acf_add_options_sub_page(array(
				'page_title' 	=> 'Amounts Configurator',
				'menu_title'	=> 'Amounts configurator',
				'parent_slug'	=> 'woocommerce-role-by-amount-spent',
			));
			
			
			
			add_action( 'current_screen', array(&$this, 'cl_set_global_options_pages') );
		}
	}
	
	function cl_set_global_options_pages($current_screen) 
	{
	  if(!is_admin())
		  return;
	  global $wcra_wpml_helper;
	  //wcra_var_dump($current_screen->id);
	  $page_ids = array(
		WCRA_AmountConfiguratorPage::$page_id
	  );
	  
	  if (in_array($current_screen->id, $page_ids)) 
	  {
		$wcra_wpml_helper->switch_to_default_language();
		add_filter('acf/settings/current_language', array(&$this, 'cl_acf_set_language'), 100);
	  }
	}
	

	function cl_acf_set_language() 
	{
	  return acf_get_setting('default_language');
	}
}
?>