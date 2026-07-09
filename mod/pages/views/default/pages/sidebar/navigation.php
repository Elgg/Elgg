<?php
/**
 * Navigation menu for a page
 *
 * @uses $vars['page'] Page object for which the menu items should be shown
 */

$entity = elgg_extract('page', $vars);
if ($entity instanceof \ElggPage) {
	elgg_deprecated_notice('Please provide the page entity in "entity" not in "page"', '7.1');
} else {
	$entity = null;
}

$content = elgg_view_menu('pages_nav', [
	'class' => ['pages-nav', 'elgg-menu-page'],
	'entity' => elgg_extract('entity', $vars, $entity),
]);

if (empty($content)) {
	return;
}

echo elgg_view_module('aside', elgg_echo('pages:navigation'), $content);
