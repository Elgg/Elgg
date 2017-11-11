<?php
/**
 * Activity widget content view
 */

$widget = elgg_extract('entity', $vars);

$num_display = (int) $widget->num_display ?: 8;

$options = [
	'limit' => $num_display,
	'pagination' => false,
	'no_results' => elgg_echo('river:none'),
];

if (elgg_in_context('dashboard')) {
	$content_type = $widget->content_type ?: 'friends';
	if ($content_type == 'friends') {
		$options['relationship_guid'] = $widget->getOwnerGUID();
		$options['relationship'] = 'friend';
	}
} else {
	$options['subject_guid'] = $widget->getOwnerGUID();
}

echo elgg_list_river($options);
