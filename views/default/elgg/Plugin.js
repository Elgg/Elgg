/**
 * If your plugin has a boot module, it must return an instance of the class defined by
 * this module.
 */
define(function (require) {

	/**
	 * Constructor
	 *
	 * @param {Object} spec Specification object with keys:
	 *
	 *     init: {Function} optional function called in plugin order in the elgg/init module,
	 *
	 * @constructor
	 */
	function Plugin(spec) {
		spec = spec || {};

		/**
		 * This is called by elgg/init to initialize the plugin. Do not use.
		 *
		 * @access private
		 * @internal
		 */
		this._init = function () {
			if (spec.init) {
				spec.init();
			}
		};
	}

	return Plugin;
});

