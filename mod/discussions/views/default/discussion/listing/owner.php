<?php

/**
 * Renders a list of discussion topics by an owner
 *
 * @uses $vars['entity'] Owner/target entity
 */

$entity = elgg_extract('entity', $vars);

$options = array(
	'type' => 'object',
	'subtype' => 'discussion',
	'limit' => max(20, elgg_get_config('default_limit')),
	'order_by' => 'e.last_action desc',
	'full_view' => false,
	'no_results' => elgg_echo('discussion:none'),
	'preload_owners' => true,
);

if ($target instanceof ElggUser) {
	// Display all discussions started by the user regardless of
	// the entity that is working as a container. See #4878.
	$options['owner_guid'] = (int) $entity->guid;
} else {
	$options['container_guid'] = (int) $entty->guid;
}

echo elgg_list_entities($options);