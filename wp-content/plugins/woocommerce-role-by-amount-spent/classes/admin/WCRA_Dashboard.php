<?php 
class WCRA_Dashboard
{
	public function __construct()
	{
		
		 add_action( 'wp_dashboard_setup', array( &$this, 'add_server_time_widget' ) );
		 //add_action( 'woocommerce_process_product_meta',  array( &$this, 'save_widget_data') );
	}
	public function add_server_time_widget()
	{
		if(current_user_can('manage_woocommerce') || current_user_can('edit_posts'))
			wp_add_dashboard_widget( 'wpps-server-time', __('WooCommerce Role by Amount Spent - Server time', 'woocommerce-role-by-amount-spent'), array( &$this, 'render_server_time_widget' ));
		 
	}
	function render_server_time_widget()
	{
		global $wcra_general_option_model;
		$hour_offset = $wcra_general_option_model->get_time_offset();
		/* $server_headers = apache_request_headers();
		wcra_var_dump($server_headers['Host']); */
		?>
		<p class="form-field">
			<label  style="display: inline;"><?php echo __( 'Current server time with offset (date format: dd/mm/yyyy):', 'woocommerce-role-by-amount-spent' ); ?></label>
			<span class="wrap">
				<strong style=" font-size: 20px;"><?php echo current_time("Y-m-d H:i")/* date("d/m/Y H:i",strtotime($hour_offset.' minutes')) */; ?></strong>
			</span>
			<br/>
			<!--<span style="display:block; clear:both;" class="description"><?php _e( sprintf('Rule dates are syncronized with server time. Configure a proper minutes offset hour by the <a href="%s">Configurator</a>', get_admin_url()."admin.php?page=".WCRA_GeneralOptionsConfiguratorPage::$page_url_par), 'woocommerce-role-by-amount-spent' ); ?></span>--> 
			<span style="display:block; clear:both;" class="description"><?php _e( sprintf('Rule dates are syncronized with server time. Configure a proper <strong>Timezone</strong> in the <a href="%s">Settings -> General</a> option menu', get_admin_url()."options-general.php"), 'woocommerce-role-by-amount-spent' ); ?></span> 
		</p>
							
		<?php
	}
	
}
?>