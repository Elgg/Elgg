import 'jquery';

$(document).on('change', '#developers-settings select[name="params[block_email]"]', function() {
	if ($(this).val() === 'forward') {
		$('#developers-settings input[name="params[forward_email]"]').closest('.elgg-field').removeClass('hidden');
	} else {
		$('#developers-settings input[name="params[forward_email]"]').closest('.elgg-field').addClass('hidden');
	}
});
