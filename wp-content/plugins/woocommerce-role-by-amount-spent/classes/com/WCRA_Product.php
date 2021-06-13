<?php 
class WCRA_Product
{
	public function __construct()
	{
	}
	//check done on add to cart, update cart and on checkout cart validation
	public function role_item_can_be_purchased($product_id, $variation_id)
	{
		global $wcra_customer_model, $wcra_role_per_product_model,$wcra_general_option_model,$wcra_wpml_helper;
		$rules = $wcra_role_per_product_model->get_rules();
		$roles = $wcra_customer_model->get_current_user_roles();
		$time_offset = $wcra_general_option_model->get_time_offset();
		//$now = date("Y-d-m H:i", strtotime($time_offset.' minutes'));
		$now = current_time("Y-m-d H:i");
		
		//wpml
		$product_id = $wcra_wpml_helper->get_original_id($product_id);
		$variation_id = $variation_id != 0 ? $wcra_wpml_helper->get_original_id($variation_id, 'product_variation') : $variation_id;
		foreach((array)$rules as $rule)
		{
			//not logged
			/* if( ($roles == false || !is_array($roles)) &&  is_array($rule['purchase_restriction_by_products']) && (in_array($product_id, $rule['purchase_restriction_by_products']) || in_array($variation_id, $rule['purchase_restriction_by_products'])) )
				return false; */
			if( $roles == false || !is_array($roles) )
				return true;
			
			//Roles
			if( is_array($rule['purchase_restriction_by_products']) && (in_array($product_id, $rule['purchase_restriction_by_products']) || in_array($variation_id, $rule['purchase_restriction_by_products'])))
				foreach((array)$roles as $role_code)
					if(in_array($role_code, $rule['product_roles_to_assign'])) 
					{
						
						return false;
					}
			
			//In case of relative date, check if can be purchased before expiring (date compare)	
			if($rule['expiring_date_type'] == 'none' && (in_array($product_id, $rule['selected_products']) || in_array($variation_id, $rule['selected_products']))) 
			{
				if($rule['repurchase_strategy'] == 'no')
					return false;
				else 
					return true;
			}
			if($rule['expiring_date_type'] == 'relative'  && (in_array($product_id, $rule['selected_products']) || in_array($variation_id, $rule['selected_products'])) )
			{
			
				$purchase_date = $wcra_customer_model->get_purchase_date_for_role_product($product_id, $variation_id);
				if($rule['repurchase_strategy'] == 'no')
				{
					if($purchase_date === 'false') //not logged
						return false;
					if( $purchase_date != "" && wcra_date_compare($now , date($purchase_date, strtotime($rule['expiring_date_value'].' '.$rule['expiring_date_type']) > 0)))
						return false;
				}
			}
			//In case of fixed date (date compare)	
			if($rule['expiring_date_type'] == 'fixed' && (in_array($product_id, $rule['selected_products']) || in_array($variation_id, $rule['selected_products'])))
			{
				$purchase_date = $wcra_customer_model->get_purchase_date_for_role_product($product_id, $variation_id);
				if($purchase_date === 'false') //not logged
						return false;
				if( $purchase_date != "" && wcra_date_compare($now , $rule['expiring_fixed_date']." ".$rule['expiring_fixed_hour'].":".$rule['expiring_fixed_minute']) > 0)
					return false;
			}
		}
		
		return true;
	}
	public function get_products_ids_to_use_as_filter_on_order_selection($product_ids, $categories_ids, $get_post_belonging_to_children_categories)
	{
		$product_ids_to_use_as_filter = array();
		
		if($product_ids != false && is_array($product_ids) && !empty($product_ids))
		{
			foreach($product_ids as $product)
			{
				$parent_id = wp_get_post_parent_id($product);
				$variation_id = $parent_id != 0 ? $product : 0;
				$product_id = $parent_id != 0 ? $parent_id : $product;
				
				$product_ids_to_use_as_filter[] = array('product_id'=>$product_id, 'variation_id'=>$variation_id);
				//wpml
				$this->set_product_translations_if_any($product_ids_to_use_as_filter, $product_id, $variation_id);
			}
		}
		if($categories_ids != false && is_array($categories_ids) && !empty($categories_ids))
		{
			$products_belonging_to_selected_categories = $this->get_post_ids_using_categories($categories_ids, "product_cat", $get_post_belonging_to_children_categories);
			if(!empty($products_belonging_to_selected_categories))
			{
				foreach($products_belonging_to_selected_categories as $additional_product)
				{
					$product_ids_to_use_as_filter[] = array('product_id'=>$additional_product['product_id'], 'variation_id'=>$additional_product['variation_id']);
					//wpml
					$this->set_product_translations_if_any($product_ids_to_use_as_filter, $additional_product['product_id'], $additional_product['variation_id']);
				}
			}
		}
		return $product_ids_to_use_as_filter;
	}
	public function get_expiring_date_role_product($main_id, $main_variation_id, $purchase_date, $rule = null, $format = "d-m-Y H:i")
	{
		global $wcra_general_option_model,  $wcra_role_per_product_model;
		//$time_offest = $wcra_general_option_model->get_time_offset();
		//$now = date("d-m-Y H:i",strtotime($time_offest.' minutes'));
		$rule_to_examine = $rule;
		
		if(!isset($rule_to_examine))
		{
			$rules = $wcra_role_per_product_model->get_rules();
			foreach((array)$rules as $rule)
			{
				foreach($rule['selected_products'] as $temp_selected_product_id)
				{
					$post_type = get_post_type($temp_selected_product_id);
					$product_id =  $post_type == 'product' ? $temp_selected_product_id : wp_get_post_parent_id($temp_selected_product_id);
					$variation_id = $post_type == 'product' ? 0 : $temp_selected_product_id;
					if($product_id == $main_id && ($variation_id == $main_variation_id || $variation_id == 0))
					{
						$rule_to_examine = $rule;
					}
				}
			}
		}
			
		if(isset($rule_to_examine))
		{
			if($rule_to_examine['expiring_date_type'] == 'fixed')
				return $rule_to_examine['expiring_fixed_date']." ".$rule_to_examine['expiring_fixed_hour'].":".$rule_to_examine['expiring_fixed_minute'];
							
			/* $expiring_date = date_create($purchase_date);
			date_add($expiring_date, date_interval_create_from_date_string($rule_to_examine['expiring_date_value'].' '.$rule_to_examine['expiring_time_type']));
			$expiring_date = date_format($expiring_date, 'd-m-Y H:i'); */
			if($rule_to_examine['expiring_date_type'] == "")
				return false;
			
			$rule_to_examine['expiring_time_type'] = is_array($rule_to_examine['expiring_time_type']) ? $rule_to_examine['expiring_time_type'][0] : $rule_to_examine['expiring_time_type'];
			$expiring_date = wcra_add_date($purchase_date, $rule_to_examine['expiring_date_value'].' '.$rule_to_examine['expiring_time_type']);
			$expiring_date = wcra_format_date($expiring_date, $format);
			return $expiring_date;
		}
		return "";
	}
	private function set_product_translations_if_any( &$product_ids_to_use_as_filter, $product_id, $variation_id)
	{
		global $wcra_wpml_helper;
		if(!$wcra_wpml_helper->is_active())
			return;
		
		if($variation_id != 0)
		{
			$variation_translations = $wcra_wpml_helper->get_all_translation_ids($variation_id, 'product_variation');
			if($variation_translations != false && !empty($variation_translations))
				foreach($variation_translations as $translated_variation)
					$product_ids_to_use_as_filter[] = array('product_id'=>wp_get_post_parent_id($translated_variation), 'variation_id'=>$translated_variation);
		}
		else
		{
			$product_translations = $wcra_wpml_helper->get_all_translation_ids($product_id);
			if($product_translations != false && !empty($product_translations))
				foreach($product_translations as $translated_product)
					$product_ids_to_use_as_filter[] = array('product_id'=>$translated_product, 'variation_id'=>0);
		}
	}
	public function get_complementry_ids($ids_to_exclude/* , $post_type = "product" */)
	{
		global $wpdb;
		$product_ids = array();
		foreach($ids_to_exclude as $product)
		{
			
			if($product['variation_id'] != 0)
			{
				$product_ids[] = $product['variation_id'];
			}
			else
			{
				$product_ids[] = $product['product_id'];
			}
		}
			
		$results = array();
		$query = "SELECT posts.ID, posts.post_type, posts.post_parent
				  FROM {$wpdb->posts} AS posts
				  WHERE ".
				  //posts.post_type = '{$post_type}' AND  
				  "posts.ID NOT IN('".implode("','",$product_ids)."') ";
		$ids = $wpdb->get_results($query, ARRAY_A);
		foreach($ids as $id)
		{
			if($id['post_type'] == 'product')
				$results[] = array('product_id' => (int)$id['ID'], 'variation_id' => 0);
			else
				$results[] = array('product_id' => (int)$id['post_parent'], 'variation_id' => (int)$id['ID']);
		}
		return $results;
	}
	public function product_belongs_to_categories($product_id, $categories)
	{
		if(!isset($categories) || empty($categories) || !isset($product_id))
			return false;
		
		$categories = !is_array($categories) ? array($categories) : $categories;
		$product = wc_get_product($product_id);
		if(!isset($product) || $product == false)
			return false;
		
		/* wcra_var_dump($product->get_category_ids( ));
		wcra_var_dump($categories);
		wcra_var_dump(array_intersect( $product->get_category_ids( ), $categories));
		wcra_var_dump(!empty(array_intersect( $product->get_category_ids( ), $categories))); */
		return !empty(array_intersect( $product->get_category_ids( ), $categories));
	}
	public function merge_products_with_the_one_seleted_via_category($product_role_rule)
	{
		$products = $this->get_post_ids_using_categories($product_role_rule['selected_categories']);
		foreach($products as $products_obj)
		{
			$prduct_id = $products_obj['variation_id'] != 0 ? $products_obj['variation_id'] : $products_obj['product_id'];
			if(!in_array($prduct_id, $product_role_rule['selected_products']))
				$product_role_rule['selected_products'][] = $prduct_id;
				
		}
		return $product_role_rule['selected_products'];
	}
	private function get_post_ids_using_categories( $selected_categories, $category_type_name="product_cat", $get_post_belonging_to_children_categories = "selected_only" , $strategy = "all")
	{
		//$get_post_belonging_to_children_categories : "selected_only" || "all_children"
		
		global $wpdb;
		$not_suffix = $strategy == "all" ? "  " : " NOT ";
		$results = $additional_categories_ids = array();
		
		//Retrieve children categories id
		if($get_post_belonging_to_children_categories == 'all_children')
		{
			foreach($selected_categories as $current_category)
			{
				$args = array(
						'type'                     => 'post',
						'child_of'                 => $current_category,
						'parent'                   => '',
						'orderby'                  => 'name',
						'order'                    => 'ASC',
						'hide_empty'               => 1,
						'hierarchical'             => 1,
						'exclude'                  => '',
						'include'                  => '',
						'number'                   => '',
						'taxonomy'                 => $category_type_name,
						'pad_counts'               => false

					); 

					$categories = get_categories( $args );
					//wctbp_var_dump($categories);
					foreach($categories as $result)
					{
						if(!is_array($result))
							$additional_categories_ids[] = (int)$result->term_id;
					}
			}
		}
		if(!empty($additional_categories_ids))
			$selected_categories = array_merge($selected_categories, $additional_categories_ids);
		
		
		//GROUP_CONCAT(posts.ID)
		//type = product || product_variation
	    $wpdb->query('SET group_concat_max_len=5000000'); 
		$wpdb->query('SET SQL_BIG_SELECTS=1');
		$query = "SELECT DISTINCT posts.ID, posts.post_type, posts.post_parent
				 FROM {$wpdb->posts} AS posts 
				 INNER JOIN {$wpdb->term_relationships} AS term_rel ON term_rel.object_id = posts.ID
				 INNER JOIN {$wpdb->term_taxonomy} AS term_tax ON term_tax.term_taxonomy_id = term_rel.term_taxonomy_id 
				 INNER JOIN {$wpdb->terms} AS terms ON terms.term_id = term_tax.term_id
				 WHERE  terms.term_id {$not_suffix} IN ('" . implode( "','", $selected_categories). "')  
				 AND term_tax.taxonomy = '{$category_type_name}' "; 
		$ids = $wpdb->get_results($query, ARRAY_A); 
		 /* $args = array(
			
			//'category_name'    => '',
			//'post_type'        => 'product',
			'post_status'      => 'publish',
			'tax_query'             => array(
				array(
					'taxonomy'      => 'product_cat',
					'field' => 'term_id', //This is optional, as it defaults to 'term_id'
					'terms'         => $selected_categories,
					'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
				)
			),
			'suppress_filters' => false //exclude wpml 
		);
		$posts_array = get_posts( $args ); */
		
		if($ids && !empty($ids))
			foreach($ids as $product)
			{
				if($product['post_type'] == "product")
					$results[] = array('product_id'=>$product['ID'], 'variation_id'=>0);
				else
					$results[] = array('product_id'=>$product['post_parent'], 'variation_id'=>$product['ID']);
			}
		return $results;
	}
}
?>