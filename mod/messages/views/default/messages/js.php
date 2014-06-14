elgg.deprecated_notice('Use of elgg.messages is deprecated in favor of the elgg/messages AMD module', '1.9');

// messages plugin toggle
elgg.register_hook_handler('init', 'system', function() {
	$("#messages-toggle").click(function() {
		$('input[type=checkbox]').click();
	});
});
