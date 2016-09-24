<?php

/**
 * Renders a list of discussions, optionally filtered by container type
 *
 * @uses $vars['container_type'] Container type filter to apply
 */
$options = array(
	'type' => 'object',
	'subtype' => 'discussion',
	'order_by' => 'e.last_action desc',
	'limit' => max(20, elgg_get_config('default_limit')),
	'full_view' => false,
	'no_results' => elgg_echo('discussion:none'),
	'preload_owners' => true,
	'preload_containers' => true,
);

$container_type = elgg_extract('container_type', $vars);
if ($container_type) {
	$dbprefix = elgg_get_config('dbprefix');
	$container_type = sanitize_string($container_type);
	$options['joins'][] = "JOIN {$dbprefix}entities ce ON ce.guid = e.container_guid";
	$options['wheres'][] = "ce.type = '$container_type'";
}

echo elgg_list_entities($options);
