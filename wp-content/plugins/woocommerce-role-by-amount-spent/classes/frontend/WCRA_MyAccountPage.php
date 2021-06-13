<?php 
class WCRA_MyAccountPage
{
	public function __construct()
	{
		add_action('woocommerce_account_content', array(&$this, 'render_additional_content'), 20);
		//add_action('woocommerce_account_page_endpoint', array(&$this, 'render_additional_content'), 10 );
	}
	public function render_additional_content()
	{
		global $wp, $wcra_general_option_model, $wcra_text_helper, $wcra_shortcodes;
		$is_dashboard = false;
		 foreach($wp->query_vars as $endpoint => $value)
		{
			if($endpoint == 'page')
				$is_dashboard = true;
		}
		if(!$is_dashboard)
			return; 
		
		$options = $wcra_general_option_model->get_my_account_page_options();
		$texts = $wcra_text_helper->get_my_account_page_texts();
		$roles_to_exclude = !empty($options['roles_to_exclude_from_role_list']) ? implode(",",$options['roles_to_exclude_from_role_list']) : null;
		//wcra_var_dump($wcra_general_option_model->get_my_account_page_options());
		//wcra_var_dump($roles_to_exclude);
		
		wp_enqueue_style('wcra-shortcode-current-roles-list',  WCRA_PLUGIN_PATH.'/css/frontend-my-account-current-roles.css');
		wp_enqueue_style('wcra-shortcode-next-roles-list',  WCRA_PLUGIN_PATH.'/css/frontend-my-account-next-roles-list.css');
		
		if($options['display_current_roles_list'])
		{
			echo $wcra_shortcodes->show_current_roles(array('css'=>false, 'roles_to_not_show' => $roles_to_exclude, 'is_my_account' => true));
		}
		if($options['display_next_roles_list'])
		{
			echo $wcra_shortcodes->show_next_roles_list(array('css'=>false,'is_my_account' => true));
		}
	}
}
?>