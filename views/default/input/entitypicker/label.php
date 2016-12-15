<?php
/**
 * List item view in Entity Picker
 *
 * @uses $vars['entity'] User entity
 * @uses $vars['class'] Name of the returned data array
 */

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof \ElggEntity)) {
	return;
}

echo elgg_view_list_item($entity, [
	'use_hover' => false,
	'use_link' => false,
	'class' => 'elgg-autocomplete-item',
	'title' => $entity->getDisplayName(),
]);
