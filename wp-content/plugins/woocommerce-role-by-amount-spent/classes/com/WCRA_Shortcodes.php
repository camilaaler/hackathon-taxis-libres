<?php 
class WCRA_Shortcodes
{
	public function __construct()
	{
		add_shortcode( 'wcra_show_expiring_dates', array(&$this, 'show_expiring_dates' ));
		add_shortcode( 'wcra_current_roles', array(&$this, 'show_current_roles' ));
		add_shortcode( 'wcra_next_roles_list', array(&$this, 'show_next_roles_list' ));
	}
	public function show_expiring_dates($atts)
	{
		 $parameters = shortcode_atts( array(
          'user_id' => get_current_user_id(),
			), $atts );
		/*	
		if(!isset($parameters['product_id']))
			return "";
		 */
		 $user_id = $parameters['user_id'];
		if($user_id == 0)
			return;
		
		global $wcra_customer_model,$wcra_wpml_helper,$wcra_product_model;
		//Foreach user product meta data, get product ids
		$all_purchased_role_products = $wcra_customer_model->get_purchased_role_products_ids($user_id);
		ob_start();
		foreach((array)$all_purchased_role_products as $all_purchased_role_product)
		{
			$all_purchased_role_product['product_id'] = $wcra_wpml_helper->get_current_language_id($all_purchased_role_product['product_id']);
			$expiring_data = $wcra_product_model->get_expiring_date_role_product($all_purchased_role_product['product_id'], $all_purchased_role_product['variation_id'],$all_purchased_role_product['purchase_date'],null,'default');
			$product = wc_get_product($all_purchased_role_product['product_id']);
			if(!isset($product) || $product == false)
				continue;
			echo '<strong class="wcra_product_role_title">'.$product->get_title().':</strong> <span class="wcra_product_role_date">'.$expiring_data.'</span><br/>';
			
		}
		return ob_get_clean();
	}
	public function show_current_roles($atts)
	{
		global $wcra_customer_model,  $wcra_text_helper;
		$parameters = shortcode_atts( array(
          'user_id' => get_current_user_id(),
          'css' => true,
		  'roles_to_not_show' => null,
		  'is_my_account' => false,
			), $atts );
		
		if((boolean)$parameters['css'])
			wp_enqueue_style('wcra-shortcode-show-current-roles',  WCRA_PLUGIN_PATH.'/css/frontend-shortcode-current-roles.css');
		
		/* $all_roles = wp_roles();
		$all_roles = $all_roles->roles;  //$all_roles[$role_code]['name'] */
		$role_code_to_role_name = $wcra_customer_model->role_code_to_role_name();
		$texts = $wcra_text_helper->get_my_account_page_texts();
		$user_id = $parameters['user_id'];
		$roles_to_not_show = isset($parameters['roles_to_not_show']) ? explode(",",$parameters['roles_to_not_show']) : array();
		$user = new WP_User($user_id);
		$roles_to_show = array(); //role codes
		
		foreach($user->roles as $role_code)
			if(isset($role_code_to_role_name[$role_code]) && !in_array($role_code, $roles_to_not_show))
				$roles_to_show[] = $role_code_to_role_name[$role_code];
		
		ob_start();
		//wcra_var_dump($all_roles['administrator']['name']);
		include WCRA_PLUGIN_ABS_PATH.'templates/current_roles.php';
		return ob_get_clean();
	}
	public function show_next_roles_list($atts)
	{
		global $wcra_customer_model,  $wcra_text_helper;
		
		$parameters = shortcode_atts( array(
          'user_id' => get_current_user_id(),
		   'css' => true,
		   'is_my_account' => false,
			), $atts );
			
		if((boolean)$parameters['css'])	
			wp_enqueue_style('wcra-shortcode-next-roles-list',  WCRA_PLUGIN_PATH.'/css/frontend-shortcode-next-roles-list.css');		
		
		$texts = $wcra_text_helper->get_my_account_page_texts();
		$rules_roles_and_amounts = $wcra_customer_model->get_next_roles_and_amounts_missing_to_them_by_user_id($parameters['user_id']);
		$role_code_to_role_name = $wcra_customer_model->role_code_to_role_name();
		ob_start();
		include WCRA_PLUGIN_ABS_PATH.'templates/next_roles_list.php';
		return ob_get_clean();		
	}
}
?>