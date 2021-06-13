<?php if(!empty($roles_to_show)): 
		if( $parameters['is_my_account']): ?>
		<h2 class="wcra_role_list_title"><?php echo $texts['role_list_title']; ?></h2>	
	<?php endif; ?>
	<ul class="wrca_role_list">
	<?php 
	endif;

	foreach((array)$roles_to_show as $role_name)
	{
		?>
		<li class="wcra_role_name_list_element"><span class="wcra_role_name"><?php echo $role_name; ?></span></li>
		<?php 
	}
	if(!empty($roles_to_show)): ?>
	</ul>
<?php endif; ?>