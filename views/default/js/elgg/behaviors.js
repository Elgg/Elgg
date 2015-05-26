/**
 * Builds up a function to transform content delivered in or added to the page. E.g. allow
 * WYSIWYG editors to transform textareas.
 */
define(function (require) {
	var $ = require('jquery');
	var attachers = [];

	return {
		/**
		 * Add a function used to attach behavior within in a DOM context
		 *
		 * Plugins implicitly provide this by having their $plugin_id/boot module
		 * return an Object with a function called addBehavior.
		 *
		 * @param {Function} func The function
		 * @access private
		 */
		addBehavior: function (func) {
			if (typeof func !== 'function') {
				throw new Error("addBehavior: func is not a function")
			}
			attachers.push(func);
		},

		/**
		 * Attach all available behaviors to elements within the context(s)
		 *
		 * If you add DOM elements to the page, this should be called on them, or an
		 * element surrounding them.
		 *
		 * @param {HTMLElement|jQuery} context DOM element or jQuery collection
		 */
		attach: function (context) {
			$(context).each(function () {
				var that = this;

				$.each(attachers, function (key, func) {
					func(that);
				});
			});
		}
	};
});
