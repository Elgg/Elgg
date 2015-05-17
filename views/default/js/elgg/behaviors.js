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
		addAttacher: function (func) {
			attachers.push(func);
		},

		/**
		 * Attach all available behaviors to elements within the DOM context
		 *
		 * If you add DOM elements to the page, this should be called on them, or an
		 * element surrounding them.
		 *
		 * @param {HTMLElement} context
		 */
		attach: function (context) {
			$.each(attachers, function (key, func) {
				func(context);
			});
		}
	};
});
