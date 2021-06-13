<?php 
class WCRA_RoleCalculatorPage
{
	public function __construct()
	{
	}
	public function render_page()
	{
		global $wcra_time_model;
		wp_enqueue_style( 'wcra-common', WCRA_PLUGIN_PATH.'/css/backend-common.css');
		wp_enqueue_style( 'wcra-role-calculator', WCRA_PLUGIN_PATH.'/css/backend-role-calculator.css');
		
		wp_enqueue_script( 'wcra-role-calculator', WCRA_PLUGIN_PATH.'/js/backend-role-calculator.js', array('jquery'));
		
		/* wcra_var_dump(date_create(current_time("Y-m-d H:i"), timezone_open(get_option('timezone_string'))));
		$time_stamp = wp_next_scheduled( 'wcra_role_assignment_check_event' );
		$date = new DateTime();
		$date->setTimestamp($time_stamp);
		$date->setTimezone (timezone_open(get_option('timezone_string')));
		wcra_var_dump($date); */
		
		/*  wcra_var_dump(date_create(current_time("Y-m-d H:i")));
		 $time_stamp = wp_next_scheduled( 'wcra_role_assignment_check_event' );
		 $date = new DateTime();
	 	 $date->setTimestamp($time_stamp);
		 wcra_var_dump($date); */
		 
		/*  wcra_var_dump(timezone_open(get_option('timezone_string')));
		 wcra_var_dump(timezone_open(date_default_timezone_get())); 
		 wcra_var_dump(get_option('timezone'));*/
		 //wcra_var_dump($wcra_time_model->timeZoneConvert( $date->format("Y-m-d H:i"), timezone_open(get_option('timezone_string')), date_default_timezone_get());
		
		?>
		<div class="wrap white-box">
			<h2 class="wcra_title_with_border"><?php _e('Recalculate user roles', 'woocommerce-shipping-tracking');?></h2>
			<p><?php _e('By default, user roles assignment by amout spent is performed after an order has been placed by the customer. However if you want to compute user roles without waiting the next order placement by the user, hit the "Recompute" button to force the recalculation of user roles.','woocommerce-role-by-amount-spent');?></p>
			
			<div id="progress-bar-container">
				<div id="notice-box"></div>
				<div id="progress-bar-background"><div id="progress-bar"></div></div>		
			</div>
									
			<p class="submit">
				<button class="button button-primary" id="recompute_button"><?php _e('Recompute','woocommerce-role-by-amount-spent');?></button>
			</p>
		</div>
		<?php 
	}
}
?>