<?php 
add_action( 'wp_loaded', 'wrca_scheduled_role_assignment_computation_activation' );
add_action( 'wcra_role_assignment_check_event', 'wcra_perform_role_assignment_check' );

function wrca_scheduled_role_assignment_computation_activation() 
{
	global $wcra_general_option_model, $wcra_time_model;
	$scheduled_interval_span = $wcra_general_option_model->get_scheduled_time_span();
	$hour_offset = $wcra_general_option_model->get_time_offset();
	
	/* wcra_var_dump(wp_get_schedule('wcra_role_assignment_check_event'));
	wcra_var_dump(wp_next_scheduled('wcra_role_assignment_check_event'));  */
	//wp_clear_scheduled_hook( 'wcra_role_assignment_check_event' );
	if(!empty($scheduled_interval_span))
	{
		if ( !wp_next_scheduled( 'wcra_role_assignment_check_event' ) ) 
		{
			//$date = new DateTime(date("m/d/Y ".$scheduled_interval_span['start_hour'].":".$scheduled_interval_span['start_minute'],strtotime($hour_offset.' minutes'))); // format: MM/DD/YYYY
			$scheduled_interval_span['start_hour'] = $scheduled_interval_span['start_minute'] != "00"  ? ltrim($scheduled_interval_span['start_hour'], '0') :  $scheduled_interval_span['start_hour'];
			$scheduled_interval_span['start_minute'] = $scheduled_interval_span['start_minute'] != "00" ? ltrim($scheduled_interval_span['start_minute'], '0') :  $scheduled_interval_span['start_minute'];
			
			$hour = intval($scheduled_interval_span['start_hour']) < 10 & $scheduled_interval_span['start_minute'] != "00" ? "0".$scheduled_interval_span['start_hour'] : $scheduled_interval_span['start_hour'];
			$minute = intval($scheduled_interval_span['start_minute']) < 10 & $scheduled_interval_span['start_minute'] != "00" ? "0".$scheduled_interval_span['start_minute'] : $scheduled_interval_span['start_minute'];
			$date = date_create(current_time("Y-m-d")." ".$hour.":".$minute, timezone_open($wcra_time_model->get_current_time_zone()/* get_option('timezone_string') */));
			
			//date_timezone_set ( $date , get_option('gmt_offset') );
			/* wcra_var_dump($date);
			wcra_var_dump($hour." ".$minute);
			wcra_var_dump(get_option('gmt_offset'));
			wcra_var_dump($scheduled_interval_span['start_minute']); */
			//wcra_var_dump($date->getTimestamp() ); 
			/* wcra_var_dump(current_time("Y-m-d H:i")); */
			
			wp_schedule_event( $date->getTimestamp() /* $date->format('U') */, 'wcra_time_interval_span', 'wcra_role_assignment_check_event' ); 
		}
	}
	else //redundant -> hook: wrca_reset_schuler
		wp_clear_scheduled_hook( 'wcra_role_assignment_check_event' );
    
}

function wcra_perform_role_assignment_check() 
{
	global $wcra_customer_model;
	/* wcra_var_debug_dump("here");
	wcra_var_debug_dump(current_time("Y-m-d H:i")); */
    $wcra_customer_model->recompute_all_user_role();
}

function wrca_create_custom_time_interval( $schedules ) 
{
 
	global $wcra_general_option_model;
	$scheduled_interval_span = $wcra_general_option_model->get_scheduled_time_span();
	if(!empty($scheduled_interval_span))
	{
		$time = 3600; //sec
		switch($scheduled_interval_span['time_span'])
		{
			case 'hour': $time = 3600 * $scheduled_interval_span['time_quantity']; break;
			case 'day': $time = 86400 * $scheduled_interval_span['time_quantity']; break;
		}
		$schedules['wcra_time_interval_span'] = array(
				'interval'  => $time,
				'display'   => __( 'Role-O-Matic custom time interval span', 'woocommerce-role-by-amount-spent' )
		);
	}
	
    return $schedules;
}
add_filter( 'cron_schedules', 'wrca_create_custom_time_interval' );

function wrca_reset_schuler( $post_id ) 
{
	if(!function_exists('get_current_screen'))
		return;
	
    $screen = get_current_screen();
	if($screen->id == WCRA_AmountConfiguratorPage::$page_id);
		wp_clear_scheduled_hook( 'wcra_role_assignment_check_event' );
}
add_action('acf/save_post', 'wrca_reset_schuler', 20);
?>