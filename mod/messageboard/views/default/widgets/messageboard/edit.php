<?php
/**
 * Messageboard widget edit view
 */

$widget = elgg_extract('entity', $vars);

echo elgg_view('object/widget/edit/num_display', [
	'entity' => $widget,
	'label' => elgg_echo('messageboard:num_display'),
	'default' => 4,
]);
