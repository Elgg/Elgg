<?php
/**
 * View conversation thread
 */

use Elgg\Exceptions\Http\EntityNotFoundException;

$thread_id = (int) elgg_extract('guid', $vars);

// it could be that the main wire post was deleted
/* @var $original_post \ElggWire */
$original_post = get_entity($thread_id);
if ($original_post instanceof \ElggWire) {
	if (!elgg_get_page_owner_entity() instanceof \ElggEntity) {
		throw new EntityNotFoundException();
	}
	
	elgg_push_entity_breadcrumbs($original_post);
}

echo elgg_view_page(elgg_echo('thewire:thread'), [
	'content' => elgg_view('thewire/listing/thread', [
		'entity' => $original_post,
		'thread_id' => $thread_id,
	]),
	'filter_id' => 'thewire/thread',
]);
