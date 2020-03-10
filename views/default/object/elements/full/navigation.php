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

$params = $vars;
$params['sort_by'] = 'priority';

$menu = elgg_view_menu('entity_navigation', $params);

if (!$menu) {
	return;
}

echo elgg_format_element('div', [
	'class' => 'elgg-listing-full-navigation',
], $menu);
