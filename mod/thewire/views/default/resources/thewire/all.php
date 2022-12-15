<?php
/**
 * All wire posts
 */

elgg_push_collection_breadcrumbs('object', 'thewire');

$content = '';
if (elgg_is_logged_in()) {
	$content .= elgg_view_form('thewire/add', [
		'class' => 'thewire-form',
	]);
}

$content .= elgg_view('thewire/listing/all');

echo elgg_view_page(elgg_echo('collection:object:thewire:all'), [
	'content' => $content,
	'sidebar' => elgg_view('thewire/sidebar'),
	'filter_value' => 'all',
]);
