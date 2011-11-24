<?php
/**
 * These two snippets demonstrates triggering an event and how to register for
 * that event.
 */

$object = new ElggObject();
elgg_trigger_event('test', 'example', $object);

// elsewhere a handler could be registered by saying
elgg_register_event_handler('test', 'example', 'example_event_handler');
