<?php
/**
 * Default object HTML view for autocomplete items
 *
 * @uses $vars['entity'] the selected entity
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggObject) {
	return;
}

$vars['icon'] = $entity->hasIcon('tiny') ? elgg_view_entity_icon($entity, 'tiny') : '';

echo elgg_view('input/autocomplete/default', $vars);
