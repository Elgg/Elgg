<?php
/**
 * All wire posts
 */

elgg_push_collection_breadcrumbs('object', 'thewire');

$title = elgg_echo('collection:object:thewire:all');

$content = '';
if (elgg_is_logged_in()) {
	$content .= elgg_view_form('thewire/add', [
		'class' => 'thewire-form',
	]);
	$content .= elgg_view('input/urlshortener');
}

$content .= elgg_list_entities([
	'type' => 'object',
	'subtype' => 'thewire',
	'limit' => get_input('limit', 15),
]);

echo elgg_view_page($title, [
	'content' => $content,
	'sidebar' => elgg_view('thewire/sidebar'),
	'filter_value' => 'all',
]);
