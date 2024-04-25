<?php
/**
 * Activity widget content view
 */

$widget = elgg_extract('entity', $vars);
if (!$widget instanceof \ElggWidget) {
	return;
}

$num_display = (int) $widget->num_display ?: 8;

$options = [
	'limit' => $num_display,
	'pagination' => false,
	'widget_more' => elgg_view_url($widget->getURL(), elgg_echo('activity:more')),
];

if ($widget->context === 'dashboard') {
	$content_type = $widget->content_type ?: 'friends';
	if ($content_type === 'friends') {
		echo elgg_view('river/listing/friends', [
			'entity' => $widget->getOwnerEntity(),
			'options' => $options,
		]);
	} else {
		echo elgg_view('river/listing/all', [
			'options' => $options,
		]);
	}
	
	return;
}

echo elgg_view('river/listing/owner', [
	'entity' => $widget->getOwnerEntity(),
	'options' => $options,
]);
