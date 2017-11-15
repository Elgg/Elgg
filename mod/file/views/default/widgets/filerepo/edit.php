<?php
/**
 * Elgg file widget edit view
 */

$widget = elgg_extract('entity', $vars);

echo elgg_view('object/widget/edit/num_display', [
	'entity' => $widget,
	'label' => elgg_echo('file:num_files'),
	'default' => 4,
]);
