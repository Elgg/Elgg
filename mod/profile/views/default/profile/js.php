elgg.register_hook_handler('init', 'system', function() {
	// only do this on the profile page's widget canvas.
	if ($('.profile').length) {
		$('#elgg-widget-col-1').css('min-height', $('.profile').outerHeight(true) + 1);
	}
});