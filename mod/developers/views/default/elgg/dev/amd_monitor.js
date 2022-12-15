!function() {
	// http://stackoverflow.com/a/19043564/3779
	var defined_modules = require.s.contexts._.defined;

	define(function (require) {
		var $ = require('jquery');
		var i18n = require('elgg/i18n');

		var known = {};
		var count = 0;

		function update() {
			$.each(defined_modules, function (name, val) {
				if (!known[name]) {
					known[name] = 1;
					count++;
					console.log(count + ' ' + i18n.echo('developers:amd') + '(' + name + ')', val);
				}
			});
		}

		if (typeof Object.observe === 'function') {
			Object.observe(defined_modules, update);
		} else {
			setInterval(update, 1000);
		}
		
		update();
	});
}();
