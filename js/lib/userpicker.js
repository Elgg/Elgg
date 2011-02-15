elgg.userpicker.init = function() {
	// binding autocomplete.
	// doing this as an each so we can past this to functions.
	$('.elgg-input-user-picker').each(function() {
		
		var params = elgg.userpicker.getSearchParams(this);
		
		$(this).autocomplete({
			source: function(request, response) {
				elgg.get('pg/livesearch', {
					data: params,
					dataType: 'json',
					success: function(data) {
						response(data);
					}
				});
			}
			minLength: 2,
			select: elgg.userpicker.addUser
		})
		
		//@todo This seems convoluted
		.data("autocomplete")._renderItem = elgg.userpicker.formatItem;
	});
	

	// changing friends vs all users.
	$('.elgg-user-picker [name=match_on]').live('click', function() {
		// update the extra params for the autocomplete.
		var e = $(this).closest('.elgg-user-picker').find('.elgg-input-user-picker');
		var params = elgg.userpicker.getSearchParams(e);
		e.setOptions({extraParams: params});
		e.flushCache();
	});
};

elgg.userpicker.formatItem = function(ul, item) {
	switch (item.type) {
		case 'user':
		case 'group':
			r = item.icon + item.name + ' - ' + item.desc;
			break;

		default:
			r = item.name + ' - ' + item.desc;
			break;
	}
	
	return $("<li/>")
		.data("item.autocomplete", item)
		.append(r)
		.appendTo(ul);
};

elgg.userpicker.addUser = function(event, ui) {
	var info = ui.item;
	
	// do not allow users to be added multiple times
	if (!(info.guid in elgg.userpicker.userList)) {
		elgg.userpicker.userList[info.guid] = true;
	
		var picker = $(this).closest('.elgg-user-picker');
		var users = picker.find('.elgg-user-picker-entries');
		var internalName = users.find('[type=hidden]').attr('name');
		
		// not sure why formatted isn't.
		var formatted = elgg.userpicker.formatItem(data);

		// add guid as hidden input and to list.
		var li = formatted + ' <div class="delete-button"><a onclick="elgg.userpicker.removeUser(this, ' + info.guid + ')"><strong>X</strong></a></div>'
		+ '<input type="hidden" name="' + internalName + '" value="' + info.guid + '" />';
		$('<li>').html(li).appendTo(users);

		$(this).val('');
	}
}

function elgg.userpicker.removeUser(link, guid) {
	$(link).closest('.elgg-user-picker-entries > li').remove();
}

elgg.userpicker.getSearchParams = function(e) {
	if ($(e).closest('.elgg-user-picker').find('[name=match_on]').attr('checked')) {
		return {'match_on[]': 'friends'};
	} else {
		return {'match_on[]': 'users'};
	}
}

elgg.register_event_handler('init', 'system', elgg.userpicker.init);