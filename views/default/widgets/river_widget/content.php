<?php
/**
 * Activity widget content view
 */

$widget = elgg_extract('entity', $vars);

$num_display = sanitize_int($widget->num_display, false);
// set default value for display number
if (!$num_display) {
	$num_display = 8;
}

$options = [
	'limit' => $num_display,
	'pagination' => false,
	'no_results' => elgg_echo('river:none'),
];

if (elgg_in_context('dashboard')) {
	if ($widget->content_type == 'friends') {
		$options['relationship_guid'] = $widget->getOwnerGUID();
		$options['relationship'] = 'friend';
	}
} else {
	$options['subject_guid'] = $widget->getOwnerGUID();
}

echo elgg_list_river($options);
