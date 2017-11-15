<?php
/**
 * Pages widget edit
 */

$widget = elgg_extract('entity', $vars);

echo elgg_view('object/widget/edit/num_display', [
	'entity' => $widget,
	'name' => 'pages_num',
	'label' => elgg_echo('pages:num'),
	'default' => 4,
]);
