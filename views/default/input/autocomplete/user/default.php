<?php
/**
 * Default user HTML view for autocomplete items
 *
 * @uses $vars['entity'] the selected entity
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggUser) {
	return;
}

$vars['icon'] = elgg_view_entity_icon($entity, 'tiny', ['use_hover' => false]);

echo elgg_view('input/autocomplete/default', $vars);
