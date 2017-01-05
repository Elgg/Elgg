<?php
/**
 * Shows a line with information about who created a piece of content
 *
 * Generally used a entity summary.
 *
 * @uses $vars['entity']     The entity to show the by line for
 * @uses $vars['show_links'] Owner and container text should show as links (default: true)
 * @uses $vars['time']       Optional timestamp.
 *                           If set, will be used instead of time_created value.
 *                           If set to false, will not be rendered
 *
 */

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof ElggEntity)) {
	return;
}

$show_links = elgg_extract('show_links', $vars, true);

$by_line = [];

$owner = $entity->getOwnerEntity();
if ($owner instanceof ElggEntity) {
	if ($show_links) {
		$owner_text = elgg_view('output/url', [
			'href' => $owner->getURL(),
			'text' => $owner->name,
			'is_trusted' => true,
		]);
	} else {
		$owner_text = $owner->name;
	}
	
	$by_line[] = elgg_echo('byline', [$owner_text]);
}

$time = elgg_extract('time', $vars);
if (!isset($time)) {
	$time = $entity->time_created;
}

if ($time !== false) {
	$by_line[] = elgg_view_friendly_time($time);
}

$container_entity = $entity->getContainerEntity();
if ($container_entity instanceof ElggGroup && ($container_entity->getGUID() !== elgg_get_page_owner_guid())) {
	if ($show_links) {
		$group_text = elgg_view('output/url', [
			'href' => $container_entity->getURL(),
			'text' => $container_entity->name,
			'is_trusted' => true,
		]);
	} else {
		$group_text = $container_entity->name;
	}

	$by_line[] = elgg_echo('byline:ingroup', [$group_text]);
}

if (!empty($by_line)) {
	echo implode(' ', $by_line);
}