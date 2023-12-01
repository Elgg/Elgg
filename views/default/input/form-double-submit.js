define(['jquery'], function ($) {
	$(document).on('submit', '.elgg-form-prevent-double-submit', function (e) {
		$(this).find('button[type="submit"]').prop('disabled', true);
	});
});
