<?php
/**
 * Generic entity header upload helper
 */

$defaults = [
	'name' => 'header',
	'icon_type' => 'header',
	'cropper_aspect_ratio_size' => 'header',
];

$vars = array_merge($defaults, $vars);

echo elgg_view('entity/edit/icon', $vars);
