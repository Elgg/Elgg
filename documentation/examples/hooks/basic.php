<?php

elgg_register_plugin_hook_handler('get_items', 'example', 'example_plugin_hook');
elgg_register_plugin_hook_handler('get_items', 'example', 'example_plugin_hook_2');

$params = array('username' => 'Joe');
$items = elgg_trigger_plugin_hook('get_items', 'example', $params, $default);

var_dump($items);

function example_plugin_hook($hook, $type, $value, $params) {
	if (is_array($value)) {
		$value[] = "Hook Value 1";
		$value[] = "Hook Value 2";
	}

	return $value;
}

function example_plugin_hook_2($hook, $type, $value, $params) {
	$username = isset($params['username']) ? $params['username'] : NULL;
	if (is_array($value)) {
		switch($username) {
			case 'Joe':
				$value[] = "Joe's item";
				break;
			case 'John':
				$value[] = "Joe's item";
				break;
		}
	}

	return $value;
}
