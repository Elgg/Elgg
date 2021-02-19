<?php
/**
 * Displays information about the time of the relationship
 *
 * @uses $vars['relationship'] ElggRelationship
 * @uses $vars['time']         Time of the post
 *                             If not set, will display the time when the entity was created (time_created attribute)
 *                             If set to false, time string will not be rendered
 * @uses $vars['time_icon']    Icon name to be used with time info
 *                             Set to false to not render an icon
 *                             Default is 'history'
 * @uses $vars['time_href']    Optional link to set on the friendly time text
 */

$relationship = elgg_extract('relationship', $vars);
if (!$relationship instanceof ElggRelationship) {
	return;
}

$time = elgg_extract('time', $vars, $relationship->time_created);
if (empty($time)) {
	return;
}

$content = elgg_view_friendly_time($time);

$time_href = elgg_extract('time_href', $vars);
if (!empty($time_href)) {
	$content = elgg_view_url($time_href, $content);
}

echo elgg_view('object/elements/imprint/element', [
	'icon_name' => elgg_extract('time_icon', $vars, 'history'),
	'content' => $content,
	'class' => [
		'elgg-listing-time',
	],
]);
