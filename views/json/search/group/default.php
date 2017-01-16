<?php

$entity = elgg_extract('entity', $vars);

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
$data->value = $entity->guid;
$data->icon = $icon;

echo json_encode($data);

elgg_set_viewtype($viewtype);