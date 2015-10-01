
//<script>
elgg.provide('elgg.dashboard');

elgg.dashboard.init = function() {
	require(['jquery'], function($) {
		$(function() {
			// for group activity widget, set the widget title to the group name
			$(".elgg-widget-edit").on("submit", ".elgg-form-widgets-save-group-activity", function(event) {
				var title = $(this).find('select[name*="params[group_guid]"] option:selected').html();
				$(this).find('input[name*="title"]').val(title);
			});
		});
	});
};

require(['elgg/hooks/register'], function(register) {
	register('init', 'system', elgg.dashboard.init);
});
