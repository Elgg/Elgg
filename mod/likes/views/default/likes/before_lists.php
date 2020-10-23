<?php
/**
 * Elgg likes river preloader (extends page/components/list)
 *
 * @uses $vars['preload_likes'] Set to true if you want to try to preload likes. If not set it will try to determine automatically if it is needed.
 * @uses $vars['items']         Array of ElggEntity or ElggAnnotation objects
 * @uses $vars['list_class']    Additional CSS class for the <ul> element
 */

$items = (array) elgg_extract('items', $vars, []);
if (!elgg_is_logged_in() || count($items) < 3) {
	return;
}

$preload = elgg_extract('preload_likes', $vars);
if (!isset($preload)) {
	$list_class = elgg_extract('list_class', $vars);
	$preload = !elgg_in_context('widgets') && in_array($list_class, ['elgg-list-river', 'elgg-list-entity', 'comments-list']);
}

if (!$preload) {
	return;
}

$preloader = new \Elgg\Likes\Preloader(\Elgg\Likes\DataService::instance());
$preloader->preloadForList($items);
