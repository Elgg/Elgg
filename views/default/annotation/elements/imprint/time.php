<?php
/**
 * Displays information the time of the annotation
 *
 * @uses $vars['annotation'] The annotation to show the time for
 * @uses $vars['time']       Time of the annotation
 *                           If not set, will display the time when the annotation was created (time_created attribute)
 *                           If set to false, time string will not be rendered
 * @uses $vars['time_icon']  Icon name to be used with time info
 *                           Set to false to not render an icon
 *                           Default is 'history'
 * @uses $vars['time_href']  Optional link to set on the friendly time text
 */

$annotation = elgg_extract('annotation', $vars);
if (!$annotation instanceof ElggAnnotation) {
	return;
}

$time = elgg_extract('time', $vars, $annotation->time_created);
if (empty($time)) {
	return;
}

$content = elgg_view_friendly_time($time);

$time_href = elgg_extract('time_href', $vars);
if (!empty($time_href)) {
	$content = elgg_view_url($time_href, $content);
}

if (elgg_is_empty($content)) {
	return;
}

echo elgg_view('object/elements/imprint/element', [
	'icon_name' => elgg_extract('time_icon', $vars, 'history'),
	'content' => $content,
	'class' => 'elgg-listing-time',
]);
