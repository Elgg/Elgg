<?php
/**
 * The handler for a plugin hook receives information about the hook (name and
 * type), the current value for the hook, and parameters related to the hook.
 */

elgg_register_plugin_hook_handler('forward', '404', 'example_plugin_hook_handler');

function example_plugin_hook_handler($hook, $type, $value, $params) {
	var_dump($hook);
	var_dump($type);
	var_dump($value);
	var_dump($params);

	// we are not changing $value so return null
	return null;
}
