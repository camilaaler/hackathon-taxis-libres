<?php 
class WCRA_UserPage
{
	public function __construct()
	{
		add_action ( 'show_user_profile', array( &$this,'render_expiring_products_dates'), 99);
		add_action ( 'edit_user_profile', array( &$this,'render_expiring_products_dates'), 99 );
		add_action ( 'edit_user_profile_update', array( &$this,'update_purchase_date')); //user/admin is viewing other profile
		add_action ( 'personal_options_update', array( &$this,'update_purchase_date')); //user/admin is viewing other profile
	}
	public function render_expiring_products_dates( $user )
	{
		if(!is_admin())
			return;
		
		global $wcra_shortcodes, $wcra_wpml_helper,$wcra_customer_model, $wcra_product_model;
		$all_purchased_role_products = $wcra_customer_model->get_purchased_role_products_ids($user->ID);
		
		//echo $wcra_shortcodes->show_expiring_dates(array('user_id'=>$user->ID));
		echo '<h3>'.__( 'Purchased products & expiring dates', 'woocommerce-role-by-amount-spent' ).'</h3>';
		echo '<table class="form-table">';
		echo '<tbody>';
		foreach((array)$all_purchased_role_products as $all_purchased_role_product)
		{
			$error = false;
			try{
					//$product = new WC_Product($all_purchased_role_product['product_id']);
					$product = wc_get_product($all_purchased_role_product['product_id']);
				}catch(Exception $e){$error = true;}
			if(!isset($product) || is_bool($product))
				$error = true;
				
		/* 	if($error)
				continue; */
			echo '<tr>';
				
				$all_purchased_role_product['product_id'] = $wcra_wpml_helper->get_current_language_id($all_purchased_role_product['product_id']);
				$variation_title = $all_purchased_role_product['variation_id'] == 0 ? "" : " - ".__( 'Variation: ', 'woocommerce-role-by-amount-spent' ).$all_purchased_role_product['variation_id'];
				if(!$error)
					echo '<th><label class="wcra_product_title_label">'.$product->get_title().$variation_title.'</label></th>';
				else
					echo '<th><label class="wcra_product_title_label">N/A</label></th>';
				echo '<td>';
					echo '<span class="wcra_purchased_date_content"><strong>'.__( 'Purchased', 'woocommerce-role-by-amount-spent' ).':</strong> '.wcra_format_date($all_purchased_role_product['purchase_date'], 'default').'</span><br/>';
					$expiring_data = $wcra_product_model->get_expiring_date_role_product($all_purchased_role_product['product_id'], $all_purchased_role_product['variation_id'],$all_purchased_role_product['purchase_date'],null,'default');
					echo '<span class="wcra_expire_date_content"><strong>'.__( 'Expire date', 'woocommerce-role-by-amount-spent' ).':</strong> '.wcra_format_date($expiring_data, 'default').'</span><br/>';
					echo '<input type="checkbox" value="true" name="wcra_delete_purchase_date['.$all_purchased_role_product['meta_key'].']"> '.__('Delete','woocommerce-role-by-amount-spent').'</input>';
					echo '<input type="hidden" value="'.$user->ID.'" name="wcra_purchase_date_to_id['.$all_purchased_role_product['meta_key'].']"></input>';
				echo '</td>';
			echo '</tr>';
		}
		echo '</tbody>';
		echo '</table>';
	}
	public function update_purchase_date($user_id = 0)
	{
		if(!is_admin())
			return;
		global $wcra_customer_model;
		if(isset($_POST['wcra_delete_purchase_date']))
		{
			foreach($_POST['wcra_delete_purchase_date'] as $meta_key => $value)
			{
				/* wcra_var_dump($meta_key);
				wcra_var_dump($_POST['wcra_purchase_date_to_id'][$meta_key]); */
				$wcra_customer_model->remove_purchase_date_for_role_product($_POST['wcra_purchase_date_to_id'][$meta_key], $meta_key);
			}
		}
	}
	
}
?>