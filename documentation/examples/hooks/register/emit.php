<?php

// @todo this is an event, not a hook
elgg_register_event_handler('test', 'example', 'example_init_system_callback');

$params = new ElggObject();
elgg_trigger_event('test', 'example', $params);
