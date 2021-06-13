<?php 
class WCRA_RolePerAmountRule
{
	public function __construct()
	{
	}
	
	public function get_rules()
	{
		add_filter('acf/settings/current_language',  array(&$this, 'cl_acf_set_language'), 100);
		$return_value = null;
	
		//if(isset($option_name) && $option_name == 'scheduling_rules')
		{
			$all_data = array();
			if( have_rows('wcra_rules', 'option') )
				while ( have_rows('wcra_rules', 'option') ) 
				{
					the_row();
					$rule = array();
					$rule['dates'] = array();
					$rule['rule_name_id'] = get_sub_field('wcra_rule_name_id', 'option'); //Check if value exists: if( $value )
					$rule['unique_id'] = get_sub_field('wcra_unique_id', 'option'); 
					$rule['role_restriction'] = get_sub_field('wcra_role_restriction', 'option'); 
					$rule['products_restriction'] = get_sub_field('wcra_products_restriction', 'option') ; 
					$rule['categories_restriction'] = get_sub_field('wcra_categories_restriction', 'option'); 
					$rule['amount'] = get_sub_field('wcra_amount', 'option'); 
					
					$rule['max_amount'] = get_sub_field('wcra_max_amount', 'option'); 
					$rule['max_amount'] = $rule['max_amount'] ? $rule['max_amount'] : 0; 
					
					$rule['roles_to_assign'] = get_sub_field('wcra_roles_to_assign', 'option'); //check if null
					$rule['remove_old_roles_before_assign_the_new_ones'] = get_sub_field('wcra_remove_old_roles_before_assign_the_new_ones', 'option'); //no || yes || selected_ones
					$rule['which_roles_have_to_be_removed'] = get_sub_field('wcra_which_roles_have_to_be_removed', 'option');  //<---
					
					$rule['roles_removal'] = get_sub_field('wcra_roles_removal', 'option'); 
					$rule['which_roles_have_to_be_removed_after_expiration'] = get_sub_field('wcra_which_roles_have_to_be_removed_after_expiration', 'option'); //<---
				
				
					$rule['time_period_type'] = get_sub_field('wcra_time_period_type'); 
					$rule['time_period_type'] = $rule['time_period_type'] ? $rule['time_period_type'] : 'fixed'; 
					
					$rule['time_amount'] = get_sub_field('wcra_time_amount'); 
					$rule['time_amount'] = $rule['time_amount'] ? $rule['time_amount'] : 1; 
					
					$rule['time_type'] = get_sub_field('wcra_time_type'); 
					$rule['time_type'] = $rule['time_type'] ? $rule['time_type'] : 'minutes'; 
					
					$rule['notification_email_subject'] = get_sub_field('wcra_notification_email_subject'); 
					$rule['notification_email_subject'] = $rule['notification_email_subject'] ? $rule['notification_email_subject']  : ""; 
					
					$rule['email_notification'] = get_sub_field('wcra_email_notification'); 
					$rule['email_notification'] = $rule['email_notification'] ? $rule['email_notification']  : 'no'; 
					
					$rule['notification_email_text'] = get_sub_field('wcra_notification_email_text'); 
					$rule['notification_email_text'] = $rule['notification_email_text'] ? $rule['notification_email_text']  : ''; 
					
					$rule['selected_strategy'] = get_sub_field('wcra_selected_strategy', 'option'); //all || except
					$rule['selected_strategy'] = $rule['selected_strategy'] ? $rule['selected_strategy'] : 'all'; 
					
					$rule['categories_children'] = get_sub_field('wcra_categories_children', 'option'); //selected_only || all_children
					$rule['categories_children'] = $rule['categories_children'] ? $rule['categories_children'] : 'selected_only' ; 
					/* format 
					 ["roles_to_assign"]=>
						array(2) {
						  [0]=>
						  string(16) "customer_premium"
						  [1]=>
						  string(0) ""
						}
						*/
					//consistency
					$rule['products_restriction'] = $rule['products_restriction'] ? $rule['products_restriction'] : false;
					$rule['categories_restriction'] = $rule['categories_restriction'] ? $rule['categories_restriction'] : false;
					$rule['roles_removal'] = $rule['roles_removal'] ? $rule['roles_removal'] : 'no';
					$rule['remove_old_roles_before_assign_the_new_ones'] = $rule['remove_old_roles_before_assign_the_new_ones'] ? $rule['remove_old_roles_before_assign_the_new_ones'] : 'no';
					
					
					$rule['which_roles_have_to_be_removed'] = $rule['remove_old_roles_before_assign_the_new_ones'] != 'selected_ones' || !isset($rule['which_roles_have_to_be_removed']) || empty($rule['which_roles_have_to_be_removed']) || $rule['which_roles_have_to_be_removed'][0] == "" ? false : $rule['which_roles_have_to_be_removed'];
					if($rule['which_roles_have_to_be_removed'] != false)
					{
						foreach($rule['which_roles_have_to_be_removed'] as $key => $role)
							if($role == "")
								unset($rule['which_roles_have_to_be_removed'][$key]);
					}
					$rule['which_roles_have_to_be_removed_after_expiration'] = $rule['roles_removal'] != 'selected_ones' || !isset($rule['which_roles_have_to_be_removed_after_expiration']) || empty($rule['which_roles_have_to_be_removed_after_expiration']) || $rule['which_roles_have_to_be_removed_after_expiration'][0] == "" ? false : $rule['which_roles_have_to_be_removed_after_expiration'];
					if($rule['which_roles_have_to_be_removed_after_expiration'] != false)
					{
						foreach($rule['which_roles_have_to_be_removed_after_expiration'] as $key => $role)
							if($role == "")
								unset($rule['which_roles_have_to_be_removed_after_expiration'][$key]);
					}
					$rule['role_restriction'] = !isset($rule['role_restriction']) || empty($rule['role_restriction']) || $rule['role_restriction'][0] == "" ? false : $rule['role_restriction'];
					if($rule['role_restriction'] != false)
					{
						foreach($rule['role_restriction'] as $key => $role)
							if($role == "")
								unset($rule['role_restriction'][$key]);
					}
					$rule['roles_to_assign'] = !isset($rule['roles_to_assign']) || empty($rule['roles_to_assign']) || $rule['roles_to_assign'][0] == "" ? false : $rule['roles_to_assign'];
					if($rule['roles_to_assign'] != false)
					{
						foreach($rule['roles_to_assign'] as $key => $role)
							if($role == "")
								unset($rule['roles_to_assign'][$key]);
					}
					//end consistency
					
					$rule['dates'] = array();
					if( have_rows('wcra_dates', 'option') )
						while ( have_rows('wcra_dates', 'option') ) 
						{
							the_row();
							$temp = array('starting_date' => get_sub_field('wcra_starting_date'), //Format Y/m/d
										  'start_hour' => get_sub_field('wcra_start_hour'),
										  'start_minute' => get_sub_field('wcra_start_minute'),
										  'ending_date' => get_sub_field('wcra_ending_date'),
										  'end_hour' => get_sub_field('wcra_end_hour'),
										  'end_minute' => get_sub_field('wcra_end_minute'),
										  'compute_role_assignment_during_different_period' => get_sub_field('wcra_compute_role_assignment_during_different_period'),
										  'effective_starting_date' => get_sub_field('wcra_effective_starting_date'),
										  'effective_start_hour' => get_sub_field('wcra_effective_start_hour'),
										  'effective_start_minute' => get_sub_field('wcra_effective_start_minute'),
										  'effective_ending_date' => get_sub_field('wcra_effective_ending_date'),
										  'effective_end_hour' => get_sub_field('wcra_effective_end_hour'),
										  'effective_end_minute' => get_sub_field('wcra_effective_end_minute')
										  );
													  
							//consistency
							$temp['start_hour'] = $temp['start_hour'] ? $temp['start_hour'] : 0;
							$temp['start_hour'] = intval($temp['start_hour']) < 10 ? "0".intval($temp['start_hour']) : $temp['start_hour'];
							
							$temp['start_minute'] =$temp['start_minute']  ? $temp['start_minute'] : 0;
							$temp['start_minute'] = intval($temp['start_minute']) < 10 ? "0".intval($temp['start_minute']) : $temp['start_minute'];
							
							$temp['start_time'] = $temp['start_hour'].":".$temp['start_minute'];
							
							$temp['end_hour'] = $temp['end_hour'] ? $temp['end_hour'] : 23 ;
							$temp['end_hour'] = intval($temp['end_hour']) < 10 ? "0".intval($temp['end_hour']) : $temp['end_hour'];
							
							$temp['end_minute'] = $temp['end_minute'] ? $temp['end_minute'] : 59;
							$temp['end_minute'] = intval($temp['end_minute']) < 10 ? "0".intval($temp['end_minute']) : $temp['end_minute'];
							
							$temp['end_time'] = $temp['end_hour'].":".$temp['end_minute'];
							//
							$temp['effective_starting_date'] =  $temp['effective_starting_date'] ? $temp['effective_starting_date'] : $temp['starting_date'];
							$temp['effective_ending_date'] =  $temp['effective_ending_date'] ? $temp['effective_ending_date'] : $temp['ending_date'];
							
							$temp['effective_start_hour'] =  $temp['effective_start_hour'] ? $temp['effective_start_hour'] : 0;
							$temp['effective_start_hour'] =$temp['effective_start_hour'] < 10 ? "0".$temp['effective_start_hour'] : $temp['effective_start_hour'];
							
							$temp['effective_start_minute'] =  $temp['effective_start_minute'] ? $temp['effective_start_minute'] : 0;
							$temp['effective_start_minute'] =$temp['effective_start_minute'] < 10 ? "0".$temp['effective_start_minute'] : $temp['effective_start_minute'];
							
							$temp['effective_start_time'] = $temp['effective_start_hour'].":".$temp['effective_start_minute'];
							
							$temp['effective_end_hour'] = $temp['effective_end_hour'] ? $temp['effective_end_hour'] : 23 ;
							$temp['effective_end_hour'] = $temp['effective_end_hour'] < 10 ? "0".$temp['effective_end_hour'] : $temp['effective_end_hour'];
							
							$temp['effective_end_minute'] = $temp['effective_end_minute'] ? $temp['effective_end_minute'] : 59;
							$temp['effective_end_minute'] = $temp['effective_end_minute'] < 10 ? "0".$temp['effective_end_minute'] : $temp['effective_end_minute'];
							
							$temp['effective_end_time'] = $temp['effective_end_hour'].":".$temp['effective_end_minute'];

							//To grant retro-compability
							$temp['compute_role_assignment_during_different_period'] = $temp['compute_role_assignment_during_different_period'] ? $temp['compute_role_assignment_during_different_period'] : 'no';
							
							
							$rule['dates'][] = $temp;
						} 
					$all_data[] = $rule;
				}
				$return_value = $all_data;
		}
		/* else
		{	
			$return_value =  get_field($option_name, 'option');
			$return_value = isset($return_value) ? $return_value : $default_value;
		} */
		remove_filter('acf/settings/current_language', array(&$this,'cl_acf_set_language'), 100);
		return  $return_value;
	}
	public function cl_acf_set_language() 
	{
	  return acf_get_setting('default_language');
	}
}
?>