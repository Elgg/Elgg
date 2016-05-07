define(function(require) {
	var elgg = require('elgg');
	require('elgg/init');
	elgg.ui.registerTogglableMenuItems('feature', 'unfeature');
});