<?php
/**
 * Outputs entity previous/next navigation elements
 *
 * @uses $vars['entity'] Entity used to determine previous/next urls
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggEntity) {
	return;
}

if (!elgg_extract('show_navigation', $vars, false)) {
	return;
}

$vars['sort_by'] = 'priority';

$menu = elgg_view_menu('entity_navigation', $vars);
if (!$menu) {
	return;
}

echo elgg_format_element('div', ['class' => 'elgg-listing-full-navigation'], $menu);
