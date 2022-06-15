<?php
/**
 * Group profile RSS view
 *
 * Displays a list of the latest content in the group
 *
 * @uses $vars['entity'] ElggGroup object
 */

$entities = elgg_entity_types_with_capability('searchable');
if (!empty($entities['object'])) {
	echo elgg_list_entities([
		'type' => 'object',
		'subtypes' => $entities['object'],
		'container_guid' => $vars['entity']->getGUID(),
	]);
}
