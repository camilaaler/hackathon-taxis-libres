<?php 
class WCRA_Text
{
	public function __construct()
	{
	}
	public function get_my_account_page_texts()
	{
		$bad_chars = array('"', "'");
		
		$all_data['role_list_title'] = get_field('wcra_role_list_title', 'option'); 		
		$all_data['role_list_title'] = $all_data['role_list_title'] != null ? $all_data['role_list_title'] : __("Role list","woocommerce-role-by-amount-spent"); 		
		
		$all_data['next_roles_list_title'] = get_field('wcra_next_roles_list_title', 'option'); 
		$all_data['next_roles_list_title'] = $all_data['next_roles_list_title'] != null ? $all_data['next_roles_list_title'] : __("Next roles","woocommerce-role-by-amount-spent"); 
		
		foreach($all_data as $key => $single_data)
				$all_data[$key] = str_replace($bad_chars, "", $single_data);
		
		return $all_data;
	}
}
?>