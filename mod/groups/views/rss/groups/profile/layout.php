<?php
/**
 * Group profile RSS view
 *
 * Displays a list of the latest content in the group
 *
 * @uses $vars['entity'] ElggGroup object
 */

echo elgg_list_entities(array(
	'type' => 'object',
	'container_guid' => $vars['entity']->getGUID(),
));
