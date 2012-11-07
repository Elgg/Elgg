<?php
/**
 * Group profile RSS view
 *
 * Displays a list of the latest content in the group
 *
 * @uses $vars['entity'] ElggGroup object
 */

$entities = elgg_get_config('registered_entities');

if ($entities['object'] && count($entities['object'])) {
	$subtypes = array();
	foreach ($entities['object'] as $subtype) {
		$subtypes[] = $subtype;
	}
	
	echo elgg_list_entities(array(
		'type' => 'object',
		'subtypes' => $subtypes,
		'container_guid' => $vars['entity']->getGUID(),
	));
}
