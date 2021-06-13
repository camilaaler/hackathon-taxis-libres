<?php 
class WCRA_Order
{
	public function __construct()
	{
		//Order actions
		add_action('woocommerce_process_shop_order_meta', array( &$this, 'on_order_save' ), 5,2); //On save order
		//add_action('before_delete_post', array( &$this,'before_order_is_deleted'));
		add_action('woocommerce_order_status_changed', array( &$this, 'check_a_role_has_to_be_assigned_to_customer_after_order_status_change' )); //After checkout
		
		//WooCommerce subscription
		add_filter( 'wcs_renewal_order_created', array( &$this, 'check_a_role_has_to_be_assigned_to_customer_after_order_status_change' )); //passed parameters: $renewal_order, $subscription 
		add_action( 'wcs_resubscribe_order_created',  array( &$this, 'check_a_role_has_to_be_assigned_to_customer_after_order_status_change' )); //passed parameters: $resubscribe_order, $subscription
	}
	public static function get_date_created($order)
	{
		if(version_compare( WC_VERSION, '2.7', '<' ))
			return $order->order_date;
		
		$object = $order->get_date_created();
		return $object->date('Y-m-d H:i');
	}
	
	function check_a_role_has_to_be_assigned_to_customer_after_order_status_change($order_id_or_obj, $old_status = null, $new_status = null)
	{
		global $wcra_customer_model;
		$order = is_numeric($order_id_or_obj) ? wc_get_order($order_id_or_obj) : $order_id_or_obj;
		if( $order->get_user_id( ) != 0)
		{
			try{
				$wcra_customer_model->set_role_according_to_rules($order->get_user_id( ),$order);
			}catch(Exception $e){}
		}
		
		return $order_id_or_obj;
	}
	public function on_order_save( $order_id, $order)
	{
		//$order->order_date;
		$this->check_a_role_has_to_be_assigned_to_customer_after_order_status_change($order_id);
	}
	public function get_user_orders_by_rule($user_id, $rule, $product_ids_to_use_as_filter)
	{
		global $wcra_product_model;
		//$product_ids_to_use_as_filter = $wcra_product_model->get_products_ids_to_use_as_filter_on_order_selection( $rule['products_restriction'], $rule['categories_restriction'], $rule['categories_children']);
		$orders = array( );
		if(isset($product_ids_to_use_as_filter) && !empty($product_ids_to_use_as_filter))
			if($rule['selected_strategy'] != 'all')
			{
				$product_ids_to_use_as_filter = $wcra_product_model->get_complementry_ids($product_ids_to_use_as_filter);
			}
		
		if($rule['time_period_type'] == 'dynamic')
			$orders[] = $this->get_orders_by_date_and_user_id($user_id, $rule['time_period_type'], $rule, $product_ids_to_use_as_filter);
		else
			foreach((array)$rule['dates'] as $date)
			{
				/* wcra_var_dump("fixed"); */
				$orders[] = $this->get_orders_by_date_and_user_id($user_id, $rule['time_period_type'], $date, $product_ids_to_use_as_filter);
			}
		return $orders;
	}
	public function get_orders_by_date_and_user_id($user_id, $time_period_type, $date_rule, $products_restriction)
	{
		global $wpdb, $wcra_general_option_model;
		$minutes_offset = $wcra_general_option_model->get_time_offset();
		//$now = date("Y-m-d H:i", strtotime($minutes_offset.' minutes'));
		$now = current_time("Y-m-d H:i");
		
		$compute_role_assignment_during_different_period = 'no';
		if($time_period_type == 'fixed')
		{
			$compute_role_assignment_during_different_period = $date_rule['compute_role_assignment_during_different_period'];
			//if($date_rule['compute_role_assignment_during_different_period'] == 'no')
			{
				$start_date = $date_rule['starting_date'];
				$end_date = $date_rule['ending_date'];
				$star_time = $date_rule['start_time'];
				$end_time = $date_rule['end_time'];
			}
			/* else
			{
				$start_date = $date_rule['effective_starting_date'];
				$end_date = $date_rule['effective_ending_date'];
				$star_time = $date_rule['effective_start_time'];
				$end_time = $date_rule['effective_end_time'];
			} */
			$start_date = $start_date." ".$star_time;
			$end_date = $end_date." ".$end_time;
		}	
		else
		{
			$compute_role_assignment_during_different_period = 'no';
			$time_amount = $date_rule['time_amount'];
			$time_type = $date_rule['time_type'];
			
			/* $start_date = date("Y-m-d", strtotime("-".$time_amount.' '.$time_type));
			$end_date = date("Y-m-d");
			$star_time = date("H:i", strtotime("-".$time_amount.' '.$time_type));
			$end_time = date("H:i"); //"23:59"; */
			
			//new
			$start_date = current_time("Y-m-d");
			$end_date = current_time("Y-m-d");
			$star_time = current_time("H:i");
			$end_time = current_time("H:i"); 
			
			$end_date = $end_date." ".$end_time;
			
			$start_date = date_create($start_date." ".$star_time);
			date_sub($start_date, date_interval_create_from_date_string($time_amount.' '.$time_type));
			$start_date = date_format($start_date, 'Y-m-d H:i');
			//
			
			$date_rule['effective_starting_date'] = $start_date;
			$date_rule['effective_start_time'] = $star_time;
			$date_rule['effective_ending_date'] = $end_date;
			$date_rule['effective_end_time']  = $end_time;
			
			/* wcra_var_dump("***************************");
			wcra_var_dump($start_date."  -> ".$end_date);
			wcra_var_dump($now); 
			wcra_var_dump($star_time."  -> ".$end_time);
			wcra_var_dump("minutes: ".$minutes_offset);  
			wp_die();  */
			
		}		
		
		 
		/* $start_date = date_create($start_date." ".$star_time);
		if($minutes_offset <= 0)
			date_sub($start_date, date_interval_create_from_date_string(abs($minutes_offset).' minutes'));
		else
		
			date_add($start_date, date_interval_create_from_date_string(abs($minutes_offset).' minutes'));
		$start_date = date_format($start_date, 'Y-m-d H:i');  */
		
		
		/* $end_date = date_create($end_date." ".$end_time);
		if($minutes_offset <= 0)
			date_sub($end_date, date_interval_create_from_date_string(abs($minutes_offset).' minutes'));
		else
		
			date_add($end_date, date_interval_create_from_date_string(abs($minutes_offset).' minutes'));
		$end_date = date_format($end_date, 'Y-m-d H:i'); */
		
		
		
		/* wcra_var_dump("***************************");
		wcra_var_dump($start_date."  -> ".$end_date);
		wcra_var_dump($now); 
		wcra_var_dump($star_time."  -> ".$end_time);
		wcra_var_dump("minutes: ".$minutes_offset);  
		wcra_var_dump($date_rule['effective_starting_date']." ".$date_rule['effective_start_time']);  
		wcra_var_dump($date_rule['effective_ending_date']." ".$date_rule['effective_end_time']);  
		wcra_var_dump($date_rule['effective_starting_date']." ".$date_rule['effective_start_time']<= $now);  
		wcra_var_dump($date_rule['effective_ending_date']." ".$date_rule['effective_end_time'] >= $now);  
		wp_die();  */
		
		if( ($compute_role_assignment_during_different_period == 'no' && $start_date > $now) || 
			($compute_role_assignment_during_different_period == 'yes' && (( $date_rule['effective_starting_date']." ".$date_rule['effective_start_time'] <= $now ) ||
										  ( $now <= $date_rule['effective_ending_date']." ".$date_rule['effective_end_time'] )
									     )
		    )
		  )
			return -1;
		/* wcra_var_dump("survive"); */ 
		$additional_joins = "";
		$additional_where_conditions = "";
		$additional_select_conditions = "";
		if(isset($products_restriction) && !empty($products_restriction))
		{
			$additional_where_conditions .= " AND ( ";
			$additional_select_conditions .= ", SUM( order_product_id_filter_total.meta_value ) as filtered_product_total";
			$additional_joins .="  INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS order_product_id_filter ON order_product_id_filter.order_item_id = order_items.order_item_id ";
			$additional_joins .="  INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS order_product_id_filter_total ON order_product_id_filter_total.order_item_id = order_items.order_item_id ";
			$product_ids = array();
			$variation_ids = array();
			foreach($products_restriction as $product)
			{
				
				if($product['variation_id'] != 0)
				{
					$variation_ids[] = $product['variation_id'];
				}
				else
				{
					$product_ids[] = $product['product_id'];
				}
			}
			if(!empty($variation_ids))
				$additional_where_conditions .= " (order_product_id_filter.meta_key = '_variation_id' AND order_product_id_filter.meta_value IN(".implode(",",$variation_ids).")) ";
			if(!empty($variation_ids) && !empty($product_ids))
				$additional_where_conditions .= " OR ";
			if(!empty($product_ids))
				$additional_where_conditions .= " (order_product_id_filter.meta_key = '_product_id' AND order_product_id_filter.meta_value IN(".implode(",",$product_ids).") ) ";
			$additional_where_conditions .= " )";
			$additional_where_conditions .= " AND order_product_id_filter_total.meta_key = '_line_total'  AND order_product_id_filter_total.order_item_id = order_product_id_filter.order_item_id"; //_line_subtotal
		} 
		
		$wpdb->query('SET SQL_BIG_SELECTS=1');
		$query_addons = $this->get_orders_query_conditions_to_exclude_bad_orders(); 
		         //GROUP_CONCAT(order_product_id_filter.meta_value) as product_id,
		$query = "SELECT  SUM(order_itemmeta_total.meta_value) AS order_total, SUM(order_feemeta_total.meta_value) AS order_fees ".$additional_select_conditions. //, SUM(order_tax.meta_value) AS order_tax 
				  " FROM {$wpdb->posts} AS orders ".
				  //INNER JOIN {$wpdb->postmeta} AS order_total ON order_total.post_id = orders.ID 
				  //INNER JOIN {$wpdb->postmeta} AS order_tax ON order_tax.post_id = orders.ID 
				  "INNER JOIN {$wpdb->prefix}woocommerce_order_items AS order_items ON order_items.order_id = orders.ID ".
				  "INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS order_itemmeta_total ON order_itemmeta_total.order_item_id = order_items.order_item_id ".
				  "LEFT JOIN {$wpdb->prefix}woocommerce_order_items AS order_fees ON order_fees.order_id = orders.ID AND order_fees.order_item_type = 'fee' ".
				  "LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS order_feemeta_total ON order_feemeta_total.order_item_id = order_fees.order_item_id AND order_feemeta_total.meta_key = '_line_total' ".
				  "INNER JOIN {$wpdb->postmeta} AS customer_user_id ON customer_user_id.post_id = orders.ID ".$additional_joins." ".$query_addons['join']." 
				  WHERE orders.post_type = 'shop_order' ".
				  //"AND order_total.meta_key = '_order_total' ".
				  //" AND order_tax.meta_key = '_order_tax' ".
				  " AND order_items.order_item_type = 'line_item' ".
				  "AND order_itemmeta_total.meta_key = '_line_total' ". 
				  
				  "AND customer_user_id.meta_key = '_customer_user'
				  AND customer_user_id.meta_value = {$user_id} 
				  AND orders.post_date >= '{$start_date}' 
				  AND orders.post_date <= '{$end_date}'  ".$additional_where_conditions." ".$query_addons['where']; //{$start_date} {$star_time} ////// {$start_date} 00:00 [....] {$end_date} 23:59
		// wcra_var_dump($query); 
		$result =  $wpdb->get_results($query);
		//$result2 =  $wpdb->get_results("SELECT * FROM {$wpdb->posts} AS orders WHERE orders.post_type = 'shop_order' ");
		
		/* wcra_var_dump($result2);
		wcra_var_dump($query);
		wcra_var_dump($result);
		 wp_die();  */

		$result = !empty($result) && $result[0]->order_total != null ? $result[0] : -2;
		if(is_object($result))
		{
			/* wcra_var_dump($result->order_total);
			wcra_var_dump($result->order_fees); */
			$result->end_date = $end_date;
			$result->start_date = $start_date;
			$result->start_time = $star_time; //no need
			$result->end_time = $end_time; //no need
			$result->time_period_type = $time_period_type; 
			$result->order_total = isset($result->order_fees) ? $result->order_total + $result->order_fees : $result->order_total; 
		}
		/* result format: 
		object(stdClass)#655 (3) {
		  ["order_total"]=>
		  string(2) "23"
		  ["filtered_product_total"]=>
		  string(2) "23"
		  ["end_date"]=>
		  string(16) "2016-09-30 23:58"
		}
		*/
		return $result; 		
	}
	public function get_not_allowed_order_statuses($use_prefix = true)
	{
		global $wcra_general_option_model;
		$statuses = $wcra_general_option_model->get_order_statues_to_exclude();
		if(function_exists( 'wc_get_order_statuses' ) && $use_prefix)
		{
			$statuses_with_prefix = array();
			foreach($statuses as $status)
				$statuses_with_prefix[] = 'wc-'.$status;
			return $statuses_with_prefix;//array('wc-cancelled', 'wc-refunded', 'wc-failed', 'wc-pending');
		}
		
		return $statuses;//array('cancelled', 'refunded', 'failed','pending');
	}
	public function get_orders_query_conditions_to_exclude_bad_orders($join_type = 'INNER')
	{
		global $wpdb;
		$statuses = $this->get_order_statuses();
		$result = array();
		$result['join'] = "";
		$result['where'] = "";
		$result['version'] = $statuses['version'];
		if($statuses['version'] > 2.1)
		{
			/* 
			 'wc-pending'    => _x( 'Pending Payment', 'Order status', 'woocommerce' ),
			'wc-processing' => _x( 'Processing', 'Order status', 'woocommerce' ),
			'wc-on-hold'    => _x( 'On Hold', 'Order status', 'woocommerce' ),
			'wc-completed'  => _x( 'Completed', 'Order status', 'woocommerce' ),
			'wc-cancelled'  => _x( 'Cancelled', 'Order status', 'woocommerce' ),
			'wc-refunded'   => _x( 'Refunded', 'Order status', 'woocommerce' ),
			'wc-failed'     => _x( 'Failed', 'Order status', 'woocommerce' ),
			*/
			$result['statuses'] = $statuses['statuses'] = array_diff($statuses['statuses'], $this->get_not_allowed_order_statuses()/* array('wc-cancelled', 'wc-refunded', 'wc-failed', 'wc-pending') */);
			$result['where'] = " AND orders.post_status IN ('".implode( "','",$statuses['statuses'])."') ";
		}
		else 
		{
			$result['statuses'] = $statuses['statuses'] = array_diff($statuses['statuses'], $this->get_not_allowed_order_statuses() /* array('cancelled', 'refunded', 'failed','pending') */);
			$result['join'] = " {$join_type} JOIN {$wpdb->term_relationships} AS rel ON orders.ID=rel.object_id
							  {$join_type} JOIN {$wpdb->term_taxonomy} AS tax ON tax.term_taxonomy_id = rel.term_taxonomy_id
							  {$join_type} JOIN {$wpdb->terms} AS term ON term.term_id = tax.term_id ";
			$result['where'] .= " AND orders.post_status   = 'publish'
								 AND tax.taxonomy        = 'shop_order_status' 
								 AND term.slug           IN ( '" .implode( "','",$statuses['statuses']). "' )";
		}
		
		return $result;
	}
	public function get_order_statuses()
	{
		
		$result = array();
		$result['statuses'] = array();
		if(function_exists( 'wc_get_order_statuses' ))
		{
			
			$result['version'] = 2.2;
			//[slug] => name
			$temp  = wc_get_order_statuses();
			foreach($temp as $slug => $title)
					array_push($result['statuses'], $slug);
		}
		else
		{
			$args = array(
				'hide_empty'   => false, 
				'fields'            => 'id=>slug', 
			);
			$result['version'] = 2.1;
			
			$temp = get_terms('shop_order_status', $args);
			foreach($temp as $id => $slug)
					array_push($result['statuses'], $slug);
		}
		return $result;
	}
}
?>