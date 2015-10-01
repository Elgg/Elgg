/*
 * Javascript hook interface
 */

elgg.provide('elgg.config.hooks');
elgg.provide('elgg.config.instant_hooks');
elgg.provide('elgg.config.triggered_hooks');

/**
 * @private
 */
elgg._register_hook_handler = function(name, type, handler, priority) {
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

	// call if instant and already triggered.
	if (elgg.is_instant_hook(name, type) && elgg.is_triggered_hook(name, type)) {
		handler(name, type, null, null);
	}

	return priorities[name][type].insert(handler, priority);
};

/**
 * Registers a hook handler with the event system.
 *
 * @deprecated Use the elgg/hooks/register module in a boot module
 */
elgg.register_hook_handler = function(name, type, handler, priority) {
	if (name === 'boot' && type === 'system') {
		elgg.deprecated_notice("The hook [boot, system] is deprecated. Use [init, system] in a plugin boot" +
			" module", "2.1");
	} else {
		elgg.deprecated_notice("elgg.register_hook_handler is deprecated. Use the elgg/hooks/register module" +
			" in your plugin boot module", "2.1");
	}

	return elgg._register_hook_handler(name, type, handler, priority);
};

/**
 * @private
 */
elgg._trigger_hook = function(name, type, params, value) {
	elgg.assertTypeOf('string', name);
	elgg.assertTypeOf('string', type);

	// mark as triggered
	elgg.set_triggered_hook(name, type);

	// default to null if unpassed
	value = !elgg.isNullOrUndefined(value) ? value : null;

	var hooks = elgg.config.hooks,
		tempReturnValue = null,
		returnValue = value,
		callHookHandler = function(handler) {
			tempReturnValue = handler(name, type, params, returnValue);
			if (!elgg.isNullOrUndefined(tempReturnValue)) {
				returnValue = tempReturnValue;
			}
		};

	elgg.provide(name + '.' + type, hooks);
	elgg.provide('all.' + type, hooks);
	elgg.provide(name + '.all', hooks);
	elgg.provide('all.all', hooks);

	var hooksList = [];
	
	if (name != 'all' && type != 'all') {
		hooksList.push(hooks[name][type]);
	}

	if (type != 'all') {
		hooksList.push(hooks['all'][type]);
	}

	if (name != 'all') {
		hooksList.push(hooks[name]['all']);
	}

	hooksList.push(hooks['all']['all']);

	hooksList.every(function(handlers) {
		if (handlers instanceof elgg.ElggPriorityList) {
			handlers.forEach(callHookHandler);
		}
		return true;
	});

	return returnValue;
};

/**
 * Emits a hook.
 *
 * @deprecated Use the elgg/hooks/trigger module
 */
elgg.trigger_hook = function(name, type, params, value) {
	elgg.deprecated_notice("elgg.trigger_hook is deprecated. Use the elgg/hooks/trigger module", "2.1");
	return elgg._trigger_hook(name, type, params, value);
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
