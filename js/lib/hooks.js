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
		return register(name, type, handler, priority, false);
	};

	/**
	 * Registers an asynchronous hook handler with the event system.
	 *
	 * Async handlers will only be called be elgg.trigger_async_hook() and will receive a 5th argument,
	 * which is a function to resolve the value. The handler must eventually call the resolve argument
	 * passing in the new hook value.
	 *
	 * @see elgg.register_hook_handler
	 *
	 * @param {String}   name     The hook name.
	 * @param {String}   type     The hook type.
	 * @param {Function} handler  Handler to call: function(hook, type, params, value, resolve)
	 * @param {Number}   priority Priority to call the event handler
	 * @return {Boolean}
	 */
	elgg.register_async_hook_handler = function(name, type, handler, priority) {
		return register(name, type, handler, priority, true);
	};

	/**
	 * @private
	 */
	function register(name, type, handler, priority, async) {
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
			handler: handler,
			async: async
		});
		return true;
	}
}();

/**
 * Unregister a registered handler for a hook
 *
 * @param {String}   name    The hook name.
 * @param {String}   type    The hook type.
 * @param {Function} handler Handle to remove
 * @return {Boolean} Was the handler removed?
 */
elgg.unregister_hook_handler = function(name, type, handler) {
	elgg.assertTypeOf('string', name);
	elgg.assertTypeOf('string', type);
	elgg.assertTypeOf('function', handler);

	if (!name || !type) {
		return false;
	}

	var hooks = elgg.config.hooks;

	elgg.provide([name, type], hooks);

	if (!hooks[name][type].length) {
		return false;
	}

	var new_list = [],
		ret = false;
	$.each(hooks[name][type], function(i, registration) {
		if (registration.handler === handler) {
			ret = true;
		} else {
			new_list.push(registration);
		}
	});

	hooks[name][type] = new_list;
	return ret;
};

!function(){

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
		return trigger(name, type, params, value, false);
	};

	/**
	 * Emits an asynchronous hook.
	 *
	 * Loops through all registered hooks and calls the handler functions in order.
	 * Every handler function will always be called, regardless of the return value.
	 *
	 * @param {String} name   The hook name.
	 * @param {String} type   The hook type.
	 * @param {Object} params Optional parameters to pass to the handlers
	 * @param {Object} value  Initial value of the return. Can be modified by handlers
	 *
	 * @return {Promise}
	 */
	elgg.trigger_async_hook = function(name, type, params, value) {
		return trigger(name, type, params, value, true);
	};

	/**
	 * Time limit per async handler
	 *
	 * @type {Number}
	 */
	elgg.config.async_handler_timeout = 1000 * 20;

	/**
	 * @private
	 */
	function trigger(name, type, params, value, async) {
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

		if (!async) {
			// only synchronous handlers
			$.each(registrations, function (i, registration) {
				if (registration.async) {
					return;
				}

				var handler_return = registration.handler(name, type, params, value);
				if (!elgg.isNullOrUndefined(handler_return)) {
					value = handler_return;
				}
			});

			return value;
		}

		/**
		 * @param {*} value
		 * @returns {Promise}
		 */
		function call_next_handler(value) {
			var registration = registrations.shift(),
				def = $.Deferred(),
				handler_def = $.Deferred(),
				handler = getAsyncHandler(registration);

			handler_def.done(function (handler_return) {
				if (!elgg.isNullOrUndefined(handler_return)) {
					value = handler_return;
				}

				if (registrations.length) {
					call_next_handler(value)
						.done(def.resolve)
						.fail(def.reject);
				} else {
					def.resolve(value);
				}
			}).fail(def.reject);

			setTimeout(function () {
				if (handler_def.state() === 'pending') {
					def.reject(new Error('Async handler did not resolve()/reject() within time limit'));
				}
			}, elgg.config.async_handler_timeout);

			handler(name, type, params, value, handler_def);

			return def.promise();
		}

		if (!registrations.length) {
			var def = $.Deferred();
			def.resolve(value);
			return def.promise();
		}

		return call_next_handler(value);
	}

	function getAsyncHandler(registration) {
		if (registration.async) {
			return registration.handler;
		}

		return function (h, t, p, v, deferred) {
			try {
				var out = registration.handler(h, t, p, v);
			} catch (e) {
				return deferred.reject(e);
			}
			deferred.resolve(out);
		};
	}
}();

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
