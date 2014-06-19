define(function(require) {
	var elgg = require('elgg');
	var $ = require('jquery');
	
	elgg.register_hook_handler('init', 'system', function init() {
		$("#messages-toggle").click(function() {
			$('input[type=checkbox]').click();
		});
	});
	
	return {
		init: init
	};
});
