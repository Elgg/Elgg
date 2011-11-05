
elgg.provide('elgg.uservalidationbyemail');

elgg.uservalidationbyemail.init = function() {
	$('.uservalidationbyemail-unvalidated-users-checkall').click(function() {
		checked = $(this).attr('checked') == 'checked';
		$('form#unvalidated-users').find('input[type=checkbox]').attr('checked', checked);
	});

	$('.uservalidationbyemail-unvalidated-users-bulk-post').click(function(event) {
		$form = $('form#unvalidated-users');
		event.preventDefault();

		// check if there are selected users
		if ($form.find('input[type=checkbox]:checked').length < 1) {
			return false;
		}

		// confirmation
		if (!confirm($(this).attr('title'))) {
			return false;
		}

		$form.attr('action', $(this).attr('href')).submit();
	});
};

elgg.register_hook_handler('init', 'system', elgg.uservalidationbyemail.init);
