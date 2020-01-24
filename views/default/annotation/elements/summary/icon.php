<?php
/**
 * Annotation icon
 *
 * @uses $vars['annotation']  ElggAnnotation
 * @uses $vars['icon']        Annotation icon. If false no icon will be shown, '' for default icon.
 * @uses $vars['icon_size']   The icon size to draw for the entity (default: small)
 * @uses $vars['icon_entity'] The entity to draw the icon for. This can be different from the listed entity (eg owner of a blog)
 *                            If provided no fallback logic will be applied
 */

$icon = elgg_extract('icon', $vars, '');
if ($icon === false) {
	return;
}

if ($icon !== '') {
	echo $icon;
	return;
}

$icon_size = elgg_extract('icon_size', $vars, 'small');
$icon_entity = elgg_extract('icon_entity', $vars);
if ($icon_entity instanceof ElggEntity) {
	echo elgg_view_entity_icon($icon_entity, $icon_size);
	return;
}

$annotation = elgg_extract('annotation', $vars);
if (!$annotation instanceof ElggAnnotation) {
	return;
}

$owner = $annotation->getOwnerEntity();
if (!$owner instanceof ElggEntity) {
	return;
}

echo elgg_view_entity_icon($owner, $icon_size);
