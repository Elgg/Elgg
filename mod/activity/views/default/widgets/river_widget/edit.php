<?php
/**
 * Edit settings for river widget
 */

/* @var $widget \ElggWidget */
$widget = elgg_extract('entity', $vars);

// dashboard widget has type parameter
if ($widget->context === 'dashboard') {
	if (!isset($widget->content_type)) {
		$widget->content_type = 'friends';
	}

	echo elgg_view_field([
		'#type' => 'select',
		'name' => 'params[content_type]',
		'#label' => elgg_echo('widgets:river_widget:type'),
		'value' => $widget->content_type,
		'options_values' => [
			'friends' => elgg_echo('widgets:river_widget:friends'),
			'all' => elgg_echo('widgets:river_widget:all'),
		],
	]);
}

echo elgg_view('object/widget/edit/num_display', [
	'entity' => $widget,
	'default' => 8,
]);
