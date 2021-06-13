<?php 
class WCRA_RolePerProductRule
{
	public function __construct()
	{
	}
	public function get_rules()
	{
		add_filter('acf/settings/current_language',  array(&$this, 'cl_acf_set_language'), 100);
		$all_data = array();
		if( have_rows('wcra_products_rules', 'option') )
			while ( have_rows('wcra_products_rules', 'option') ) 
			{
				the_row();
				$rule = array();
				$rule['product_roles_to_assign'] = get_sub_field('wcra_product_roles_to_assign', 'option'); 
				
				$rule['skip_rule_application'] = get_sub_field('wcra_skip_rule_application', 'option'); 
				$rule['skip_rule_application'] = $rule['skip_rule_application'] ? $rule['skip_rule_application'] : false;
				
				$rule['role_restriction'] = get_sub_field('wcra_product_roles_restriction', 'option'); //check if null
				
				$rule['notification_email_subject'] = get_sub_field('wcra_product_roles_notification_email_subject'); 
				$rule['notification_email_subject'] = $rule['notification_email_subject'] ? $rule['notification_email_subject']  : ""; 
				
				$rule['email_notification'] = get_sub_field('wcra_product_roles_email_notification'); 
				$rule['email_notification'] = $rule['email_notification'] ? $rule['email_notification']  : false; 
				
				$rule['notification_email_text'] = get_sub_field('wcra_product_roles_notification_email_text'); 
				$rule['notification_email_text'] = $rule['notification_email_text'] ? $rule['notification_email_text']  : ''; 				
				
					
				$rule['selected_products'] = get_sub_field('wcr_selected_products', 'option'); 
				$rule['selected_products'] = $rule['selected_products'] ? $rule['selected_products'] : array();
				
				$rule['selected_categories'] = get_sub_field('wcra_selected_categories', 'option'); 
				$rule['selected_categories'] = $rule['selected_categories'] ? $rule['selected_categories'] : array(); 
				
				$rule['products_relation'] = get_sub_field('wcra_products_relation', 'option'); 
				$rule['products_relation'] = $rule['products_relation'] ? $rule['products_relation'] : 'or';  // or || and
				
				$rule['quantity_policy'] = get_sub_field('wcra_quantity_policy', 'option'); 
				$rule['quantity_policy'] = $rule['quantity_policy'] ? $rule['quantity_policy'] : 'ignore';  // ignore || number_of_distinct_product 
				
				$rule['quantity_value'] = get_sub_field('wcra_quantity_value', 'option'); 
				$rule['quantity_value'] = $rule['quantity_value'] ? $rule['quantity_value'] : 1;  
				
				$rule['expiring_date_type'] = get_sub_field('wcra_expiring_date_type', 'option'); 
				$rule['expiring_fixed_date'] = get_sub_field('wcra_expiring_fixed_date', 'option'); 
				
				$rule['expiring_fixed_hour'] = get_sub_field('wcra_expiring_hour', 'option'); 
				$rule['expiring_fixed_hour'] = $rule['expiring_fixed_hour']  ? $rule['expiring_fixed_hour']  : 23; 
				
				$rule['expiring_fixed_minute'] = get_sub_field('wcra_expiring_minute', 'option');
				$rule['expiring_fixed_minute'] = $rule['expiring_fixed_minute'] ? $rule['expiring_fixed_minute'] : 59 ;
				
				$rule['expiring_date_value'] = get_sub_field('wcra_expiring_date_value', 'option');  
				$rule['expiring_time_type'] = get_sub_field('wcra_expiring_time_type', 'option'); 
				$rule['repurchase_strategy'] = 'yes'; //get_sub_field('wcra_repurchase_strategy', 'option'); 
				$rule['purchase_restriction_by_products'] = get_sub_field('wcra_purchase_restriction_by_products', 'option'); 
				
				$rule['product_remove_old_roles_before_assign_the_new_ones'] = get_sub_field('wcra_product_remove_old_roles_before_assign_the_new_ones', 'option'); 
				$rule['product_remove_old_roles_before_assign_the_new_ones'] = $rule['product_remove_old_roles_before_assign_the_new_ones'] ? $rule['product_remove_old_roles_before_assign_the_new_ones'] : 'no'; //no || yes || selected_ones
				$rule['roles_to_assign_after_expiring_date'] = get_sub_field('wcra_roles_to_assign_after_expiring_date', 'option'); 
				
				$rule['remove_all_roles_before_assign_expring_date_roles'] = get_sub_field('wcra_remove_all_roles_before_assign_expring_date_roles', 'option'); //no || yes || selected_ones
				$rule['product_which_roles_have_to_be_removed'] = get_sub_field('wcra_product_which_roles_have_to_be_removed', 'option');  //<---
				$rule['product_which_roles_have_to_be_removed_before_assign_expiring_date'] = get_sub_field('wcra_product_which_roles_have_to_be_removed_before_assign_expiring_date', 'option');  //<---
				
				//Consistency checks
				$rule['expiring_fixed_hour'] = intval($rule['expiring_fixed_hour']) < 10 ? "0".intval($rule['expiring_fixed_hour'])  : $rule['expiring_fixed_hour'];
				$rule['expiring_fixed_minute'] = intval($rule['expiring_fixed_minute']) < 10 ? "0".intval($rule['expiring_fixed_minute']) : $rule['expiring_fixed_minute'];
				$rule['product_roles_to_assign'] = !isset($rule['product_roles_to_assign']) || empty($rule['product_roles_to_assign']) || $rule['product_roles_to_assign'][0] == "" ? false : $rule['product_roles_to_assign'];
				if($rule['product_roles_to_assign'] != false)
				{
					foreach($rule['product_roles_to_assign'] as $key => $role)
						if($role == "")
							unset($rule['product_roles_to_assign'][$key]);
				}
				$rule['remove_all_roles_before_assign_expring_date_roles'] =  $rule['remove_all_roles_before_assign_expring_date_roles'] ? $rule['remove_all_roles_before_assign_expring_date_roles'] : "no";
				$rule['roles_to_assign_after_expiring_date'] = $rule['expiring_date_type'] == 'none' || !isset($rule['roles_to_assign_after_expiring_date']) || empty($rule['roles_to_assign_after_expiring_date']) || $rule['roles_to_assign_after_expiring_date'][0] == "" ? false : $rule['roles_to_assign_after_expiring_date'];
				if($rule['roles_to_assign_after_expiring_date'] != false)
				{
					foreach($rule['roles_to_assign_after_expiring_date'] as $key => $role)
						if($role == "")
							unset($rule['roles_to_assign_after_expiring_date'][$key]);
				}				
				$rule['product_which_roles_have_to_be_removed'] = $rule['product_remove_old_roles_before_assign_the_new_ones'] != 'selected_ones' || !isset($rule['product_which_roles_have_to_be_removed']) || empty($rule['product_which_roles_have_to_be_removed']) || $rule['product_which_roles_have_to_be_removed'][0] == "" ? false : $rule['product_which_roles_have_to_be_removed'];
				if($rule['product_which_roles_have_to_be_removed'] != false)
				{
					foreach($rule['product_which_roles_have_to_be_removed'] as $key => $role)
						if($role == "")
							unset($rule['product_which_roles_have_to_be_removed'][$key]);
				}
				
				$rule['product_which_roles_have_to_be_removed_before_assign_expiring_date'] = $rule['remove_all_roles_before_assign_expring_date_roles'] != 'selected_ones' || !isset($rule['product_which_roles_have_to_be_removed_before_assign_expiring_date']) || empty($rule['product_which_roles_have_to_be_removed_before_assign_expiring_date']) || $rule['product_which_roles_have_to_be_removed_before_assign_expiring_date'][0] == "" ? false : $rule['product_which_roles_have_to_be_removed_before_assign_expiring_date'];
				if($rule['product_which_roles_have_to_be_removed_before_assign_expiring_date'] != false)
				{
					foreach($rule['product_which_roles_have_to_be_removed_before_assign_expiring_date'] as $key => $role)
						if($role == "")
							unset($rule['product_which_roles_have_to_be_removed_before_assign_expiring_date'][$key]);
				}
				$rule['role_restriction'] = !isset($rule['role_restriction']) || empty($rule['role_restriction']) || $rule['role_restriction'][0] == "" ? false : $rule['role_restriction'];
				if($rule['role_restriction'] != false)
				{
					foreach($rule['role_restriction'] as $key => $role)
						if($role == "")
							unset($rule['role_restriction'][$key]);
				}
				
				$all_data[] = $rule;
			}
		remove_filter('acf/settings/current_language', array(&$this,'cl_acf_set_language'), 100);
		return  $all_data;
	}
	public function cl_acf_set_language() 
	{
	  return acf_get_setting('default_language');
	}
}
?>