<?php 
/*
Plugin Name: WooCommerce Role-O-Matic
Description: Set products prices or discounts per time periods or recurring events according to product quantities and user roles.
Author: Lagudi Domenico
Version: 8.0
*/

/* 
Copyright: WooCommerce Role-O-Matic uses the ACF PRO plugin. ACF PRO files are not to be used or distributed outside of the WooCommerce Role-O-Matic plugin.
*/
define('WCRA_PLUGIN_PATH', rtrim(plugin_dir_url(__FILE__), "/") )  ;
define('WCRA_PLUGIN_ABS_PATH', plugin_dir_path( __FILE__ ) );

if ( !defined('WP_CLI') && ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ||
					   (is_multisite() && array_key_exists( 'woocommerce/woocommerce.php', get_site_option('active_sitewide_plugins') ))
					 )	
	)
{
	//For some reasins the theme editor in some installtion won't work. This directive will prevent that.
	if(isset($_POST['action']) && $_POST['action'] == 'edit-theme-plugin-file')
		return;
	
	if(isset($_REQUEST ['context']) && $_REQUEST['context'] == 'edit') //rest api
		return;
		
	if(isset($_POST['action']) && strpos($_POST['action'], 'health-check') !== false) //health check
		return;
	
	if(isset($_REQUEST['is_admin'])) //Fixes and uncompability with Project Manager plugin
		return;
		
	$wcra_id = 15418671;
	$wcra_name = "WooCommerce Role-O-Matic";
	$wcra_activator_slug = "wcra-activator";
	
	//com
	include 'classes/com/WCRA_Acf.php';
	include 'classes/com/WCRA_Global.php';
	require_once('classes/admin/WCRA_ActivationPage.php');
	
	add_action('init', 'wcra_init');	
	add_action('admin_notices', 'wcra_admin_notices' );
	add_action('admin_menu', 'wcra_init_act');
	if(defined('DOING_AJAX') && DOING_AJAX)
		wcra_init_act();
}
function wcra_setup()
{
	global $wcra_wpml_helper, $wcra_email_helper, $wcra_role_model, $wcra_product_model, $wcra_customer_model, $wcra_cart_model,
		   $wcra_role_per_amount_model, $wcra_role_per_product_model, $wcra_general_option_model, $wcra_order_model, $wcra_shortcodes, 
		   $wcra_text_helper, $wcra_dashboard_widget, $wcra_user_page, $wcra_checkout_controller, $wcra_my_account_page, $wcra_time_model;
	
	//com
	if(!class_exists('WCRA_Wpml'))
	{
		require_once('classes/com/WCRA_Wpml.php');
		$wcra_wpml_helper = new WCRA_Wpml();
	}
	if(!class_exists('WCRA_Time'))
	{
		require_once('classes/com/WCRA_Time.php');
		$wcra_time_model = new WCRA_Time();
	}
	if(!class_exists('WCRA_Email'))
	{
		require_once('classes/com/WCRA_Email.php');
		$wcra_email_helper = new WCRA_Email();
	}
	if(!class_exists('WCRA_Role'))
	{
		require_once('classes/com/WCRA_Role.php');
		$wcra_role_model = new WCRA_Role();
	}
	if(!class_exists('WCRA_Product'))
	{
		require_once('classes/com/WCRA_Product.php');
		$wcra_product_model = new WCRA_Product();
	}
	if(!class_exists('WCRA_Customer'))
	{
		require_once('classes/com/WCRA_Customer.php');
		$wcra_customer_model = new WCRA_Customer();
	}
	if(!class_exists('WCRA_Cart'))
	{
		require_once('classes/com/WCRA_Cart.php');
		$wcra_cart_model = new WCRA_Cart();
	}
	if(!class_exists('WCRA_RolePerAmountRule'))
	{
		require_once('classes/com/WCRA_RolePerAmountRule.php');
		$wcra_role_per_amount_model = new WCRA_RolePerAmountRule();
	}
	if(!class_exists('WCRA_RolePerProductRule'))
	{
		require_once('classes/com/WCRA_RolePerProductRule.php');
		$wcra_role_per_product_model = new WCRA_RolePerProductRule();
	}
	if(!class_exists('WCRA_GeneralOption'))
	{
		require_once('classes/com/WCRA_GeneralOption.php'); 
		$wcra_general_option_model = new WCRA_GeneralOption();
	}
	if(!class_exists('WCRA_Order'))
	{
		require_once('classes/com/WCRA_Order.php');
		$wcra_order_model = new WCRA_Order();
	}
	if(!class_exists('WCRA_Shortcodes'))
	{
		require_once('classes/com/WCRA_Shortcodes.php');
		$wcra_shortcodes = new WCRA_Shortcodes();
	}
	if(!class_exists('WCRA_Text'))
	{
		require_once('classes/com/WCRA_Text.php');
		$wcra_text_helper = new WCRA_Text();
	}
	
	//admin
	if(!class_exists('WCRA_Dashboard'))
	{
		require_once('classes/admin/WCRA_Dashboard.php');
		$wcra_dashboard_widget = new WCRA_Dashboard();
	}
	if(!class_exists('WCRA_UserPage'))
	{
		require_once('classes/admin/WCRA_UserPage.php');
		$wcra_user_page = new WCRA_UserPage();
	}
	if(!class_exists('WCRA_TextConfiguratorPage'))
		require_once('classes/admin/WCRA_TextConfiguratorPage.php');
	if(!class_exists('WCRA_AmountConfiguratorPage'))
		require_once('classes/admin/WCRA_AmountConfiguratorPage.php');
	if(!class_exists('WCRA_ProductConfiguratorPage'))
		require_once('classes/admin/WCRA_ProductConfiguratorPage.php');
	if(!class_exists('WCRA_GeneralOptionsConfiguratorPage'))
		require_once('classes/admin/WCRA_GeneralOptionsConfiguratorPage.php');
	if(!class_exists('WCRA_RoleEditorPage'))
		require_once('classes/admin/WCRA_RoleEditorPage.php');
	if(!class_exists('WCRA_RoleCalculatorPage'))
		require_once('classes/admin/WCRA_RoleCalculatorPage.php');
	
	//frontend
	if(!class_exists('WCRA_CheckoutPage'))
	{
		require_once('classes/frontend/WCRA_CheckoutManager.php');
		$wcra_checkout_controller = new WCRA_CheckoutManager();
	}
	if(!class_exists('WCRA_MyAccountPage'))
	{
		require_once('classes/frontend/WCRA_MyAccountPage.php');
		$wcra_my_account_page = new WCRA_MyAccountPage();
	}
	
	
	include 'classes/com/WCRA_Cron.php';
	
	add_action('admin_init', 'wcra_admin_init');
	add_action('admin_menu', 'wcra_init_admin_panel');	
	add_action( 'wp_print_scripts', 'wcra_unregister_css_and_js' );
}
function wcra_unregister_css_and_js($enqueue_styles)
{
	$url = $_SERVER['REQUEST_URI'];
	if( strpos($url, '/point-of-sale') !== false)
	{
		wp_dequeue_script('select2');
	}
}
function wcra_admin_notices()
{
	global $wcra_notice, $wcra_name, $wcra_activator_slug;
	if($wcra_notice && (!isset($_GET['page']) || $_GET['page'] != $wcra_activator_slug))
	{
		 ?>
		<div class="notice notice-success">
			<p><?php echo sprintf(__( 'To complete the <span style="color:#96588a; font-weight:bold;">%s</span> plugin activation, you must verify your purchase license. Click <a href="%s">here</a> to verify it.', 'woocommerce-role-by-amount-spent' ), $wcra_name, get_admin_url()."admin.php?page=".$wcra_activator_slug); ?></p>
		</div>
		<?php
	}
}
function wcra_init()
{
	load_plugin_textdomain('woocommerce-role-by-amount-spent', false, basename( dirname( __FILE__ ) ) . '/languages' );
	/* if(is_admin())
		wcra_init_act(); */
}
function wcra_init_act()
{
	global $wcra_activator_slug, $wcra_name, $wcra_id;
	new WCRA_ActivationPage($wcra_activator_slug, $wcra_name, 'woocommerce-role-by-amount-spent', $wcra_id, WCRA_PLUGIN_PATH);
}
function wcra_admin_init()
{
	$remove = remove_submenu_page( 'woocommerce-role-by-amount-spent', 'woocommerce-role-by-amount-spent');
}	
function wcra_init_admin_panel()
{
	if(!current_user_can('manage_woocommerce'))
		return;
	$place = $place = wcra_get_free_menu_position(55, 0.1);
	
	$hookname  = add_menu_page( __( 'Role-O-Matic', 'woocommerce-role-by-amount-spent' ), __( 'Role-O-Matic', 'woocommerce-role-by-amount-spent' ), 'manage_woocommerce', 'woocommerce-role-by-amount-spent', null, WCRA_PLUGIN_PATH."/images/user-icon.png", (string)$place );
	add_submenu_page('woocommerce-role-by-amount-spent', __('Roles editor','woocommerce-role-by-amount-spent'), __('Roles editor','woocommerce-role-by-amount-spent'), 'edit_shop_orders', 'woocommerce-role-by-amount-roles-editor', 'wcra_render_roles_editor_page');
	add_submenu_page('woocommerce-role-by-amount-spent', __('Roles re-calculator','woocommerce-role-by-amount-spent'), __('Roles re-calculator','woocommerce-role-by-amount-spent'), 'edit_shop_orders', 'woocommerce-role-by-amount-roles-calculator', 'wcra_render_roles_calculator_page');
	$amounts_configurator = new WCRA_AmountConfiguratorPage();
	$products_configurator = new WCRA_ProductConfiguratorPage();
	$general_options_configurator = new WCRA_GeneralOptionsConfiguratorPage();
	$text_options_configurator = new WCRA_TextConfiguratorPage();
	
	//wcra_var_dump($remove );	
}
function wcra_get_free_menu_position($start, $increment = 0.1)
{
	foreach ($GLOBALS['menu'] as $key => $menu) {
		$menus_positions[] = $key;
	}
	
	if (!in_array($start, $menus_positions)) return $start;

	/* the position is already reserved find the closet one */
	while (in_array($start, $menus_positions)) 
	{
		$start += $increment;
	}
	return $start;
}
function wcra_render_roles_editor_page()
{
	$roles_editor_page = new WCRA_RoleEditorPage();
	$roles_editor_page->render_page();
}
function wcra_render_roles_calculator_page()
{
	$roles_calculator_page = new WCRA_RoleCalculatorPage();
	$roles_calculator_page->render_page();
}
?>