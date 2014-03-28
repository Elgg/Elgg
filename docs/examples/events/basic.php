<?php
/**
 * This snippet demonstrates how to register for an event. It dumps the
 * parameters that the handler receives to the screen. The third argument
 * of the handler function is an object that is related to the event. For
 * the 'init', 'system' event, it is null.
 */

elgg_register_event_handler('init', 'system', 'example_event_handler');

function example_event_handler($event, $type, $object) {
	var_dump($event);
	var_dump($type);
	var_dump($object);

	return true;
}
