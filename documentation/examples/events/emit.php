<?php

$params = new ElggObject();
trigger_elgg_event('test', 'example', $params);

// handlers would be registered by saying
register_elgg_event_handler('test', 'example', 'example_event_handler');
