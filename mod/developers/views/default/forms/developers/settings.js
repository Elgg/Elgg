define(function(require) {
	
	var $ = require('jquery');
	
	$(document).on('change', '.elgg-form-developers-settings select[name="block_email"]', function() {
		if ($(this).val() === 'forward') {
			$('.elgg-form-developers-settings input[name="forward_email"]').closest('.elgg-field').removeClass('hidden');
		} else {
			$('.elgg-form-developers-settings input[name="forward_email"]').closest('.elgg-field').addClass('hidden');
		}
	});
});
