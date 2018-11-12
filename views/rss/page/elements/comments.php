<?php
/**
 * RSS comments view
 *
 * @uses $vars['entity']        ElggEntity
 * @uses $vars['limit']         Optional limit value (default is 25)
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	return;
}

$limit = elgg_extract('limit', $vars, get_input('limit', 0));
if (!$limit) {
	$limit = elgg_trigger_plugin_hook('config', 'comments_per_page', [], 25);
}

echo elgg_list_entities([
	'type' => 'object',
	'subtype' => 'comment',
	'container_guid' => $entity->guid,
	'reverse_order_by' => true,
	'full_view' => true,
	'limit' => $limit,
	'distinct' => false,
]);
