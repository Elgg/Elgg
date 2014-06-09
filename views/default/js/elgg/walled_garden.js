define(function(require) {
	var elgg = require('elgg');
	var $ = require('jquery');
	
	elgg.register_hook_handler('init', 'system', function init() {
		$('.registration_link').trigger('click');
	});	
	
	return {
		init: init
	};
});
