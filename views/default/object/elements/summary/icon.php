<?php
/**
 * Object icon
 *
 * @uses $vars['entity']      ElggEntity
 * @uses $vars['icon']        Object icon. If false no icon will be shown, true will try entity icon with a fallback to owner icon,
 *                            anything else will just be drawn.
 * @uses $vars['icon_size']   The icon size to draw for the entity (default: small)
 * @uses $vars['icon_entity'] The entity to draw the icon for. This can be different from the listed entity (eg owner of a blog)
 *                            If provided no fallback logic will be applied
 */

$icon = elgg_extract('icon', $vars);
if ($icon === false) {
	return;
}

if (isset($icon) && $icon !== true) {
	echo $icon;
	return;
}

$icon_size = elgg_extract('icon_size', $vars, 'small');
$icon_entity = elgg_extract('icon_entity', $vars);
if ($icon_entity instanceof ElggEntity) {
	echo elgg_view_entity_icon($icon_entity, $icon_size, $vars);
	return;
}

if ($icon !== true) {
	return;
}

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	return;
}

if ($entity->hasIcon($icon_size)) {
	echo elgg_view_entity_icon($entity, $icon_size, $vars);
	return;
}

$owner = $entity->getOwnerEntity();
if (!$owner instanceof ElggEntity) {
	return;
}

echo elgg_view_entity_icon($owner, $icon_size, $vars);
