<?php

elgg_register_event_handler('init', 'system', 'example_event_handler');

function example_event_handler($event, $type, $params) {
	var_dump($event);
	var_dump($object_type);
	var_dump($params);

	return true;
}


