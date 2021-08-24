<?php
/**
 * Friend widget options
 */

/* @var $widget ElggWidget */
$widget = elgg_extract('entity', $vars);

echo elgg_view('object/widget/edit/num_display', [
	'entity' => $widget,
	'label' => elgg_echo('friends:num_display'),
	'default' => 12,
	'max' => 100,
]);

echo elgg_view_field([
	'#type' => 'select',
	'name' => 'params[icon_size]',
	'#label' => elgg_echo('icon:size'),
	'value' => $widget->icon_size ?: 'small',
	'options_values' => [
		'small' => elgg_echo('icon:size:small'),
		'tiny' => elgg_echo('icon:size:tiny'),
	],
]);
