//<script>

require(['jquery', 'elgg'], function ($, elgg) {
	elgg.register_hook_handler('init', 'system', function () {
		// for group activity widget, set the widget title to the group name
		$(".elgg-widget-edit").on("submit", ".elgg-form-widgets-save-group-activity", function(event) {
			var title = $(this).find('select[name*="params[group_guid]"] option:selected').html();
			$(this).find('input[name*="title"]').val(title);
		});
	});
});
