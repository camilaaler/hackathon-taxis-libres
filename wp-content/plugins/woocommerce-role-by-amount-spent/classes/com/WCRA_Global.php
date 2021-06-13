<?php 
//format Y-m-d H:i
function wcra_date_compare($date1, $date2)
{
	$date1 = strtotime($date1);
	$date2 = strtotime($date2);
	if($date1 == $date2)
			return 0;
	else if($date1 > $date2)
		return 1;
	
	return -1;
}
//format d-m-Y H:i		
function wcra_format_date($date, $format="d-m-Y H:i")
{
	if($date == false)
		return __('Never', 'woocommerce-role-by-amount-spent');
	$new_date = date_create($date);
	//date_add($new_date, date_interval_create_from_date_string($rule_to_examine['expiring_date_value'].' '.$rule_to_examine['expiring_time_type']));
	if($format == "default")
		$format = get_option('date_format')." : ".get_option('time_format');
	
	$expiring_date = is_object($new_date) ? date_format($new_date, $format) : $date;
	return $expiring_date;
}
$wcra_result = get_option("_".$wcra_id);
$wcra_notice = !$wcra_result || $wcra_result != md5($_SERVER['SERVER_NAME']);
$wcra_notice = false;
/* if($wcra_notice)
	remove_action( 'plugins_loaded', 'wcra_setup'); */
if(!$wcra_notice)
	wcra_setup();
function wcra_add_date($date, $time_to_add)
{
	$date = date_create($date);
	if(is_object($date))
	{
		date_add($date, date_interval_create_from_date_string($time_to_add));
		$date = date_format($date, 'Y-m-d H:i');
	}
	else
		$date = __('Error computing date', 'woocommerce-role-by-amount-spent');
	
	return $date;
}
function wcra_var_dump($data)
{
	echo "<pre>";
	var_dump($data);
	echo "</pre>";
}
function wcra_var_debug_dump($log)
{
	 if ( is_array( $log ) || is_object( $log ) ) 
	 {
         error_log( print_r( $log, true ) );
      } 
	  else if(is_bool($log))
	  {
		 error_log( $log ? 'true' : 'false' );  
	  }	  
	  else{
         error_log( $log );
      }

}