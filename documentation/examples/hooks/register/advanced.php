<?php

// the output:page hook is triggered by elgg_view_page().
elgg_register_plugin_hook_handler('output', 'page', 'example_plugin_hook_handler', 600);
elgg_register_plugin_hook_handler('output', 'page', 'example_plugin_hook_handler_2', 601);

function example_plugin_hook_handler($event, $type, $value, $params) {
	// change A to @
	$value = str_replace('A', '@', $value);

	return $value;
}

function example_plugin_hook_handler_2($event, $type, $value, $params) {
	// change S to $
	$value = str_replace('S', '$', $value);

	return $value;
}

$content = 'This is some Sample Content.';

echo elgg_view_page('Title', $content);