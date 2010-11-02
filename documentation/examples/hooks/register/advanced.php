<?php

// the output:page hook is triggered by page_draw().
register_plugin_hook('output', 'page', 'example_plugin_hook_handler', 600);
register_plugin_hook('output', 'page', 'example_plugin_hook_handler_2', 601);

function example_plugin_hook_handler($event, $type, $value, $params) {
	// change A to @
	$value = str_replace('A', '@', $value);
	
	return $value;
}

function example_plugin_hook_handler($event, $type, $value, $params) {
	// change S to $
	$value = str_replace('S', '$', $value);
	
	return $value;
}

$content = 'This is some Sample Content.';

page_draw('Title', $content);