define(function(require) {
	var $ = require('jquery');
	var elgg = require('elgg');

	function init() {
		// for group activity widget, set the widget title to the group name
		$(".elgg-widget-edit").on("submit", ".elgg-form-widgets-save-group-activity", function(event) {
			var title = $(this).find('select[name*="params[group_guid]"] option:selected').html();
			$(this).find('input[name*="title"]').val(title);
		});
	}
	
	elgg.register_hook_handler('init', 'system', init);

	return {
		init: init
	};
});
