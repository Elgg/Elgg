// TODO: move all elgg.ui.widget code here in 3.0
define(function (require) {
	var elgg = require('elgg');
	var $ = require('jquery');

	var w = $.extend({}, elgg.ui.widgets);
	w.init = function () {
		elgg.ui.widgets.init._amd = true;
		elgg.ui.widgets.init();
	};

	return w;
});
