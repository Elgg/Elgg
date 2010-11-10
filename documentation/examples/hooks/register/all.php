<?php

elgg_register_plugin_hook_handler('all', 'system', 'example_plugin_hook_handler');

// This function will be called for any hook of type 'system'
function example_plugin_hook_handler($hook, $type, $value, $params) {
	// logic here.
}
