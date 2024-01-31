import 'jquery';

$('[name=require_admin_validation]').click(function() {
	if ($(this).prop('checked')) {
		$('.elgg-admin-users-admin-validation-notification').show();
	} else {
		$('.elgg-admin-users-admin-validation-notification').hide();
	}
});

$('[name=simplecache_enabled]').click(function() {
	// when the checkbox is disabled, do not toggle the compression checkboxes
	var names = ['simplecache_minify_js', 'simplecache_minify_css', 'cache_symlink_enabled'];
	for (var i = 0; i < names.length; i++) {
		var $input = $('input[type!=hidden][name="' + names[i] + '"]');
		if ($input.length) {
			$input.attr('disabled', !$input.prop('disabled'));
			$input.closest('.elgg-field').toggleClass('elgg-field-disabled');
		}
	}
});
