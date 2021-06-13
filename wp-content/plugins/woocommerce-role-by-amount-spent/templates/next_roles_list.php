<?php 
	//wcra_var_dump($role_code_to_role_name);
	//wcra_var_dump($rules_roles_and_amounts);
	//for($i = 0; $i < 20; $i++) //test
	if(!empty($rules_roles_and_amounts) && $parameters['is_my_account']): ?>
	 <h2 class="wcra_next_role_list_title"><?php echo $texts['next_roles_list_title'];?> </h2>
	<?php endif;  
	foreach((array)$rules_roles_and_amounts as $rule_and_amount): 
	
		$end_date_obj = DateTime::createFromFormat("Y-m-d H:i", $rule_and_amount['end_date'] );
		$start_date_obj = DateTime::createFromFormat("Y-m-d H:i", $rule_and_amount['start_date']);
		$spent_percentage = ($rule_and_amount['total_spent']/$rule_and_amount['amount_to_achieve'])*100;
		$spent_percentage = $spent_percentage > 100 ? 100 : $spent_percentage;
		$end_date = $start_date = "";
		
		if(is_object($end_date_obj))
			$end_date = $end_date_obj->format(get_option( 'date_format' )." - ".get_option( 'time_format' ));
		if(is_object($start_date_obj))
			$start_date = $start_date_obj->format(get_option( 'date_format' )." - ".get_option( 'time_format' ));
		
		?>
		<div class="wcra_next_role_container">
			<div class="wcra_next_role_names_container">
				<?php $name_counter = 0;
					foreach((array)$rule_and_amount['role_to_assign'] as $role_to_assign): 
						if(isset($role_code_to_role_name[$role_to_assign])): ?>
						<span class="wcra_next_role_single_name"><?php echo $role_code_to_role_name[$role_to_assign]; if($name_counter++ > 1) echo ","; ?></span>
				<?php endif; endforeach; ?>
			</div>
			<span class="wcra_next_role_total_spent">
				<span class="wcra_next_role_total_spent_label"><?php echo __('Spent: ', 'woocommerce-role-by-amount-spent');?></span> 
				<span class="wcra_next_role_total_spent_value"><?php echo wc_price($rule_and_amount['total_spent']); ?></span>
			</span>
			<span class="wcra_next_role_amount_to_achieve">
				<span class="wcra_next_role_amount_spent_label"><?php echo __('Amount to achieve: ', 'woocommerce-role-by-amount-spent');?></span> 
				<span class="wcra_next_role_amount_spent_value"><?php echo wc_price($rule_and_amount['amount_to_achieve']); ?></span>
			<?php if($rule_and_amount['time_period_type'] == 'fixed'): ?>
			<span class="wcra_next_role_end_date">
				<span class="wcra_next_role_end_date_label"><?php echo __('End date: ', 'woocommerce-role-by-amount-spent');?></span> 
				<span class="wcra_next_role_end_date_value"><?php echo $end_date; ?></span>
			</span>
			<?php else: ?>
			<span class="wcra_next_role_start_date">
				<span class="wcra_next_role_start_date_label"><?php echo __('Since: ', 'woocommerce-role-by-amount-spent');?></span> 
				<span class="wcra_next_role_start_date_value"><?php echo $start_date; ?></span>
			</span>
			<?php endif; ?>
			<div class="wcra_progress_bar">
				<div class="wcra_progress_bar_background" ></div>
				<div class="wcra_progress_bar_progress" style="width:<?php echo $spent_percentage; ?>%;"></div>
			</div>
		</div>
	
<?php  endforeach; //$rules_roles_and_amounts ?>