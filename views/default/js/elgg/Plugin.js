/**
 * If your plugin has a boot module, that module must return an instance of the class defined by
 * this module.
 */
define(function (require) {
	var $ = require('jquery');
	var behaviors = require('elgg/behaviors');

	/**
	 *
	 * @param {Object} spec Specification object with keys:
	 *
	 *     addBehavior: {Function} optional function which is handed a DOM element as context and should
	 *                             attach behaviors to elements within.
	 *     exports: {Object} optional set of values to you want to make available
	 *
	 * @constructor
	 */
	function Plugin(spec) {
		var expected_keys = {
			addBehavior: 1,
			exports: 1
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
			if (spec.addBehavior) {
				behaviors.addBehavior(spec.addBehavior);
			}
		};
	}

	return Plugin;
});