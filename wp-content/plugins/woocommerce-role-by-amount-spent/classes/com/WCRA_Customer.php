<?php 
class WCRA_Customer
{
	var $roles_computation_already_performed = false;
	public function __construct()
	{
		add_action('wp_ajax_wcra_get_customers_ids', array(&$this, 'get_ajax_customers_ids'));
		add_action('wp_ajax_wcra_recompute_user_role_by_index', array(&$this, 'get_ajax_recompute_user_role_by_index'));
		
		add_action('pre_get_posts', array( &$this,'perform_role_recomputation_on_user_site_access'));
	}
	public function get_user_data($user_id)
	{
		return get_userdata( $user_id );
	}
	public function get_user_meta($user_id, $meta_name)
	{
		return get_user_meta( $user_id, $meta_name, true );
	}
	public function get_ajax_customers_ids()
	{
		
		$result = $this->get_customers_ids();
		if(!empty($result))
			echo implode(",",$result);
		else
			echo 0;
		wp_die();
	}
	public function perform_role_recomputation_on_user_site_access($main_query)
	{
		if(is_admin() || $this->roles_computation_already_performed)
			return;
		
		global $wcra_role_per_product_model;
		$this->roles_computation_already_performed = true;
		$rules = $wcra_role_per_product_model->get_rules();
		$user = get_current_user_id() > 0 ? new WP_User( get_current_user_id()) : null;
		if(isset($user))
		{
			foreach((array)$rules as $rule)
				$this->update_user_role_by_product_role_rule($user, $rule, 0 ,true);	
		}
	}
	public function get_ajax_recompute_user_role_by_index()
	{
		$indexes = json_decode (stripslashes($_POST['indexes']));
		//test
		//$indexes = [16667,16669,15877,15869,15868];
		//$indexes = [16667,16669];
		echo $this->set_role_according_to_rules($indexes);
		
		wp_die();
	}
	public function recompute_all_user_role()
	{
		$ids = $this->get_customers_ids();
		if(is_array($ids) && !empty($ids))
			$this->set_role_according_to_rules($ids);
	}
	
