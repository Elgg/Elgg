<?php
/**
 * Create a new API key object
 */

$title = elgg_echo('add:object:api_key');

$content = elgg_view_form('webservices/api_key/edit', ['sticky_enabled' => true]);

if (elgg_is_xhr()) {
	// in the lightbox
	echo elgg_view_module('info', $title, $content);
	return;
}

echo elgg_view_page($title, [
	'content' => $content,
	'filter' => false,
]);
