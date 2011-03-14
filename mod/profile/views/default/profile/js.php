elgg.register_hook_handler('init', 'system', function() {
	$('#elgg-widget-col-1').css('min-height', $('.profile').outerHeight(true) + 1);
});