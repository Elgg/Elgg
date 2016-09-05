//<script>

require(['jquery', 'elgg'], function ($, elgg) {
	elgg.register_hook_handler('init', 'system', function () {
		$(document).on('click', '#messages-toggle', function () {
			$('input[type=checkbox]').click();
		});
	});
});
