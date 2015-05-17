/**
 * If your plugin has a boot module, it must return an instance of the class defined by
 * this module.
 */
define(function (require) {
	var $ = require('jquery');

	/**
	 * Constructor
	 *
	 * @param {Object} spec Specification object with keys:
	 *
	 *     exports: {Object} optional set of values to you want to make available
	 *     init: {Function} optional function called in plugin order during the elgg/booted module,
	 *
	 * @constructor
	 */
	function Plugin(spec) {
		var expected_keys = {
			exports: 1,
			init: 1
		};

		spec = spec || {};
		$.each(spec, function (key, val) {
			if (!expected_keys[key]) {
				console.error("The key '" + key + "' is unrecognized. Is this a typo?");
			}
		});

		/**
		 * Get the exports of the boot module, if any
		 *
		 * @returns {*}
		 */
		this.getExports = function () {
			return spec.exports || {};
		};

		/**
		 * This is called by Elgg to initialize the plugin. Do not use.
		 *
		 * @access private
		 * @internal
		 */
		this._init = function () {
			if ($.isFunction(spec.init)) {
				spec.init();
			}
		};
	}

	return Plugin;
});