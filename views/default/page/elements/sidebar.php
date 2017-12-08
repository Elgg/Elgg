<?php
/**
 * Elgg sidebar contents
 *
 * @uses $vars['sidebar'] Optional content that is displayed at the bottom of sidebar
 */

echo elgg_view('page/elements/owner_block', $vars);

$page_menu = elgg_view_menu('page', ['sort_by' => 'name']);
if ($page_menu) {
	echo elgg_view_module('info', '', $page_menu, [
		'class' => 'elgg-page-menu',
	]);
}

// optional 'sidebar' parameter
if (isset($vars['sidebar'])) {
	echo $vars['sidebar'];
}
