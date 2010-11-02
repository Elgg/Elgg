<?php

// @todo this is an event, not a hook
register_elgg_event_handler('test', 'example', 'example_init_system_callback');

$params = new ElggObject();
trigger_elgg_event('test', 'example', $params);
