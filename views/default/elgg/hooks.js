define(['jquery', 'elgg'], function ($, elgg) {
	// counter for tracking registration order
	var index = 0;
	
	// array that holds all hook registrations
	var hooks = {};
		
	/**
	 * Prepares the hook registration array for a given hook
	 *
	 * @param {String} name The hook name.
	 * @param {String} type The hook type.
	 */
	function prepareHook(name, type) {
		hooks[name] = hooks[name] || [];
		hooks[name][type] = hooks[name][type] || [];
		
		hooks['all'] = hooks['all'] || [];
		hooks[name]['all'] = hooks[name]['all'] || [];
		hooks['all'][type] = hooks['all'][type] || [];
		hooks['all']['all'] = hooks['all']['all'] || [];
	}
	
	return {
		
		/**
		 * Helper function to reset all registered hooks. Mainly used for testing purposes.
		 */
		reset: function() {
			hooks = [];
		},

		/**
		 * Registers a hook handler with the event system.
		 *
		 * The special keyword "all" can be used for either the name or the type or both
		 * and means to call that handler for all of those hooks.
		 *
		 * @param {String}   name     The hook name.
		 * @param {String}   type     The hook type.
		 * @param {Function} handler  Handler to call: function(hook, type, params, value)
		 * @param {Number}   priority Priority to call the event handler
		 * @return {Boolean}
		 */
		register: function(name, type, handler, priority) {
			elgg.assertTypeOf('string', name);
			elgg.assertTypeOf('string', type);
			elgg.assertTypeOf('function', handler);
	
			if (!name || !type) {
				return false;
			}
	
			prepareHook(name, type);

			hooks[name][type].push({
				priority: priority,
				index: index++,
				handler: handler
			});
			
			return true;
		},
		
		/**
		 * Emits a synchronous hook, calling only synchronous handlers
		 *
		 * Loops through all registered hooks and calls the handler functions in order.
		 * Every handler function will always be called, regardless of the return value.
		 *
		 * @warning Handlers take the same 4 arguments in the same order as when calling this function.
		 * This is different from the PHP version!
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
		trigger: function(name, type, params, value) {
			elgg.assertTypeOf('string', name);
			elgg.assertTypeOf('string', type);
		
			// default to null if unpassed
			value = (value != null) ? value : null;
		
			var registrations = [],
				push = Array.prototype.push;
		
			prepareHook(name, type);
		
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
				if (handler_return != null) {
					value = handler_return;
				}
			});
		
			return value;
		}
	};
});
