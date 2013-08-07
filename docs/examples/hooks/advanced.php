<?php
/**
 * This snippet demonstrates how to change the value of a hook. The content
 * passed into the hook is 'This is some Sample Content.'. After the two hook
 * handlers are done, the new content is 'This is some $@mple Content.'.
 */

// the output:page hook is triggered by elgg_view_page().
elgg_register_plugin_hook_handler('output', 'page', 'example_plugin_hook_handler', 600);
elgg_register_plugin_hook_handler('output', 'page', 'example_plugin_hook_handler_2', 601);

function example_plugin_hook_handler($hook, $type, $value, $params) {
	// change a to @
	$value = str_replace('a', '@', $value);

	return $value;
}

function example_plugin_hook_handler_2($hook, $type, $value, $params) {
	// change S to $
	$value = str_replace('S', '$', $value);

	return $value;
}

$content = 'This is some Sample Content.';

echo elgg_view_page('Title', $content);
