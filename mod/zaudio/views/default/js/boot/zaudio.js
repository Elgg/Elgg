define(function (require) {
	var $ = require('jquery');
	var Plugin = require('elgg/Plugin');

	return new Plugin({
		addBehavior: function (context) {
			var $targets = $('[data-zaudio-player]', context);
			if ($targets.length) {
				require(['elgg/zaudio'], function (zaudio) {
					$targets.each(function () {
						zaudio(this);
					});
				});
			}
		}
	});
});
