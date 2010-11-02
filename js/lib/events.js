elgg.provide('elgg.config.events');
elgg.provide('elgg.config.events.all');
elgg.provide('elgg.config.events.all.all');

elgg.register_event_handler = function(event, type, callback, priority) {
	elgg.assertTypeOf('string', event);
	elgg.assertTypeOf('string', event);
	elgg.assertTypeOf('function', callback);
	
	if (!event || !type) {
		return false;
	}
	
	elgg.provide('elgg.config.events.' + event + '.' + type);

	var events = elgg.config.events;
	
	if (!(events[event][type] instanceof elgg.ElggPriorityList)) {
		events[event][type] = new elgg.ElggPriorityList();
	}

	return events[event][type].insert(callback, priority);
};

elgg.trigger_event = function(event, type, object) {
	elgg.assertTypeOf('string', event);
	elgg.assertTypeOf('string', event);

	elgg.provide('elgg.config.events.' + event + '.' + type);
	elgg.provide('elgg.config.events.all.' + type);
	elgg.provide('elgg.config.events.' + event + '.all');
	elgg.provide('elgg.config.events.all.all');
	
	var events = elgg.config.events;
	
	var callEventHandler = function(handler) { 
		return handler(event, type, object) !== false; 
	};
	
	if (events[event][type] instanceof elgg.ElggPriorityList) {
		if (!events[event][type].every(callEventHandler)) {
			return false;
		}
	}
	
	if (events['all'][type] instanceof elgg.ElggPriorityList) {
		if (!events['all'][type].every(callEventHandler)) {
			return false;
		}
	}
	
	if (events[event]['all'] instanceof elgg.ElggPriorityList) {
		if (!events[event]['all'].every(callEventHandler)) {
			return false;
		}
	}
	
	if (events['all']['all'] instanceof elgg.ElggPriorityList) {
		if (!events['all']['all'].every(callEventHandler)) {
			return false;
		}
	}
		
	return true;
};