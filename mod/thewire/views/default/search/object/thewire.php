<?php
/**
 * Search result of a wire post
 *
 * @uses $vars['entity'] the wire post
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggWire) {
	return;
}

// set a title, so we get a clickable link and no duplicate information
// as ->getDisplayName() defaults to an excerpt of description which is already shown
$entity->setVolatileData('search_matched_title', elgg_echo('item:object:thewire'));

// link to the thread, not the entity
$thread_id = (int) $entity->wire_thread;
$search_url = elgg_generate_url('collection:object:thewire:thread', [
	'guid' => $thread_id,
]);
// add # to jump directly to this post
$search_url .= "#elgg-object-{$entity->guid}";

$entity->setVolatileData('search_url', $search_url);

$vars['entity'] = $entity;

echo elgg_view('search/entity/default', $vars);
