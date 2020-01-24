<?php
/**
 * Relationship icon
 *
 * @uses $vars['relationship'] ElggRelationship
 * @uses $vars['icon']         Relationship icon. If false no icon will be shown, '' for default icon.
 * @uses $vars['icon_size']    The icon size to draw for the entity (default: small)
 * @uses $vars['icon_entity']  The entity to draw the icon for. This can be different from the listed entity (eg owner of a blog)
 *                             If provided no fallback logic will be applied
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

$relationship = elgg_extract('relationship', $vars);
if (!$relationship instanceof ElggRelationship) {
	return;
}

$entity_one = get_entity($relationship->guid_one);
if (!$entity_one instanceof ElggEntity) {
	return;
}

echo elgg_view_entity_icon($entity_one, $icon_size);
