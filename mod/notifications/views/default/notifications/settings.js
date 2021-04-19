define(['jquery'], function($) {
	// prevent email and delayed email from being enabled at the same time
	$(document).on('change', '.elgg-notifications-settings .elgg-input-checkbox:checked', function() {
		if ($(this).val() !== 'delayed_email' && $(this).val() !== 'email') {
			return;
		}
		
		var $methods = $(this).closest('.elgg-field-input');
		if ($(this).val() === 'delayed_email') {
			$methods.find('.elgg-input-checkbox[value="email"]').prop('checked', false);
		} else {
			$methods.find('.elgg-input-checkbox[value="delayed_email"]').prop('checked', false);
		}
	});
});
