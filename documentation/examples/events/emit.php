<?php

$params = new ElggObject();
elgg_trigger_event('test', 'example', $params);

// handlers would be registered by saying
elgg_register_event_handler('test', 'example', 'example_event_handler');
