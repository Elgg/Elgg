<?php
/**
 * View conversation thread
 */

$thread_id = (int) elgg_extract('guid', $vars);
elgg_entity_gatekeeper($thread_id, 'object', 'thewire');

/* @var $original_post \ElggWire */
$original_post = get_entity($thread_id);

elgg_push_entity_breadcrumbs($original_post);

echo elgg_view_page(elgg_echo('thewire:thread'), [
	'content' => elgg_view('thewire/listing/thread', [
		'entity' => $original_post,
	]),
	'filter_id' => 'thewire/thread',
]);
