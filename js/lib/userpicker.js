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

	$('.elgg-userpicker-remove').live('click', elgg.userpicker.removeUser);
}

/**
 * elgg.userpicker.userList is defined in the input/userpicker view
 */
elgg.userpicker.addUser = function(event, ui) {
	var info = ui.item;

	// do not allow users to be added multiple times
	if (!(info.guid in elgg.userpicker.userList)) {
		elgg.userpicker.userList[info.guid] = true;
		var users = $(this).siblings('.elgg-user-picker-list');
		var li = '<input type="hidden" name="members[]" value="' + info.guid + '" />';
		li += elgg.userpicker.renderUser(info);
		$('<li>').html(li).appendTo(users);
	}

	$(this).val('');
	event.preventDefault();
}

elgg.userpicker.removeUser = function(event) {
	$(this).closest('.elgg-user-picker-list > li').remove();
	event.preventDefault();
}

/**
 * The html in this method has to remain sync'ed with input/userpicker
 */
elgg.userpicker.renderUser = function(info) {

	var deleteLink = "<a href='#' class='elgg-userpicker-remove'>X</a>";

	var html = "<div class='elgg-image-block'>";
	html += "<div class='elgg-image'>" + info.icon + "</div>";
	html += "<div class='elgg-image-alt'>" + deleteLink + "</div>";
	html += "<div class='elgg-body'>" + info.name + "</div>";
	html += "</div";
	
	return html;
}

elgg.userpicker.getSearchParams = function(obj) {
	if (obj.element.siblings('[name=match_on]').attr('checked')) {
		return {'match_on[]': 'friends', 'term' : obj.term};
	} else {
		return {'match_on[]': 'users', 'term' : obj.term};
	}
}

elgg.register_hook_handler('init', 'system', elgg.userpicker.init);