/*
 * Javascript hook interface
 */

elgg.provide('elgg.config.hooks');

/**
 * Registers an hook handler with the event system.
 *
 * The special keyword "all" can be used for either the name or the type or both
 * and means to call that handler for all of those hooks.
 *
 * @param {String}   name     Name of the plugin hook to register for
 * @param {String}   type     Type of the event to register for
 * @param {Function} handler  Handle to call
 * @param {Number}   priority Priority to call the event handler
 * @return {Bool}
 */
elgg.register_hook_handler = function(name, type, handler, priority) {
	elgg.assertTypeOf('string', name);
	elgg.assertTypeOf('string', type);
	elgg.assertTypeOf('function', handler);

	if (!name || !type) {
		return false;
	}

	var priorities =  elgg.config.hooks;

	elgg.provide(name + '.' + type, priorities);

	if (!(priorities[name][type] instanceof elgg.ElggPriorityList)) {
		priorities[name][type] = new elgg.ElggPriorityList();
	}

	return priorities[name][type].insert(handler, priority);
};

/**
 * Emits a hook.
 *
 * Loops through all registered hooks and calls the handler functions in order.
 * Every handler function will always be called, regardless of the return value.
 *
 * @warning Handlers take the same 4 arguments in the same order as when calling this function.
 * This is different to the PHP version!
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
 * @return {Bool}
 */
elgg.trigger_hook = function(name, type, params, value) {
	elgg.assertTypeOf('string', name);
	elgg.assertTypeOf('string', type);

	// default to true if unpassed
	value = value || true;

	var hooks = elgg.config.hooks,
		tempReturnValue = null,
		returnValue = value,
		callHookHandler = function(handler) {
			tempReturnValue = handler(name, type, params, value);
		};

	elgg.provide(name + '.' + type, hooks);
	elgg.provide('all.' + type, hooks);
	elgg.provide(name + '.all', hooks);
	elgg.provide('all.all', hooks);

	[	hooks[name][type],
		hooks['all'][type],
		hooks[name]['all'],
		hooks['all']['all']
	].every(function(handlers) {
		if (handlers instanceof elgg.ElggPriorityList) {
			handlers.forEach(callHookHandler);
		}
		return true;
	});

	return (tempReturnValue !== null) ? tempReturnValue : returnValue;
};