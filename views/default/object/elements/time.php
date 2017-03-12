<?php

/**
 * Displays information the time of the post
 *
 * @uses $vars['entity']      The entity to show the byline for
 * @uses $vars['time']        Time of the post
 *                            If not set, will display the time when the entity was created (time_created attribute)
 *                            If set to false, time string will not be rendered
 * @uses $vars['time_icon']   Icon name to be used with time info
 *                            Set to false to not render an icon
 *                            Default is 'history'
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	return;
}

$time = elgg_extract('time', $vars);
if (!isset($time)) {
	$time = $entity->time_created;
}
if (!$time) {
	return;
}

$icon_name = elgg_extract('time_icon', $vars, 'history');
if ($icon_name === false) {
	$icon = '';
} else {
	$icon = elgg_view_icon($icon_name);
}

$time_str = $icon . elgg_view_friendly_time($time);

echo elgg_format_element('span', [
	'class' => 'elgg-listing-time',
		], $time_str);

