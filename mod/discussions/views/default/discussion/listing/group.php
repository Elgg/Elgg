<?php
/**
 *
 */

$entity = elgg_extract('entity', $vars);

$options = [
	'type' => 'object',
	'subtype' => 'discussion',
	'limit' => max(20, elgg_get_config('default_limit')),
	'order_by' => 'e.last_action desc',
	'container_guid' => (int) $entity->guid,
	'full_view' => false,
	'no_results' => elgg_echo('discussion:none'),
	'preload_owners' => true,
];

echo elgg_list_entities($options);
