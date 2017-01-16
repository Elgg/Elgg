<?php

$entity = elgg_extract('entity', $vars);
$input_name = elgg_extract('input_name', $vars);

// reset the viewtype so we can render html views
$viewtype = elgg_get_viewtype();
elgg_set_viewtype('default');

$icon = elgg_view_entity_icon($entity, 'tiny', array(
	'use_link' => false,
	'href' => false,
	'use_hover' => false,
		));

$title = elgg_format_element('h3', [], $entity->getDisplayName());

$label = elgg_view_image_block($icon, $title, [
	'class' => 'elgg-autocomplete-item',
		]);

$data = $entity->toObject();
$data->label = $label;
$data->value = $entity->username;
$data->icon = $icon;

if ($input_name) {
	$data->html = elgg_view('input/userpicker/item', $vars);
}

echo json_encode($data);

elgg_set_viewtype($viewtype);