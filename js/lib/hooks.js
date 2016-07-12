/*
 * Javascript hook interface
 */

elgg.provide('elgg.config.hooks');
elgg.provide('elgg.config.instant_hooks');
elgg.provide('elgg.config.triggered_hooks');

!function() {
	// counter for tracking registration order
	var index = 0;

	/**
	 * Registers a hook handler with the event system.
	 *
	 * For best results, depend on the elgg/ready module, so plugins will have been booted.
	 *
	 * The special keyword "all" can be used for either the name or the type or both
	 * and means to call that handler for all of those hooks.
	 *
	 * Note that handlers registering for instant hooks will be executed immediately if the instant
	 * hook has been previously triggered.
	 *
	 * @param {String}   name     The hook name.
	 * @param {String}   type     The hook type.
	 * @param {Function} handler  Handler to call: function(hook, type, params, value)
	 * @param {Number}   priority Priority to call the event handler
	 * @return {Boolean}
	 */
	elgg.register_hook_handler = function(name, type, handler, priority) {
		elgg.assertTypeOf('string', name);
		elgg.assertTypeOf('string', type);
		elgg.assertTypeOf('function', handler);

		if (!name || !type) {
			return false;
		}

		var hooks = elgg.config.hooks;

		elgg.provide([name, type], hooks);

		if (!hooks[name][type].length) {
			hooks[name][type] = [];
		}

		// call if instant and already triggered.
		if (elgg.is_instant_hook(name, type) && elgg.is_triggered_hook(name, type)) {
			handler(name, type, null, null);
		}

		hooks[name][type].push({
			priority: priority,
			index: index++,
			handler: handler
		});
		return true;
	}
}();

/**
 * Emits a synchronous hook, calling only synchronous handlers
 *
 * Loops through all registered hooks and calls the handler functions in order.
 * Every handler function will always be called, regardless of the return value.
 *
 * @warning Handlers take the same 4 arguments in the same order as when calling this function.
 * This is different from the PHP version!
 *
 * @note Instant hooks do not support params or values.
 *
 * Hooks are called in priority order.
 *
 * @param {String} name   The hook name.
 * @param {String} type   The hook type.
 * @param {Object} params Optional parameters to pass to the handlers
 * @param {Object} value  Initial value of the return. Can be modified by handlers
 *
 * @return {*}
 */
elgg.trigger_hook = function(name, type, params, value) {
	elgg.assertTypeOf('string', name);
	elgg.assertTypeOf('string', type);

	// mark as triggered
	elgg.set_triggered_hook(name, type);

	// default to null if unpassed
	value = !elgg.isNullOrUndefined(value) ? value : null;

	var hooks = elgg.config.hooks,
		registrations = [],
		push = Array.prototype.push;

	elgg.provide([name, type], hooks);
	elgg.provide(['all', type], hooks);
	elgg.provide([name, 'all'], hooks);
	elgg.provide(['all', 'all'], hooks);

	if (hooks[name][type].length) {
		if (name !== 'all' && type !== 'all') {
			push.apply(registrations, hooks[name][type]);
		}
	}
	if (hooks['all'][type].length) {
		if (type !== 'all') {
			push.apply(registrations, hooks['all'][type]);
		}
	}
	if (hooks[name]['all'].length) {
		if (name !== 'all') {
			push.apply(registrations, hooks[name]['all']);
		}
	}
	if (hooks['all']['all'].length) {
		push.apply(registrations, hooks['all']['all']);
	}

	registrations.sort(function (a, b) {
		// priority first
		if (a.priority < b.priority) {
			return -1;
		}
		if (a.priority > b.priority) {
			return 1;
		}

		// then insertion order
		return (a.index < b.index) ? -1 : 1;
	});

	// only synchronous handlers
	$.each(registrations, function (i, registration) {
		var handler_return = registration.handler(name, type, params, value);
		if (!elgg.isNullOrUndefined(handler_return)) {
			value = handler_return;
		}
	});

	return value;
};

/**
 * Registers a hook as an instant hook.
 *
 * After being trigger once, registration of a handler to an instant hook will cause the
 * handle to be executed immediately.
 *
 * @note Instant hooks must be triggered without params or defaults. Any params or default
 * passed will *not* be passed to handlers executed upon registration.
 *
 * @param {String} name The hook name.
 * @param {String} type The hook type.
 * @return {Number} integer
 */
elgg.register_instant_hook = function(name, type) {
	elgg.assertTypeOf('string', name);
	elgg.assertTypeOf('string', type);

	return elgg.push_to_object_array(elgg.config.instant_hooks, name, type);
};

/**
 * Is this hook registered as an instant hook?
 *
 * @param {String} name The hook name.
 * @param {String} type The hook type.
 */
elgg.is_instant_hook = function(name, type) {
	return elgg.is_in_object_array(elgg.config.instant_hooks, name, type);
};

/**
 * Records that a hook has been triggered.
 *
 * @param {String} name The hook name.
 * @param {String} type The hook type.
 */
elgg.set_triggered_hook = function(name, type) {
	return elgg.push_to_object_array(elgg.config.triggered_hooks, name, type);
};

/**
 * Has this hook been triggered yet?
 *
 * @param {String} name The hook name.
 * @param {String} type The hook type.
 */
elgg.is_triggered_hook = function(name, type) {
	return elgg.is_in_object_array(elgg.config.triggered_hooks, name, type);
};

elgg.register_instant_hook('init', 'system');
elgg.register_instant_hook('ready', 'system');
elgg.register_instant_hook('boot', 'system');
