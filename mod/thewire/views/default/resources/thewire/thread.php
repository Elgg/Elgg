<?php
/**
 * View conversation thread
 */

$thread_id = elgg_extract('guid', $vars);
elgg_entity_gatekeeper($thread_id, 'object', 'thewire');

/* @var $original_post ElggWire */
$original_post = get_entity($thread_id);

elgg_push_entity_breadcrumbs($original_post);

$content = elgg_list_entities([
	'type' => 'object',
	'subtype' => 'thewire',
	'metadata_name_value_pairs' => [
		'name' => 'wire_thread',
		'value' => $thread_id,
	],
	'limit' => max(20, elgg_get_config('default_limit')),
	'preload_owners' => true,
]);

echo elgg_view_page(elgg_echo('thewire:thread'), [
	'content' => $content,
	'filter_id' => 'thewire/thread',
]);
