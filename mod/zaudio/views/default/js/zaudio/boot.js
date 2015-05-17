define(function (require) {
	var $ = require('jquery');

	return {
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
	};
});