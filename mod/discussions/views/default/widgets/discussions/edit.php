<?php
/**
 * Discussions widget edit view
 */

$widget = elgg_extract('entity', $vars);

echo elgg_view('object/widget/edit/num_display', [
	'entity' => $widget,
	'default' => 4,
]);
