<?php
/**
 * Elgg file widget edit view
 */

$widget = elgg_extract('entity', $vars);

echo elgg_view('object/widget/edit/num_display', [
	'entity' => $widget,
	'options' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 15, 20],
	'label' => elgg_echo('file:num_files'),
	'default' => 4,
]);
