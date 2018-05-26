<?php
/**
 * Default entity HTML view for autocomplete items
 *
 * @uses $vars['entity'] the selected entity
 * @uses $vars['input_name'] name of the returned data array
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	return;
}

$input_name = elgg_extract('input_name', $vars);
if (empty($input_name)) {
	return;
}

$icon = elgg_view_entity_icon($entity, 'tiny');
$delete_icon = elgg_view_icon('delete', ['class' => 'elgg-autocomplete-item-remove']);

$body = elgg_view_image_block($icon, $entity->getDisplayName(), ['image_alt' => $delete_icon]);
$body .= elgg_view('input/hidden', [
	'name' => "{$input_name}[]",
	'value' => $entity->guid,
]);

echo elgg_format_element('li', [
	'class' => 'elgg-item',
	'data-guid' => $entity->guid,
], $body);
