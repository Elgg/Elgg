elgg.provide('elgg.config.events');

/**
 * 
 */
elgg.register_event_handler = function(event_name, event_type, handler, priority) {
	elgg.assertTypeOf('string', event_name);
	elgg.assertTypeOf('string', event_type);
	elgg.assertTypeOf('function', handler);
	
	if (!event_name || !event_type) {
		return false;
	}
	
	var events = elgg.config.events;
	
	elgg.provide(event_name + '.' + event_type, events);

	
	if (!(events[event_name][event_type] instanceof elgg.ElggPriorityList)) {
		events[event_name][event_type] = new elgg.ElggPriorityList();
	}

	return events[event_name][event_type].insert(handler, priority);
};

/**
 * 
 */
elgg.trigger_event = function(event_name, event_type, opt_object) {
	elgg.assertTypeOf('string', event_name);
	elgg.assertTypeOf('string', event_type);

	var events = elgg.config.events,
		callEventHandler = function(handler) { 
			return handler(event_name, event_type, opt_object) !== false; 
		}
	
	elgg.provide(event_name + '.' + event_type, events);
	elgg.provide('all.' + event_type, events);
	elgg.provide(event_name + '.all', events);
	elgg.provide('all.all', events);
	
	return [
	    events[event_name][event_type],
	    events['all'][event_type],
	    events[event_name]['all'],
	    events['all']['all']
	].every(function(handlers) {
		return !(handlers instanceof elgg.ElggPriorityList) || handlers.every(callEventHandler);
	});
};