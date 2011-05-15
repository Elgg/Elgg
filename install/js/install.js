
$(function() {
	// prevent double-submission of forms
	$('form').submit(function() {
		if ($(this).data('submitted')) {
			return false;
		}
		$(this).data('submitted', true);
		return true;
	});

	// toggle the disable attribute of text box based on checkbox
	$('.elgg-combo-checkbox').click(function() {
		if ($(this).is(':checked')) {
			$(this).prev().attr('disabled', true);
			$(this).prev().val('');
		} else {
			$(this).prev().attr('disabled', false);
		}
	});
});
