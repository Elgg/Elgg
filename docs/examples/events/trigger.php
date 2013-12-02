<?php
/**
 * These two snippets demonstrates triggering an event and how to register for
 * that event.
 */

$object = new ElggObject();
$params = array(
	'sample_parameter' => array(1,2,3,4),
	'second parameter' => $object
);
elgg_trigger_event('test', 'example', $object, $params);

// elsewhere a handler could be registered by saying
elgg_register_event_handler('test', 'example', 'example_event_handler');
