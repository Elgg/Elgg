/**
 * If your plugin has a boot module, it must return an instance of the class defined by
 * this module.
 */
define(function (require) {
	var $ = require('jquery');
	var attachers = [];

	/**
	 * Constructor
	 *
	 * @param {Object} spec Specification object with keys:
	 *
	 *     init: {Function} optional function called in plugin order in the elgg/init module,
	 *
	 *     attachBehavior: {Function} optional function(container_element)
	 *         This function accepts an HTMLElement and should activate elements inside of it. Devs should
	 *         use event delegation wherever possible, but as a backup, this feature can be used to activate
	 *         new elements added to the DOM. See Plugin.attachBehavior()
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
			// setup behaviors in plugin order
			if (spec.attachBehavior) {
				if (typeof spec.attachBehavior !== 'function') {
					throw new Error("Property 'attachBehavior' must be a function");
				}
				attachers.push(spec.attachBehavior);
			}

			if (spec.init) {
				spec.init();
			}
		};
	}

	/**
	 * Attach all available behaviors to elements within the context(s)
	 *
	 * When DOM elements are added to the page, this should be called on them, or a surrounding
	 * container element. See also: elgg/Ajax.attachBehaviors
	 *
	 * @note Behaviors are not ready until all plugin.init's have been called
	 *
	 * @param {HTMLElement|jQuery|String} context DOM element, jQuery collection, or CSS selector
	 */
	Plugin.attachBehaviors = function (context) {
		$(function () {
			$(context).each(function () {
				var that = this;

				$.each(attachers, function (key, func) {
					func(that);
				});
			});
		});
	};

	return Plugin;
});
