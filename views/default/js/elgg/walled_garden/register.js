define(function(require) {
	var $ = require('jquery');
	var elgg = require('elgg'); require('elgg/walled_garden');
	
	elgg.register_hook_handler('init', 'system', function init() {
		$('.registration_link').trigger('click');
	});
	
	return {
		init: init
	};
});