<?php
/**
 * Elgg likes river preloader (extends page/components/list)
 *
 * @uses $vars['items']       Array of ElggEntity or ElggAnnotation objects
 * @uses $vars['list_class']  Additional CSS class for the <ul> element
 */

$user = elgg_get_logged_in_user_entity();
$list_class = elgg_extract('list_class', $vars);
if (!is_array($list_class)) {
	$list_class = explode(' ', $list_class);
}
if ($user
		&& !elgg_in_context('widgets')
		&& (in_array('elgg-list-river', $list_class) || in_array('elgg-list-entity', $list_class))
		&& (count($vars['items']) > 2)) {
	$preloader = new \Elgg\Likes\Preloader(\Elgg\Likes\DataService::instance());
	$preloader->preloadForList($vars['items']);
}
