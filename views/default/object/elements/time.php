<?php

/**
 * Displays information the time of the post
 *
 * @uses $vars['entity']      The entity to show the byline for
 * @uses $vars['time']        Time of the post
 *                            If not set, will display the time when the entity was created (time_created attribute)
 *                            If set to false, time string will not be rendered
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

$friendly_time = elgg_view_friendly_time($time);

echo elgg_view('output/url', [
	'href' => $entity->getURL(),
	'text' => $friendly_time,
	'class' => 'elgg-friendly-time',
]);
