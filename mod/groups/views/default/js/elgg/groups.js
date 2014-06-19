define(function(require) {
	var elgg = require('elgg');
	var $ = require('jquery');
	
	// this adds a class to support IE8 and older
	elgg.register_hook_handler('init', 'system', function init() {
		// jQuery uses 0-based indexing
		$('#groups-tools').children('li:even').addClass('odd');
	});

	elgg.ui.registerTogglableMenuItems('feature', 'unfeature');
	
	return {
		init: init
	};
});