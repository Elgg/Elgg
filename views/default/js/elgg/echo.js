define(function(require, exports, module) {

	//var vsprintf = require('vsprintf');
	var elgg = require('elgg');

	var loader = require('elgg/echo/loader');

	return {
		load: function(name, req, onload, config) {

			loader(name, module.config().lang, function (translation) {
				var f;

				if (null === translation) {
					f = function() {
						return name;
					};
					f.found = false;

				} else {
					f = function(args) {
						return vsprintf(translation, args || []);
					};
					f.found = true;
				}

				onload(f);
			});
		}
	};
});
