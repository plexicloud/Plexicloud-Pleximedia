function update_player_height(method)
{
	var theForm = document.getElementById('adminForm');
	
	var radioObj = theForm.aspect_constraint;
	var radioLength = radioObj.length;
	
	for(var i = 0; i < radioLength; i++) {
		if(radioObj[i].checked) {
			var aspect_constraint = radioObj[i].value;
		}
	}
	
	/* Determine our multiplier based on our aspect ratio */
		if (method == 'width') {
			var multiplier = 0.75;
			
			if (aspect_constraint == '16x9') {
				multiplier = 0.5625;
			}
			
			var width = theForm.video_player_width.value;
			width = width.replace(/[^0-9]/g, '');
			theForm.video_player_width.value = width;
			
			var height = Math.round(width *  multiplier);
			
			theForm.video_player_height.value = height;
		} 
		if (method == 'height') {
			var multiplier = 1.3;
			
			if (aspect_constraint == '16x9') {
				multiplier = 1.8;
			}
			
			
			var height = theForm.video_player_height.value;
			height = height.replace(/[^0-9]/g, '');
			theForm.video_player_height.value = height;
			
			var width = Math.round(height *  multiplier);
			
			theForm.video_player_width.value = width;
		}
}
function edit_category()
{
	/* Do Something */
	var theForm = document.getElementById('adminForm');
	
	if (theForm.categories.options[theForm.categories.selectedIndex].value == "0") {
		alert('You must select a category first');
	} else {
		theForm.task.value = 'do_edit_category';
		theForm.edit_category_id.value = theForm.categories.options[theForm.categories.selectedIndex].value;
		theForm.category_name.value = theForm.categories.options[theForm.categories.selectedIndex].text;
		document.getElementById('add_cat').innerHTML = "Edit a Category";
	}
}

function jomtube_verify_admin_client_form()
{
	var theForm = document.getElementById('adminForm');
	
	var isValid = true;
	var message = "";
	
	if ( (theForm.pass.value != theForm.passConfirm.value) || theForm.pass.value == "")
	{
		message += 'Your passwords do not match.\n';
		isValid = false;
	}
	
	if (theForm.user_name.value == "")
	{
		message += 'You must enter a user name.\n';
		isValid = false;
	}
	
	if (theForm.infindomain.value == "")
	{
		message += 'You must enter a domain.\n';
		isValid = false;
	}
	
	if (message != "")
	{
		alert(message);
	}
	
	return isValid;
}

function delete_category()
{
	if (confirm("Are you sure you want to delete this category?")) {
		/* Do something */
		var theForm = document.getElementById('adminForm');
		theForm.task.value = 'do_delete_category';
		theForm.submit();
	}
}

function featured_reorder_item(id, rank, updown)
{
	var theForm = document.getElementById('adminForm');
	
	theForm.featured_id.value = id;
	theForm.featured_order.value = rank;
	theForm.order_method.value = updown;
	theForm.task.value = 'do_reorder_featured_video';
	theForm.submit();
}

function featured_remove_item(id)
{
	var theForm = document.getElementById('adminForm');
	
	if (confirm('Are you sure you want to remove this featured video?')) {
		theForm.featured_id.value = id;
		theForm.task.value = 'do_remove_featured_video';
		theForm.submit();		
	}
}