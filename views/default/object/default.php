<?php
/**
 * ElggObject default view.
 *
 * @warning This view may be used for other ElggEntity objects
 *
 * @package Elgg
 * @subpackage Core
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	return;
}

if (!isset($vars['icon'])) {
	if ($entity->hasIcon('small')) {
		$vars['icon'] = elgg_view_entity_icon($entity, 'small');
	} else {
		$owner = $entity->getOwnerEntity();
		if ($owner instanceof ElggEntity) {
			$vars['icon'] = elgg_view_entity_icon($owner, 'small');
		}
	}
}

if (!isset($vars['title']) && empty($entity->getDisplayName())) {
	$vars['title'] = get_class($entity);
}

echo elgg_view('object/elements/summary', $vars);
