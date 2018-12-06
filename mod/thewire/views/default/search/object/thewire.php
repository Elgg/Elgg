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

$entity->setVolatileData('search_matched_title', elgg_echo('item:object:thewire'));

$vars['entity'] = $entity;

echo elgg_view('search/entity/default', $vars);
