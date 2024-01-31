import 'jquery';

$(document).on('change', '.elgg-form-discussion-save select[name="container_guid"]', function () {
	var selected_text = $(this).find('option:selected').text();
	
	var $access_field = $('.elgg-form-discussion-save .discussion-access');
	if (!selected_text) {
		// enable access
		$access_field.show().find('.elgg-input-access').prop('disabled', false);
	} else {
		// group selected, disable access
		$access_field.hide().find('.elgg-input-access').prop('disabled', true);
	}
});
