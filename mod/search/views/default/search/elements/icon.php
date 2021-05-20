<?php
/**
 * Default view for a icon used in search results
 *
 * Display largely controlled by a set of overrideable volatile data:
 *   - search_icon
 *
 * @uses $vars['entity']    Entity returned in a search
 * @uses $vars['icon_size'] Size of icon (default 'small')
 */
$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	return;
}

if ($entity->getVolatileData('search_icon')) {
	echo $entity->getVolatileData('search_icon');
	return;
}

$owner = $entity->getOwnerEntity();

$size = elgg_extract('icon_size', $vars, 'small');

if ($entity->hasIcon($size) || $entity instanceof \ElggFile) {
	echo elgg_view_entity_icon($entity, $size);
} else if ($entity->getType() === 'user' || $entity->getType() === 'group') {
	echo elgg_view_entity_icon($entity, $size);
} else if ($owner instanceof ElggUser) {
	echo elgg_view_entity_icon($owner, $size);
} else if ($entity->getContainerEntity() instanceof ElggUser) {
	echo elgg_view_entity_icon($entity, $size);
}
