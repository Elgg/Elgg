//<script>

elgg.provide('elgg.uservalidationbyemail');

elgg.deprecated_notice('uservalidationbyemail.js is deprecated. Use the "elgg/uservalidationbyemail" AMD module', 2.3);

elgg.uservalidationbyemail.init = function() {
	$('#uservalidationbyemail-checkall').click(function() {
		$('#uservalidationbyemail-form .elgg-body').find('input[type=checkbox]').prop('checked', this.checked);
	});

	$('.uservalidationbyemail-submit').click(function(event) {
		var form = $('#uservalidationbyemail-form')[0];
		event.preventDefault();

		// check if there are selected users
		if ($('.elgg-body', form).find('input[type=checkbox]:checked').length < 1) {
			return false;
		}

		// confirmation
		if (!confirm(this.title)) {
			return false;
		}

		form.action = this.href;
		form.submit();
	});
};

elgg.register_hook_handler('init', 'system', elgg.uservalidationbyemail.init);
