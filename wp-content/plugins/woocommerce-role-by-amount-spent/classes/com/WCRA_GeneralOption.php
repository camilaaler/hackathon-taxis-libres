<?php 
class WCRA_GeneralOption
{
	public function __construct() 
	{
	}
	public function cl_acf_set_language() 
	{
	  return acf_get_setting('default_language');
	}
	public function get_time_offset()
	{
		/* add_filter('acf/settings/current_language',  array(&$this, 'cl_acf_set_language'), 100);
		$return_value = get_field('wcra_time_offset', 'option') ;
		$return_value = $return_value ?  $return_value : 0;
		remove_filter('acf/settings/current_language', array(&$this,'cl_acf_set_language'), 100); */
		$wordpres_offset = get_option('gmt_offset') * 60;
		$server_offset = date('Z')/60;
		$return_value = $server_offset == 0 || $server_offset == $wordpres_offset ? $wordpres_offset : $wordpres_offset - $server_offset; 
		return  $return_value;
	}
	public function get_scheduled_time_span()
	{
		add_filter('acf/settings/current_language',  array(&$this, 'cl_acf_set_language'), 100);
		$is_active = get_field('wcra_automatic_role_recomputation', 'option') ;
		$is_active = $is_active ?  $is_active : 'no';
		$time_span = array();
		if($is_active == 'yes')
		{
			$time_span['time_quantity'] = get_field('wcra_time_quantity', 'option') ;
			$time_span['time_quantity'] = $time_span['time_quantity'] ?  $time_span['time_quantity'] : 1;
			
			$time_span['time_span'] = get_field('wcra_time_span', 'option'); //hour || day 
			$time_span['time_span'] = $time_span['time_span'] ?  $time_span['time_span'] : 'hour'; //hour || day 
			
			$time_span['start_hour'] = get_field('wcra_start_hour', 'option');
			$time_span['start_hour'] = $time_span['start_hour']  ? $time_span['start_hour']  : 23;
			
			$time_span['start_minute'] = get_field('wcra_start_minute', 'option');
			$time_span['start_minute'] = $time_span['start_minute'] ?  $time_span['start_minute'] : 59;
		}			
		remove_filter('acf/settings/current_language', array(&$this,'cl_acf_set_language'), 100);
		return $time_span;
	}
	public function get_order_statues_to_exclude()
	{
		add_filter('acf/settings/current_language',  array(&$this, 'cl_acf_set_language'), 100);
		$exclude_statuses = get_field('wcra_exclude_orders_by_status', 'option');
		$exclude_statuses =$exclude_statuses ?  $exclude_statuses : 'yes';
		
		$statuses = get_field('wcra_order_status_to_exclude', 'option');
		$statuses = $statuses ?  $statuses : array('cancelled', 'refunded', 'failed','pending');
		$statuses = $exclude_statuses == 'yes' ? $statuses : array('none');
		
		remove_filter('acf/settings/current_language', array(&$this,'cl_acf_set_language'), 100);
		return $statuses;
	}
	public function get_my_account_page_options()
	{
		$options = array();
		add_filter('acf/settings/current_language',  array(&$this, 'cl_acf_set_language'), 100);
		
		$options['display_current_roles_list'] = get_field('wcra_my_account_page_display_current_roles_list', 'option');
		$options['display_current_roles_list'] = $options['display_current_roles_list'] == null || $options['display_current_roles_list'] == 'yes' ? true : false;
		
		$options['display_next_roles_list'] = get_field('wcra_my_account_page_display_next_roles_list', 'option');
		$options['display_next_roles_list'] = $options['display_next_roles_list'] == null || $options['display_next_roles_list'] == 'yes' ? true : false;
		
		$options['roles_to_exclude_from_role_list'] = get_field('wcra_my_account_page_roles_to_exclude_from_role_list', 'option');
		foreach((array)$options['roles_to_exclude_from_role_list'] as $key => $value)
			{
				if($value == "")
					unset($options['roles_to_exclude_from_role_list'][$key]);
			}
		$options['roles_to_exclude_from_role_list'] = $options['roles_to_exclude_from_role_list'] != null ? $options['roles_to_exclude_from_role_list'] : array();
		
		remove_filter('acf/settings/current_language', array(&$this,'cl_acf_set_language'), 100);
		return $options;
	}
}
?>