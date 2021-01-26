require(['jquery'], function ($) {
	$(document).on('click', '#messages-toggle', function() {
		$('input[type=checkbox]').click();
	});
});
