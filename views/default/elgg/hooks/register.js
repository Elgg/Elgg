/**
 * Registers a hook handler with the event system.
 *
 * The special keyword "all" can be used for either the name or the type or both
 * and means to call that handler for all of those hooks.
 *
 * Note that handlers registering for instant hooks will be executed immediately if the instant
 * hook has been previously triggered.
 *
 * @param {String}   name     Name of the plugin hook to register for
 * @param {String}   type     Type of the event to register for
 * @param {Function} handler  Handle to call
 * @param {Number}   priority Priority to call the event handler
 * @return {Boolean}
 */
define(function(require) {
	var elgg = require("elgg");

	return function (name, type, handler, priority) {
		if (elgg._plugins_booted && window.console) {
			console.warn("This hook registration may be too late. Register handlers in a plugin boot" +
				" module for best results.");
		}

		return elgg._register_hook_handler(name, type, handler, priority);
	};
});
