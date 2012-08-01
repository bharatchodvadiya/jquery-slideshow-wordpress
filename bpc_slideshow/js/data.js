
function delete1(id,name1)
{	
	jQuery.post(ajax_object.ajaxurl, {
			action: 'ajax_action',
			uid: id,
			iname:name1
		}, function(data) {
			location.reload();
		});
}
