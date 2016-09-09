<?php
/**
 * Elgg likes river preloader (extends page/components/list)
 *
 * @uses $vars['items']       Array of ElggEntity or ElggAnnotation objects
 * @uses $vars['list_class']  Additional CSS class for the <ul> element
 */

$user = elgg_get_logged_in_user_entity();
if ($user
		&& !elgg_in_context('widgets')
		&& !empty($vars['list_class'])
		&& ($vars['list_class'] === 'elgg-list-river' || $vars['list_class'] === 'elgg-list-entity')
		&& (count($vars['items']) > 2)) {
	$preloader = new \Elgg\Likes\Preloader(\Elgg\Likes\DataService::instance());
	$preloader->preloadForList($vars['items']);
}
