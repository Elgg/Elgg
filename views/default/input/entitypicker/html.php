<?php
/**
 * Entity view in Entity Picker
 *
 * @uses $vars['entity'] User entity
 * @uses $vars['input_name'] Name of the returned data array
 */

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof \ElggEntity)) {
	return;
}

$input_name = elgg_extract('input_name', $vars);

$icon = elgg_view_entity_icon($entity, 'tiny', ['use_hover' => false]);

$title = $entity->getDisplayName();

$delete_icon = elgg_view_icon('delete-alt', 'elgg-entity-picker-remove');

$body = elgg_view_image_block($icon, $title, ['image_alt' => $delete_icon]);
$body .= elgg_view_field([
	'#type' => 'hidden',
	'name' => "{$input_name}[]",
	'value' => $entity->guid,
]);

echo elgg_format_element('li', ['data-guid' => $entity->guid], $body);
