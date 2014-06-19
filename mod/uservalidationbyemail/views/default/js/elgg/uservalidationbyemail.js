define(function(require) {
	var elgg = require('elgg');
	var $ = require('jquery');
	
	elgg.register_hook_handler('init', 'system', function init() {
		$('#uservalidationbyemail-checkall').click(function() {
			var checked = $(this).attr('checked') == 'checked';
			$('#uservalidationbyemail-form .elgg-body').find('input[type=checkbox]').attr('checked', checked);
		});

		$('.uservalidationbyemail-submit').click(function(event) {
			var $form = $('#uservalidationbyemail-form');
			event.preventDefault();

			// check if there are selected users
			if ($('#uservalidationbyemail-form .elgg-body').find('input[type=checkbox]:checked').length < 1) {
				return false;
			}

			// confirmation
			if (!confirm($(this).attr('title'))) {
				return false;
			}

			$form.attr('action', $(this).attr('href')).submit();
		});
	});

	return {
		init: init
	};
});
