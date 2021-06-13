<?php 
class WCRA_Cart
{
	public function __construct()
	{
		//Cart actions
		add_action('woocommerce_add_to_cart_validation', array(&$this, 'cart_add_to_validation'), 10, 5);
		add_action('woocommerce_update_cart_validation', array(&$this, 'cart_update_validation'), 10, 4);

	}
	//Cart actions
	public function cart_add_to_validation( $original_result, $product_id, $quantity , $variation_id = 0, $variations = null )
	{
		global $wcra_product_model;
		$can_add = $wcra_product_model->role_item_can_be_purchased($product_id, $variation_id);
		
		if(!$can_add)
			wc_add_notice( __('Cannot add this product to the Cart.','woocommerce-role-by-amount-spent') ,'error');
		
		return $can_add;
	}
	public function cart_update_validation($original_result, $cart_item_key = 'none', $values = 0, $quantity = 0)
	{
	
		global $woocommerce, $wcra_product_model;
		$cart = $woocommerce->cart->cart_contents;
		foreach((array)$cart as $cart_item)
		{
			$temp_result = $wcra_product_model->role_item_can_be_purchased($cart_item['product_id'], $cart_item['variation_id']);
			if(!$temp_result)
			{
				wc_add_notice( sprintf(__('Please remove <strong>%s</strong> from the Cart, it cannot be purchased.','woocommerce-role-by-amount-spent'),$cart_item['data']->post->post_title) ,'error');
				$original_result = $temp_result;
			}
		}
		return $original_result;
	}
	//Checkout actions
	public function cart_validation_before_checkout() //Called by WCRA_CheckoutAndOrderHookManager
	{
		return $this->cart_update_validation(true);
	}
	//On Order delete
	public function update_customer_and_products_total_presasales_info($order_id) //Called by WCRA_CheckoutAndOrderHookManager
	{
	}
}
?>