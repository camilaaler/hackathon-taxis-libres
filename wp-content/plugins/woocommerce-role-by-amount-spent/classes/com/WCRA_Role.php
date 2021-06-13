<?php 
class WCRA_Role
{
	public function __construct()
	{
	}
	public function create_new_role($role_name)
	{
		$role_code = strtolower($role_name);
		$role_code = strip_tags($role_code);
		$role_code = str_replace(" ", "_",$role_code);
		$role_code = preg_replace("/[^\w.-]/","",$role_code); 
		//wcra_var_dump($role_code);
		add_role( $role_code, $role_name);
	}
	public function delete_roles($roles_to_delete)
	{
		//wcra_var_dump($roles_to_delete);
		foreach((array)$roles_to_delete as $role_code)
			remove_role(  $role_code );
	}
}
?>