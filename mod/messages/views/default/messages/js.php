
// messages plugin toggle
elgg.register_hook_handler('init', 'system', function() {
	$("#messages-toggle").click(function() {
		$('input[type=checkbox]').click();
	});
});
