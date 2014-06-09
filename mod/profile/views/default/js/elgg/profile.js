define(function(require) {
	var $ = require('jquery');
	var elgg = require('elgg');

	// force the first column to at least be as large as the profile box in cols 2 and 3
	// we also want to run before the widget init happens so priority is < 500
	elgg.register_hook_handler('init', 'system', function init() {
		// only do this on the profile page's widget canvas.
		if ($('.profile').length) {
			$('#elgg-widget-col-1').css('min-height', $('.profile').outerHeight(true) + 1);
		}
	}, 400);

	return {
		init: init
	};
});