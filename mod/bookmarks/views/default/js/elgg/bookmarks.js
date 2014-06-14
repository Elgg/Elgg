define(function(require) {
	var $ = require('jquery');
	var elgg = require('elgg');

	function init() {
		// append the title to the url
		var title = document.title;
		var e = $('a.elgg-bookmark-page');
		var link = e.attr('href') + '&title=' + encodeURIComponent(title);
		e.attr('href', link);
	};

	elgg.register_hook_handler('init', 'system', init);

	return {
		init: init
	};
});
