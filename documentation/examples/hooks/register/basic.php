<?php

elgg_register_plugin_hook_handler('forward', 'system', 'example_plugin_hook_handler');

function example_plugin_hook_handler($event, $type, $value, $params) {
	var_dump($event);
	var_dump($type);
	var_dump($value);
	var_dump($params);
	
	return true;
}


