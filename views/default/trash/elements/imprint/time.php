<?php
/**
 * Displays information the time of the post
 *
 * @uses $vars['entity']    The entity to show the time for
 * @uses $vars['time']      Time of the post
 *                          If not set, will display the time when the entity was deleted (time_deleted attribute)
 *                          If set to false, time string will not be rendered
 * @uses $vars['time_icon'] Icon name to be used with time info
 *                          Set to false to not render an icon
 *                          Default is 'history'
 * @uses $vars['time_href'] Optional link to set on the friendly time text
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggEntity) {
	return;
}

$time = elgg_extract('time', $vars, $entity->time_deleted);
if (!$time) {
	return;
}

$content = elgg_view_friendly_time($time);
$time_href = elgg_extract('time_href', $vars);
if (!empty($time_href)) {
	$content = elgg_view_url($time_href, $content);
}

echo elgg_view('trash/elements/imprint/element', [
	'icon_name' => elgg_extract('time_icon', $vars, 'trash-alt'),
	'content' => $content,
	'class' => 'elgg-listing-time',
]);
