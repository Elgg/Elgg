<?php
/**
 * If you register an 'all' string for the event name, the handler function will
 * be called for all events with that name, regardless of event type. The same
 * can be done for the event type argument. Registering 'all' for both
 * argyuments results in a handler being called for every event.
 */

elgg_register_event_handler('all', 'object', 'example_event_handler');

// This function will be called for any event of type 'object'
function example_event_handler($event, $type, $object) {
	// check what sort of object is passed
	if ($object instanceof ElggObject) {
		$subtype = $object->getSubtype();

		switch ($subtype) {
			case 'blog':
			case 'thewire':
			case 'pages':
				// prevent these object subtypes from being saved or changed
				return false;
			default:
				return true;
		}

	}

	return true;
}
