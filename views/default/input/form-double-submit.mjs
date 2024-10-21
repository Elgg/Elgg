import 'jquery';

$(document).on('submit', '.elgg-form-prevent-double-submit', function (e) {
	$(this).find('button[type="submit"]').prop('disabled', true);
	
	var $submitter = $(e.originalEvent.submitter);
	if ($submitter.attr('name') !== undefined) {
		$(this).append('<input type="hidden" name="' + $submitter.attr('name') + '" value="' + $submitter.attr('value') + '"/>');
	}
});
