<?php
/**
 * Navigation menu for a page
 *
 * @uses $vars['page'] Page object for which the menu items should be shown
 */

$content = elgg_view_menu('pages_nav', [
	'class' => ['pages-nav', 'elgg-menu-page'],
	'entity' => elgg_extract('page', $vars),
	'prepare_vertical' => true,
]);

if (empty($content)) {
	return;
}

echo elgg_view_module('aside', elgg_echo('pages:navigation'), $content);
