<?php 
class WCRA_RoleEditorPage
{
	public function __construct()
	{
	}
	private function save_data()
	{
		global $wcra_role_model;
		$message = array('message' => "" , 'error'=>false);
		//wcra_var_dump($_POST);
		if(isset($_POST['add_new_role']))
		{
			if(isset($_POST['wcra_new_role_name']) && $_POST['wcra_new_role_name'] != "")
			{
				$wcra_role_model->create_new_role($_POST['wcra_new_role_name']);
				$message['message'] = __('Role added successfully', 'woocommerce-shipping-tracking');
			}
			else
				return $message = array('message' => __('Role name cannot be empty', 'woocommerce-shipping-tracking') , 'error'=>true);
		}
		else if(isset($_POST['delete_selected']))
		{
			if(isset($_POST['wcra_roles_to_delete']))
			{
				$wcra_role_model->delete_roles($_POST['wcra_roles_to_delete']);
				$message['message'] = __('Role deleted successfully', 'woocommerce-shipping-tracking');
			}
			else
				return $message = array('message' => __('Select at least one role to delete', 'woocommerce-shipping-tracking') , 'error'=>true);
		}
		return $message;
	}
	public function render_page()
	{
		global $wp_roles;
		wp_enqueue_style( 'wcra-common', WCRA_PLUGIN_PATH.'/css/backend-common.css');
		wp_enqueue_style( 'wcra-backend-role-configurator', WCRA_PLUGIN_PATH.'/css/backend-role-configurator.css');
		
		$result = null;
		
		if(isset($_POST) && !empty($_POST))
			$result = $this->save_data();
		
		if(isset($result) && $result['error'] == false):
			?>
			<div class="updated notice notice-success is-dismissible" id="message"><p><?php echo $result['message']; ?></p><button class="notice-dismiss" type="button"><span class="screen-reader-text">Dismiss this notice.</span></button></div>
		<?php elseif(isset($result)): ?>
			<div class="notice notice-error is-dismissible" id="message"><p><?php echo $result['message']; ?></p><button class="notice-dismiss" type="button"><span class="screen-reader-text">Dismiss this notice.</span></button></div>
		<?php endif; ?>
		<div class="wrap white-box">
		<form action="" method="post" >
			<h2 class="wcra_title_with_border"><?php _e('Existing roles', 'woocommerce-shipping-tracking');?></h2>
			<?php foreach((array)$wp_roles->roles as $role_code => $role):
				if($role_code != 'administrator'): ?>
				<input type="checkbox" name="wcra_roles_to_delete[]" value="<?php echo $role_code; ?>"><?php echo $role['name']; ?></input><br/>
			<?php endif; endforeach; ?>		
			<p class="submit">
				<input type="submit" value="<?php _e('Delete selected','woocommerce-role-by-amount-spent');?>" class="button-primary button-delete" name="delete_selected" />
			</p>
			<h2 class="wcra_title_with_border"><?php _e('Add new role', 'woocommerce-shipping-tracking');?></h2>
			<p><?php _e('Here you can create new roles that can be used in the <strong>Roles and Amounts Configurator</strong>. These special roles can be used to assign special discount or prices using the <a href="http://codecanyon.net/item/woocommerce-pricing/14679278">WooCommerce Pricing!</a> plugin.', 'woocommerce-shipping-tracking');?></p>
			<input type="text" name="wcra_new_role_name" class="wcra_text_field" value="" placeholder="<?php _e('Type new role name like: Premium customer march 2016', 'woocommerce-shipping-tracking');?>"></input>
			
									
			<p class="submit">
				<input type="submit" value="<?php _e('Add new role','woocommerce-role-by-amount-spent');?>" class="button-primary" name="add_new_role" />
			</p>
			</form>
		</div>
		<?php 
	}
}
?>