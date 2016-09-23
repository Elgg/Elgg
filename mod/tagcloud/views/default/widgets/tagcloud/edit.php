<?php
/**
 * Tagcloud widget edit view
 */

$widget = elgg_extract('entity', $vars);
// set default value
if (!isset($widget->num_items)) {
	$widget->num_items = 30;
}

echo elgg_view('object/widget/edit/num_display', [
	'entity' => $widget,
	'name' => 'num_items',
	'options' => [10, 20, 30, 50, 100],
	'label' => elgg_echo('tagcloud:widget:numtags'),
]);
