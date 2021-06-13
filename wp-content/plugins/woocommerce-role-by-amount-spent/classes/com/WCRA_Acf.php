<?php 
$wcra_active_plugins = get_option('active_plugins');
$wcra_acf_pro = 'advanced-custom-fields-pro/acf.php';
$wcra_acf_pro_is_aleady_active = in_array($wcra_acf_pro, $wcra_active_plugins) || class_exists('acf') ? true : false;
if(!$wcra_acf_pro_is_aleady_active)
	include_once( WCRA_PLUGIN_ABS_PATH . '/classes/acf/acf.php' );

$wcra_hide_menu = true;
if ( ! function_exists( 'is_plugin_active' ) ) 
{
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); 
}
/* Checks to see if the acf pro plugin is activated  */
if ( is_plugin_active('advanced-custom-fields-pro/acf.php') )  {
	$wcra_hide_menu = false;
}

/* Checks to see if the acf plugin is activated  */
if ( is_plugin_active('advanced-custom-fields/acf.php') ) 
{
	add_action('plugins_loaded', 'wcra_load_acf_standard_last', 10, 2 ); //activated_plugin
	add_action('deactivated_plugin', 'wcra_detect_plugin_deactivation', 10, 2 ); //activated_plugin
	$wcra_hide_menu = false;
}
function wcra_detect_plugin_deactivation(  $plugin, $network_activation ) { //after
   // $plugin == 'advanced-custom-fields/acf.php'
	//wcra_var_dump("wcra_detect_plugin_deactivation");
	$acf_standard = 'advanced-custom-fields/acf.php';
	if($plugin == $acf_standard)
	{
		$active_plugins = get_option('active_plugins');
		$this_plugin_key = array_keys($active_plugins, $acf_standard);
		if (!empty($this_plugin_key)) 
		{
			foreach($this_plugin_key as $index)
				unset($active_plugins[$index]);
			update_option('active_plugins', $active_plugins);
			//forcing
			deactivate_plugins( plugin_basename( WP_PLUGIN_DIR.'/advanced-custom-fields/acf.php') );
		}
	}
} 
function wcra_load_acf_standard_last($plugin, $network_activation = null) { //before
	$acf_standard = 'advanced-custom-fields/acf.php';
	$active_plugins = get_option('active_plugins');
	$this_plugin_key = array_keys($active_plugins, $acf_standard);
	if (!empty($this_plugin_key)) 
	{ 
		foreach($this_plugin_key as $index)
			//array_splice($active_plugins, $index, 1);
			unset($active_plugins[$index]);
		//array_unshift($active_plugins, $acf_standard); //first
		array_push($active_plugins, $acf_standard); //last
		update_option('active_plugins', $active_plugins);
	} 
}
if(!$wcra_acf_pro_is_aleady_active)
	add_filter('acf/settings/path', 'wcra_acf_settings_path');
function wcra_acf_settings_path( $path ) 
{
 
    // update path
    $path = WCRA_PLUGIN_ABS_PATH. '/classes/acf/';
    
    // return
    return $path;
    
}
if(!$wcra_acf_pro_is_aleady_active)
	add_filter('acf/settings/dir', 'wcra_acf_settings_dir');
function wcra_acf_settings_dir( $dir ) {
 
    // update path
    $dir =  WCRA_PLUGIN_PATH . '/classes/acf/';
    
    // return
    return $dir;
    
}

function wcra_acf_init() {
    
    include WCRA_PLUGIN_ABS_PATH . "/assets/fields.php";
    
}

add_action('acf/init', 'wcra_acf_init'); 

//hide acf menu
if($wcra_hide_menu)	
	add_filter('acf/settings/show_admin', '__return_false');


//********************************************
//CUSTOM COMPONENT
add_action('acf/include_field_types', 'wcra_include_custom_components');

function wcra_include_custom_components( $version ) {
	
	if(!class_exists('acf_field_role_selector'))
		include_once(WCRA_PLUGIN_ABS_PATH.'/classes/com/vendor/acf-role-selector-field/acf-role_selector-v5.php');

	if(!class_exists('acf_field_unique_id'))
		include_once(WCRA_PLUGIN_ABS_PATH.'/classes/com/vendor/acf-field-unique-id/acf-unique_id-v5.php');
}


//CUSTOM FILTERS
function wcra_acf_manage_wpml_translation( $post_id  ) 
{
	if( empty($_POST['acf']) || $_GET['page'] != WCRA_AmountConfiguratorPage::$page_url_par) 
        return;
	
	global $wcra_wpml_helper;
    $fields_groups = $_POST['acf'];
	
	foreach($fields_groups as $fields_group) //repeater
	{
		foreach($fields_group as $field_group_id => $fields_array)
		{
			$id = str_replace("field_", "", $field_group_id);
		
			/* $counter = 0;
			foreach($fields_array as $field_id => $field)
			{
				if( $field_id == 'field_589eff6a27314') //subject
					$subject = $field;
				$counter++;
			} */
			$subject_result = isset($fields_array['field_589eff6a27314']) ? $wcra_wpml_helper->register_single_string($id, $fields_array['field_589eff6a27314']) : false;
			$body_result = isset($fields_array['field_58932e7681d43']) ? $wcra_wpml_helper->register_single_string($id, $fields_array['field_58932e7681d43'], 'email_body_') : false;
		} 
	 }
	
}
//add_filter('acf/save_post', 'wcra_acf_manage_wpml_translation', 30); //1:before save; 20: after save

//Avoid custom fields metabox removed by pages
add_filter('acf/settings/remove_wp_meta_box', '__return_false');
?>