<?php

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggGroup) {
	return;
}

$input_name = elgg_extract('input_name', $vars);

// reset the viewtype so we can render html views
$viewtype = elgg_get_viewtype();
elgg_set_viewtype('default');

$icon = elgg_view_entity_icon($entity, 'tiny', [
	'use_link' => false,
	'href' => false,
]);

$title = elgg_format_element('h3', [], $entity->getDisplayName());

$label = elgg_view_image_block($icon, $title, [
	'class' => 'elgg-autocomplete-item',
]);

$data = $entity->toObject();
$data->label = $label;
$data->value = $entity->guid;
$data->icon = $icon;

if ($input_name) {
	$data->html = elgg_view('input/autocomplete/item', $vars);
}

echo json_encode($data);

// restore viewtype
elgg_set_viewtype($viewtype);
