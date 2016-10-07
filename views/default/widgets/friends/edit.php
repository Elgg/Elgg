<?php
/**
 * Friend widget options
 */

$widget = elgg_extract('entity', $vars);

// handle upgrade to 1.7.2 from previous versions
if ($widget->icon_size == 1) {
	$widget->icon_size = 'small';
} elseif ($widget->icon_size == 2) {
	$widget->icon_size = 'tiny';
}

// set default value for icon size
if (!isset($widget->icon_size)) {
	$widget->icon_size = 'small';
}

echo elgg_view('object/widget/edit/num_display', [
	'entity' => $widget,
	'default' => 12,
	'label' => elgg_echo('friends:num_display'),
	'options' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 15, 20, 30, 50, 100],
]);

echo elgg_view_field([
	'#type' => 'select',
	'name' => 'params[icon_size]',
	'#label' => elgg_echo('friends:icon_size'),
	'value' => $widget->icon_size,
	'options_values' => [
		'small' => elgg_echo('friends:small'),
		'tiny' => elgg_echo('friends:tiny'),
	],
]);
