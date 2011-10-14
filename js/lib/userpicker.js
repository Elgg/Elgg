elgg.provide('elgg.userpicker');

elgg.userpicker.init = function() {
	
	// binding autocomplete.
	// doing this as an each so we can pass this to functions.
	$('.elgg-input-user-picker').each(function() {

		$(this).autocomplete({
			source: function(request, response) {

				var params = elgg.userpicker.getSearchParams(this);
				
				elgg.get('livesearch', {
					data: params,
					dataType: 'json',
					success: function(data) {
						response(data);
					}
				});
			},
			minLength: 2,
			html: "html",
			select: elgg.userpicker.addUser
		})
	});
};

/**
 * elgg.userpicker.userList is defined in the input/userpicker view
 */
elgg.userpicker.addUser = function(event, ui) {
	var info = ui.item;

	// do not allow users to be added multiple times
	if (!(info.guid in elgg.userpicker.userList)) {
		elgg.userpicker.userList[info.guid] = true;
		var users = $(this).siblings('.elgg-user-picker-entries');
		var li = '<input type="hidden" name="members[]" value="' + info.guid + '" />';
		$('<li>').html(li).appendTo(users);
	}

	$(this).val('');
	event.preventDefault();
};

elgg.userpicker.removeUser = function(link, guid) {
	$(link).closest('.elgg-user-picker-entries > li').remove();
};

elgg.userpicker.getSearchParams = function(e) {
	if (e.element.siblings('[name=match_on]').attr('checked')) {
		return {'match_on[]': 'friends', 'term' : e.term};
	} else {
		return {'match_on[]': 'users', 'term' : e.term};
	}
}

elgg.register_hook_handler('init', 'system', elgg.userpicker.init);