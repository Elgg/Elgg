//<script>
// messages plugin toggle
require(['jquery', 'elgg/hooks/register'], function($, register) {
	register('init', 'system', function () {
		$("#messages-toggle").click(function () {
			$('input[type=checkbox]').click();

		});
	});
});
