<?php
/**
 * Tagcloud widget edit view
 */

$widget = elgg_extract('entity', $vars);

echo elgg_view('object/widget/edit/num_display', [
	'entity' => $widget,
	'name' => 'num_items',
	'label' => elgg_echo('widgets:tagcloud:numtags'),
	'default' => 30,
	'max' => 100,
]);
