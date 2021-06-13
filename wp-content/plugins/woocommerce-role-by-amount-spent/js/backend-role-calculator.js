var wcra_max_customers = wcra_current_customer_index = 0;
var wcra_current_customer_ids = new Array();
var wcra_max_requests = 20;
var wcra_user_updated = 0;
jQuery(document).ready(function()
{
	jQuery(document).on('click', '#recompute_button', wcra_recompute_user_roles);
	
	Number.prototype.toFixedDown = function(digits) {
		var re = new RegExp("(\\d+\\.\\d{" + digits + "})(\\d)"),
			m = this.toString().match(re);
		return m ? parseFloat(m[1]) : this.valueOf();
	};
});
function wcra_start_computation_animation()
{
	jQuery('#recompute_button').hide();
	jQuery('#progress-bar-container').fadeIn();
	jQuery('#progress-bar').animate({'width':"0%"});
	jQuery('#notice-box').html("");
}
function wcra_end_computation_animation()
{
	jQuery('#recompute_button').show();
	jQuery('#notice-box').html("Done, updated: "+wcra_user_updated+" users");
}
function wcra_update_progress_bar_animation()
{
	jQuery('#progress-bar').animate({'width': ((wcra_current_customer_index/wcra_max_customers)*100)+"%"});
	jQuery('#notice-box').html(((wcra_current_customer_index/wcra_max_customers)*100).toFixedDown(1)+"%");
}
function wcra_reset_variables()
{
	 wcra_current_customer_ids = new Array();
	 wcra_current_customer_index = wcra_user_updated = 0;
}

function wcra_recompute_user_roles(event)
{
	wcra_start_computation_animation();
	
	//Get number of users
	var formData = new FormData();
	formData.append('action', 'wcra_get_customers_ids');
	
	jQuery.ajax({
		url: ajaxurl, 
		type: 'POST',
		data: formData,
		async: true,
		success: function (data) {			
			wcra_reset_variables();
			//console.log(data);
			if(data != 0)
			{
				wcra_current_customer_ids = data.split(",");
				wcra_max_customers =  wcra_current_customer_ids.length; //Math.ceil(data/wcoei_per_page);
				
				/* for(var i = 0 ; i < wcra_max_requests && i < wcra_max_customers; i++)
				{
					wcra_recompute_single_user_role();
					wcra_current_customer_index++;
				} */
				wcra_recompute_single_user_role();
			}
			else
				wcra_end_computation();			
		},
		error: function (data,error) 
		{
			//wcra_check_error_on_response(data);
		},
		cache: false,
		contentType: false, 
		processData: false
	});
}
function wcra_recompute_single_user_role()
{
	/* console.log(wcra_max_customers);
	console.log(wcra_current_customer_ids);
	return; */
	
	var formData = new FormData();
	var ids_to_send = new Array();
	formData.append('action', 'wcra_recompute_user_role_by_index');
	for(var i = 0 ; i < wcra_max_requests && typeof wcra_current_customer_ids[wcra_current_customer_index] !== 'undefined'; i++)
	{
		ids_to_send.push(wcra_current_customer_ids[wcra_current_customer_index]);
		wcra_current_customer_index++;
	}
	formData.append('indexes', JSON.stringify(ids_to_send));
	//formData.append('index', wcra_current_customer_ids[wcra_current_customer_index]);
	
	
	jQuery.ajax({
		url: ajaxurl, 
		type: 'POST',
		data: formData,
		async: true,
		success: function (data) {			
			//wcra_current_customer_index++;
			wcra_update_progress_bar_animation();
			if(!isNaN(data))
				wcra_user_updated += parseInt(data);
			/* else
				console.log(data); */
			if(wcra_current_customer_index < wcra_max_customers)
			{
				wcra_recompute_single_user_role();
			}
			else
			{
				wcra_end_computation();
			}
		},
		error: function (data,error) 
		{
			//wcra_check_error_on_response(data);
		},
		cache: false,
		contentType: false, 
		processData: false
	});
}

function wcra_end_computation()
{
	wcra_end_computation_animation();
}