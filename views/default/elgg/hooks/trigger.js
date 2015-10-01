/**
 * Emits a hook.
 *
 * Loops through all registered hooks and calls the handler functions in order.
 * Every handler function will always be called, regardless of the return value.
 *
 * @warning Handlers take the same 4 arguments in the same order as when calling this function.
 * This is different from the PHP version!
 *
 * @note Instant hooks do not support params or values.
 *
 * Hooks are called in this order:
 *	specifically registered (event_name and event_type match)
 *	all names, specific type
 *	specific name, all types
 *	all names, all types
 *
 * @param {String} name   Name of the hook to emit
 * @param {String} type   Type of the hook to emit
 * @param {Object} params Optional parameters to pass to the handlers
 * @param {Object} value  Initial value of the return. Can be mangled by handlers
 *
 * @return {Boolean}
 */
define(function(require) {
	require("elgg/booted");

	return require("elgg")._trigger_hook;
});