	public function role_code_to_role_name()
	{
		$all_roles = wp_roles();
		$all_roles = $all_roles->roles;
		$role_code_to_role_name = array();
		
		foreach($all_roles as $role_code => $role)
			$role_code_to_role_name[$role_code] = $role['name'];
			
		return $role_code_to_role_name;
	}
	public function get_next_roles_and_amounts_missing_to_them_by_user_id($user_id)
	{
		global $wcra_order_model, $wcra_role_per_amount_model, $wcra_product_model;
		$rules = $wcra_role_per_amount_model->get_rules();
		$user = new WP_User($user_id);
		$result = array();
		if(!empty($rules))
			foreach($rules as $rule)
			{
				if(($rule['role_restriction'] == false || array_intersect($user->roles, $rule['role_restriction'])) && $rule['roles_to_assign'] != false)
				{
					$product_ids_to_use_as_filter = $wcra_product_model->get_products_ids_to_use_as_filter_on_order_selection( $rule['products_restriction'], $rule['categories_restriction'], $rule['categories_children']);
					$orders = $wcra_order_model->get_user_orders_by_rule($user_id, $rule, $product_ids_to_use_as_filter);
					
					if(!empty($orders))
						foreach($orders as $current_orders_to_process)
							if(!is_numeric($current_orders_to_process) || $current_orders_to_process != -2 && $current_orders_to_process != -1)
							{
								if($product_ids_to_use_as_filter != false)
									$current_orders_to_process->order_total = $current_orders_to_process->filtered_product_total;
								
								if($current_orders_to_process->order_total < $rule['amount'])
									$result[] = array('total_spent' => $current_orders_to_process->order_total, 
													  'amount_to_achieve' => $rule['amount'],
													  'end_date' => $current_orders_to_process->end_date,
													  'start_date' => $current_orders_to_process->start_date,
													 /*  'start_time' => $current_orders_to_process->start_time,
													  'end_time' => $current_orders_to_process->end_time, */
													  'time_period_type' => $current_orders_to_process->time_period_type,
													  'role_to_assign' => $rule['roles_to_assign']);
							}
				}
			}
		return $result;
	}
	public function set_role_according_to_rules($user_ids, $order = null)
	{
		global $wcra_order_model, $wcra_role_per_amount_model, $wcra_product_model, 
				$wcra_role_per_product_model, $wcra_general_option_model, $wcra_wpml_helper, $wcra_email_helper;
		$rules = $wcra_role_per_amount_model->get_rules();
		$updated = 0;
		$user_ids = !is_array($user_ids) && isset($user_ids) ? array(0 => $user_ids) : $user_ids;
		
		//Roles by (purchased) products
		$product_role_rules = $wcra_role_per_product_model->get_rules();
		$order_items = isset($order) && !in_array( $order->get_status(), $wcra_order_model->get_not_allowed_order_statuses(false)) ? $order->get_items() : array();
		//$current_logged_user = get_current_user_id() > 0 ? new WP_User( get_current_user_id()) : null;
		$number_of_applied_rules = 0;
	
		//Roles by product - Part 1: set purchase date according purchased items
		if(!empty($order_items) && $order->get_user_id( ) != 0)
		{
			//update user meta with product expiring date (if order is set)
			foreach((array)$product_role_rules as $product_role_rule)
			{
				$user = new WP_User($order->get_user_id( ));
				if($product_role_rule['role_restriction'] != false && count(array_intersect($user->roles , $product_role_rule['role_restriction'])) == 0)
					continue;
					
				//Purchase date update & role(s) update
				$number_of_distinct_products = 0;
				$rule_quantity_value = $product_role_rule['quantity_value'];
				$products_relation = $product_role_rule['products_relation'];
				$skip_rule_application = $product_role_rule['skip_rule_application'];
				$elegible_products = array();
				$number_of_selected_categories = count($product_role_rule['selected_categories']);
				
				foreach((array)$order_items as $order_item)
				{
					//WPML 
					$translation_product_id = $wcra_wpml_helper->get_original_id($order_item['product_id']); 
					$translation_variation_id = $wcra_wpml_helper->get_original_id($order_item['variation_id'], 'product_variation') ;
					$order_item['product_id'] = $translation_product_id ? $translation_product_id : $order_item['product_id'];
					$order_item['variation_id'] = $translation_variation_id ? $translation_variation_id : $order_item['variation_id'];
					
					
					if(in_array($order_item['product_id'], $product_role_rule['selected_products']) || 
						in_array($order_item['variation_id'], $product_role_rule['selected_products']) ||
						$wcra_product_model->product_belongs_to_categories($order_item['product_id'], $product_role_rule['selected_categories'])
					  )
					{
						/*
							$this->set_purchase_date_for_role_product($order_item['product_id'],0, $order->get_user_id( ), WCRA_Order::get_date_created($order));
							if($order_item['variation_id'] != 0)
								$this->set_purchase_date_for_role_product($order_item['product_id'],$order_item['variation_id'], $order->get_user_id( ), WCRA_Order::get_date_created($order));
						 */
						
						$elegible_products[] = array('product_id' => $order_item['product_id'], 'variation_id' => $order_item['variation_id'] != 0 ? $order_item['variation_id'] : 0);
						$number_of_distinct_products++;
					}
					
				}
				
				//purchase date is setted only for elegible products according the selected quantity policy
				if( ($products_relation == 'or' && ($product_role_rule['quantity_policy'] == 'ignore' || $number_of_distinct_products >= $rule_quantity_value)) ||
				    ($products_relation == 'and' && ($number_of_distinct_products >= $number_of_selected_categories)))
				{
					if($skip_rule_application && $number_of_applied_rules > 0)
						$elegible_products = array();
					else 
						$number_of_applied_rules++;
					
					foreach($elegible_products as $elegible_product)
					{
						$this->set_purchase_date_for_role_product($elegible_product['product_id'],$elegible_product['variation_id'], $order->get_user_id( ), WCRA_Order::get_date_created($order));
					}
				}
				
				//Remove role(s) for expired role products
				/* if(isset($current_logged_user))
				{
					$this->update_user_role_by_product_role_rule($current_logged_user, $rule);	
				} */
			}
		}
		
		if(!empty($user_ids) && isset($user_ids))
		{
			foreach($user_ids as $user_id)
			{
				$email_texts_per_user_id = array();
				$user = new WP_User($user_id);
				$temp_update = 0;
				
				//Roles by amount
				if(!empty($rules))
					foreach($rules as $rule)
					{
						/* wcra_var_dump($rule['rule_name_id'] ); */
						if(($rule['role_restriction'] == false || array_intersect($user->roles, $rule['role_restriction'])) && $rule['roles_to_assign'] != false)
						{
							$product_ids_to_use_as_filter = $wcra_product_model->get_products_ids_to_use_as_filter_on_order_selection( $rule['products_restriction'], $rule['categories_restriction'], $rule['categories_children']);
							$orders = $wcra_order_model->get_user_orders_by_rule($user_id, $rule, $product_ids_to_use_as_filter);
							
							/* wcra_var_dump($orders); 
							wcra_var_dump($rule['amount']); 
							wcra_var_dump($rule['max_amount']); 
							wp_die(); */
							if(!empty($orders))
								foreach($orders as $current_orders_to_process)
								{
									$has_spent_the_right_amount = false;
									if(!is_numeric($current_orders_to_process) || $current_orders_to_process != -2 && $current_orders_to_process != -1)
									{
										//check amount
										if($product_ids_to_use_as_filter != false)
										{
											$current_orders_to_process->order_total = $current_orders_to_process->filtered_product_total;
										}
										/* wcra_var_dump(is_numeric($product_ids_to_use_as_filter));
										wcra_var_dump($current_orders_to_process->order_total);
										wcra_var_dump($rule['amount']);  */
										if($current_orders_to_process->order_total >= $rule['amount'] && ($rule['max_amount'] == 0 || $current_orders_to_process->order_total <= $rule['max_amount']))
										{
											//assign new role
											$has_spent_the_right_amount = true;
											$has_any_role_removed = false;
											if($rule['remove_old_roles_before_assign_the_new_ones'] == 'yes')
											{
												$email_texts_per_user_id[$user_id] = array(); //If a previous role is remove, no email is sent (IF it was expected to be sent) 
												$this->remove_all_roles($user);
												$has_any_role_removed  = true;
											}
											else if( $rule['remove_old_roles_before_assign_the_new_ones']  == 'selected_ones')
											{
												$email_texts_per_user_id[$user_id] = array(); 
												//Remove seleted roles
												$this->remove_user_roles($user, $rule['which_roles_have_to_be_removed']);
												$has_any_role_removed  = true;
											}
											
											foreach($rule['roles_to_assign'] as $role_to_assign)
											{
												if(!in_array($role_to_assign,$user->roles))
												{
													if($temp_update == 0)
														$temp_update++;
													$user->add_role($role_to_assign);
													
													if($rule['email_notification'] && $rule['email_notification'] != 'no')
													{
														if(!isset($email_texts_per_user_id[$user_id]))
															$email_texts_per_user_id[$user_id] = array();
														$email_texts_per_user_id[$user_id][] = array('subject' => $rule['notification_email_subject'], 'body' => $rule['notification_email_text'], 'unique_id' => $rule['unique_id']);
													}
												}
												elseif($has_any_role_removed)
												{
													if($temp_update == 0)
														$temp_update++;
												}
											}
										}
									} 
									/* wcra_var_dump($has_spent_the_right_amount);  */
									if( (!is_numeric($current_orders_to_process) || (is_numeric($current_orders_to_process) && $current_orders_to_process != -1)) && !$has_spent_the_right_amount && $rule['roles_removal'] != 'no') //role removal
									{
										if($rule['roles_removal'] == 'yes')
											$temp_update = $this->remove_user_roles($user, $rule['roles_to_assign'],  $temp_update);
										else //selected_ones
										{
											//Remove seleted roles
											$temp_update = $this->remove_user_roles($user, $rule['which_roles_have_to_be_removed_after_expiration'],  $temp_update );
											//$temp_update += $temp_update == 0 && $removal_result ? 1 : 0;
										}
									}
								}
						}
						// Remove only if the rule matches
						else if(($rule['roles_removal'] == 'yes' || $rule['roles_removal'] == 'selected_ones') && array_intersect($user->roles, $rule['roles_to_assign'])) 
						{
							if($rule['roles_removal'] == 'yes')
							{
								$temp_update = $this->remove_user_roles($user, $rule['roles_to_assign'],  $temp_update);
							}
							else //selected_ones
							{
								//Remove seleted roles
								$temp_update = $this->remove_user_roles($user, $rule['which_roles_have_to_be_removed_after_expiration'],  $temp_update );
								//$temp_update += $temp_update == 0 && $removal_result ? 1 : 0;
							}
						}
					}
				$updated += $temp_update;
			
				//Remove and update role(s) - Role by product - Part 2 : according the product associated to rule, user role are updated
				$current_user = new WP_User($user_id);
				$number_of_applied_rules = 0;
				foreach((array)$product_role_rules as $product_role_rule_id => $product_role_rule)
				{
					if($current_user && isset($order))
					{
						
						if($product_role_rule['role_restriction'] != false && count(array_intersect($current_user->roles, $product_role_rule['role_restriction'])) == 0)
							continue;
					
						$updated = false;
						$order_items = isset($order)  && !in_array( $order->get_status(), $wcra_order_model->get_not_allowed_order_statuses(false)) ? $order->get_items() : array();
						
						$number_of_distinct_products = 0;
						$rule_quantity_value = $product_role_rule['quantity_value'];
						$skip_rule_application = $product_role_rule['skip_rule_application'];
						$number_of_selected_categories = count($product_role_rule['selected_categories']);
						$rule_can_be_applied = false;
						
						foreach((array)$order_items as $order_item)
						{
							//WPML 
							$translation_product_id = $wcra_wpml_helper->get_original_id($order_item['product_id']); 
							$translation_variation_id = $wcra_wpml_helper->get_original_id($order_item['variation_id'], 'product_variation') ;
							$order_item['product_id'] = $translation_product_id ? $translation_product_id : $order_item['product_id'];
							$order_item['variation_id'] = $translation_variation_id ? $translation_variation_id : $order_item['variation_id'];
							
							
							if(in_array($order_item['product_id'], $product_role_rule['selected_products']) || 
								in_array($order_item['variation_id'], $product_role_rule['selected_products']) ||
								$wcra_product_model->product_belongs_to_categories($order_item['product_id'], $product_role_rule['selected_categories']) )
							{
								
								/* if(!empty($product_role_rule['selected_categories']))
									$product_role_rule['selected_products'] = $wcra_product_model->merge_products_with_the_one_seleted_via_category($product_role_rule);
								
								//NOTE: product purchased (order id not null then) date have been updated in Part 1 (upper part of the method)
								$updated2 = $this->update_user_role_by_product_role_rule($current_user, $product_role_rule, $temp_update);
								$updated = $updated2 == true ? true : $updated; */
								
								//NOTE: needed to make the update_user_role_by_product_role_rule() work. In case product have been selected by category, the 'selected_products' is empty
								// to avoid this, products are manually added to that array.
								$current_product_id = $order_item['variation_id'] != 0 ? $order_item['variation_id'] : $order_item['product_id'];
								if (!in_array($current_product_id, $product_role_rule['selected_products']))
									$product_role_rule['selected_products'][] = $current_product_id;
								
								$rule_can_be_applied  = true;
								$number_of_distinct_products++;
							}
						}
						//purchase date is setted only for elegible products according the selected quantity policy
						/* wcra_var_debug_dump($product_role_rule['quantity_policy'] == 'ignore');
						wcra_var_debug_dump($number_of_distinct_products);
						wcra_var_debug_dump($rule_quantity_value);
						wcra_var_debug_dump($number_of_distinct_products >= $rule_quantity_value);
						wcra_var_debug_dump($product_role_rule['product_remove_old_roles_before_assign_the_new_ones']);
						wcra_var_debug_dump("************"); */
						
						/* if( $product_role_rule['quantity_policy'] == 'ignore' || $number_of_distinct_products >= $rule_quantity_value) */
						if( $rule_can_be_applied &&
							(($products_relation == 'or' && ($product_role_rule['quantity_policy'] == 'ignore' || $number_of_distinct_products >= $rule_quantity_value)) ||
							($products_relation == 'and' && ($number_of_distinct_products >= $number_of_selected_categories))))
						{
							if($skip_rule_application && $number_of_applied_rules > 0)
							{
								
							}
							else 
							{			
								//email sent only if there is at least one role not already owend by the user								
								if($product_role_rule['email_notification'] /* && $this->exists_at_least_one_role_not_assigned($current_user->roles, $product_role_rule['product_roles_to_assign']) */)
								{
									if(!isset($email_texts_per_user_id[$user_id]))
										$email_texts_per_user_id[$user_id] = array();
									$email_texts_per_user_id[$user_id][] = array('subject' => $product_role_rule['notification_email_subject'], 'body' => $product_role_rule['notification_email_text'], 'unique_id' => $product_role_rule_id);
								}
								
								$number_of_applied_rules++;
								//wcra_var_debug_dump("inside");
								//$wcra_product_model->merge_products_with_the_one_seleted_via_category($product_role_rule);
								//NOTE: product purchased (order id not null then) date have been updated in Part 1 (upper part of the method)
								$updated2 = $this->update_user_role_by_product_role_rule($current_user, $product_role_rule, $temp_update);
								$updated = $updated2 == true ? true : $updated2; 							
								
							}
						}					
					}
				}
				
				$this->set_default_role_if_necessary($user);
			}//end foreach $user_id
			
			//Notification
			if(!empty($email_texts_per_user_id))
					$wcra_email_helper->send_role_change_notification_to_user($email_texts_per_user_id);
		}
		
		return $updated;
	}
	public function exists_at_least_one_role_not_assigned($user_roles, $roles_to_assign)
	{
		foreach($roles_to_assign as $role_to_assign)
			if(!in_array($role_to_assign, $user_roles))
					return true;
		
		return false;
	}
	public function add_user_role_by_product_role_rule($user, $rule)
	{
	}
	public function update_user_role_by_product_role_rule($user, $rule, $temp_update = 0, $only_remove = false)
	{
		global $wcra_general_option_model, $wcra_product_model;
		$time_offest = $wcra_general_option_model->get_time_offset();
		//$now = date("Y-m-d H:i",strtotime($time_offest.' minutes'));
		$now = current_time("Y-m-d H:i");
		
		if(!empty($rule['selected_categories']))
			$rule['selected_products'] = $wcra_product_model->merge_products_with_the_one_seleted_via_category($rule);
		
		foreach($rule['selected_products'] as $temp_selected_product_id)
		{
			$post_type = get_post_type($temp_selected_product_id);
			$product_id =  $post_type == 'product' ? $temp_selected_product_id : wp_get_post_parent_id($temp_selected_product_id);
			$variation_id = $post_type == 'product' ? 0 : $temp_selected_product_id;
			
			$purchase_date = $this->get_purchase_date_for_role_product($product_id, $variation_id,$user->ID);
		    $expiring_date = $wcra_product_model->get_expiring_date_role_product($product_id, $variation_id,$purchase_date, $rule);
			
			
			//EXPIRED: remove role if expiring date has been past (date compare)
			//wcra_var_dump($user->ID." ".$purchase_date." ".$expiring_date." ".wcra_date_compare($now , $expiring_date));
			if( $purchase_date !== "false" && $purchase_date != "" && //If $purchase_date == "" it means that the user has not purchased the product
				(($rule['expiring_date_type'] == 'relative' && wcra_date_compare($now , $expiring_date) > 0 )   ||
				 ($rule['expiring_date_type'] == 'fixed'    && wcra_date_compare($now , $rule['expiring_fixed_date']." ".$rule['expiring_fixed_hour'].":".$rule['expiring_fixed_minute']) > 0 )
				)
			   )
				{
					$temp_update = $temp_update==0 ? 1 : $temp_update;
					if($rule['roles_to_assign_after_expiring_date'] != false)
					{
						if($rule['remove_all_roles_before_assign_expring_date_roles'] == 'yes')
						{
							$this->remove_all_roles($user);
						}
						else if($rule['remove_all_roles_before_assign_expring_date_roles'] == 'selected_ones')
						{
							//Remove seleted roles			
							$this->remove_user_roles($user, $rule['product_which_roles_have_to_be_removed_before_assign_expiring_date']);
						}
						
						foreach($rule['roles_to_assign_after_expiring_date'] as $product_roles_to_assign)
								$user->add_role($product_roles_to_assign);
					}
					$this->remove_user_roles($user, $rule['product_roles_to_assign'] );
					$this->set_default_role_if_necessary($user);
				}
			//add role if expiring date has still to come (date compare)	
			elseif( !$only_remove && 
					($rule['expiring_date_type'] == 'none' || 
						( $purchase_date !== "false" && $purchase_date != "" && //If $purchase_date == "" it means that the user has not purchased the product
							($rule['expiring_date_type'] == 'relative' && wcra_date_compare($now , $expiring_date) < 0)  ||
							($rule['expiring_date_type'] == 'fixed'         && wcra_date_compare($now , $rule['expiring_fixed_date']." ".$rule['expiring_fixed_hour'].":".$rule['expiring_fixed_minute']) < 0 )
						)
					)
				)
				{
					if($rule['product_remove_old_roles_before_assign_the_new_ones'] == 'yes')
					{
							$this->remove_all_roles($user);
					}
					else if($rule['product_remove_old_roles_before_assign_the_new_ones'] == 'selected_ones')
					{
						//Remove seleted roles
						$this->remove_user_roles($user, $rule['product_which_roles_have_to_be_removed']);
					}
										
					foreach($rule['product_roles_to_assign'] as $product_roles_to_assign)
					{
						$temp_update = $temp_update== 0 ? 1 : $temp_update;
						$user->add_role($product_roles_to_assign);
					}
					$this->set_default_role_if_necessary($user);
				}
			
		}
		return $temp_update;
	}
	public function get_current_user_roles()
	{
		$user_ID = get_current_user_id();
		if($user_ID > 0)
		{
			$user = new WP_User( $user_ID );
			return $user->roles;
		}
		return false;
	}
	public function get_purchase_date_for_role_product($product_id, $variation_id, $user_id = null)
	{
		global $wcra_general_option_model, $wcra_wpml_helper;
		//wpml
		$translated_product_id = $wcra_wpml_helper->get_original_id($product_id);
		$translated_variation_id = $variation_id != 0 ? $wcra_wpml_helper->get_original_id($variation_id, 'product_variation') : $variation_id;
		$product_id = $translated_product_id ? $translated_product_id : $product_id;
		$variation_id = $variation_id != 0 && $translated_variation_id ? $translated_variation_id : $variation_id;
		
		$user_ID = isset($user_id) ? $user_id : get_current_user_id();
		if($user_ID > 0)
		{
			return get_user_meta($user_ID, '_wcra_purchase_date_'.$product_id.'-'.$variation_id, true); //If empty it returns "" value;
		}
		return false;
	}
	public function set_purchase_date_for_role_product($product_id, $variation_id,  $user_id=null, $order_date = null)
	{
		global $wcra_general_option_model, $wcra_wpml_helper;
		$user_ID = isset($user_id) ? $user_id : get_current_user_id();
		$time_offest = $wcra_general_option_model->get_time_offset();
		//wpml: no need, passed id are already translated
		/* $product_id = $wcra_wpml_helper->get_original_id($product_id);
		$variation_id = $variation_id != 0 ? $wcra_wpml_helper->get_original_id($variation_id, 'product_variation') : $variation_id; */
		//$now = date("Y-m-d H:i",strtotime($time_offest.' minutes'));
		$now = current_time("Y-m-d H:i");
		$order_date = isset($order_date) ? $order_date : $now;
		
		//Prevent "old order" to modify current puchasing data.
		$old_purchasing_date = $this->get_purchase_date_for_role_product($product_id, $variation_id,  $user_ID );
		if($old_purchasing_date !== 'false' && $old_purchasing_date != "" && wcra_date_compare($order_date , $old_purchasing_date) < 0)
			return false; 
			 
		
		if($user_ID > 0)
		{
			return update_user_meta($user_ID, '_wcra_purchase_date_'.$product_id.'-'.$variation_id, $order_date );
		}
		return false;
	}
	public function get_purchased_role_products_ids($user_id)
	{
		global $wpdb;
		$products_data = array();
		$query = "SELECT meta_key, meta_value
				  FROM {$wpdb->usermeta}
				  WHERE user_id = {$user_id}
				  AND meta_key LIKE '_wcra_purchase_date_%' ";
		$results = $wpdb->get_results($query, ARRAY_A );
		$results = isset($results) ? $results : array();
		foreach($results as $result)
		{
			$full_meta_key = $result['meta_key'];
			$result['meta_key'] = str_replace('_wcra_purchase_date_', "",$result['meta_key']);
			$product_ids = explode("-",$result['meta_key']);
			$products_data[] = array('meta_key' => $full_meta_key, 'product_id' => $product_ids[0], 'variation_id' => $product_ids[1], 'purchase_date' => $result['meta_value']);
		}
		return $products_data;
	}
	public function remove_purchase_date_for_role_product($user_id, $meta)
	{
		delete_user_meta($user_id, $meta);
	}
	private function remove_all_roles($user)
	{
		foreach($user->roles as $role)
			$user->remove_role($role);
	}
	private function remove_user_roles($user, $roles_to_remove, $temp_update = 0)
	{
		if(!is_array($roles_to_remove))
			return $temp_update;
		
		foreach($roles_to_remove as $role_to_remove)
			if(in_array($role_to_remove, $user->roles))
			{
				if($temp_update == 0)
					$temp_update++;
				$user->remove_role($role_to_remove);
			}
			
		//to avoid user has no roles 	
		/* if(empty($user->roles))
			$user->add_role('customer'); */
		
		return $temp_update;
	}
	private function set_default_role_if_necessary($user)
	{
		if(empty($user->roles))
			$user->add_role('customer');
	}
	/* private function remove_specific_roles($user, $roles)
	{
		
		foreach($roles as $role_to_remove)
			if(in_array($role_to_remove, $user->roles))
			{
				$user->remove_role($role_to_assign);
			}
			
		if(empty($user->roles))
			$user->add_role('customer');
		
		return true;
	} */
	public function get_customers_ids()
	{
		global $wpdb;
		/* $result = count_users();
		return  $result['total_users']; */
		$ids = array();
		$query = "SELECT users.ID 
				FROM {$wpdb->users} as users";
				
		$result = $wpdb->get_results($query);
		if(isset($result))
			foreach((array)$result as $id)
				$ids[] = $id->ID;
		return $ids;
	}
}
?>