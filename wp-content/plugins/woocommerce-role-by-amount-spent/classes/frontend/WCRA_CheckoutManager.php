<?php 
class WCRA_CheckoutManager
{
	public function __construct()
	{
		//Checkout actions
		add_action('woocommerce_checkout_process', array( &$this, 'cart_validation_before_checkout' )); //new
		//add_action('woocommerce_checkout_order_processed', array( &$this, 'check_a_role_has_to_be_assigned_to_customer' )); 
		add_action('woocommerce_thankyou', array( &$this, 'check_a_role_has_to_be_assigned_to_customer' )); 
		
	}	
	//Order actions
	function woocommerce_process_shop_order ( $order_id, $order = null ) 
	{
        $this->check_a_role_has_to_be_assigned_to_customer($order_id);
	}
	public function update_customer_and_products_total_presasales_info($order_id)
	{
		//remove user role and puchase date
	}
	//Checkout actions
	public function cart_validation_before_checkout()
	{
		global $wcra_cart_model;
		$wcra_cart_model->cart_validation_before_checkout();
	}
	function check_a_role_has_to_be_assigned_to_customer( $order_id)
	{
		global $wcra_customer_model;
		
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
		  return $order_id;
	  
		$order = wc_get_order($order_id);
		/* $automatic_conversion = WCCM_Options::get_option('automatic_conversion');
		$send_email = WCCM_Options::get_option('send_email_after_automatic_conversion');
		$send_email = isset($send_email) && $send_email != 'false' ? true : false; */
		 
		if( $order->get_user_id( ) != 0)
		{
			try{
				$wcra_customer_model->set_role_according_to_rules($order->get_user_id( ),$order);
			}catch(Exception $e){}
		}
		//wc_add_notice("test", 'error');
		//wp_die();
	}
}
?>