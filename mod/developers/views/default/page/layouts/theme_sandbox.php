<?php
/**
 * Theme sandbox layout
 *
 * @uses $vars['title']
 * @uses $vars['content']
 */

$sidebar = elgg_extract('sidebar', $vars, '');
$sidebar .= elgg_view_menu('theme_sandbox', [
	'sort_by' => 'name',
	'class' => 'nav-pills flex-column',
]);
$vars['sidebar'] = $sidebar;

echo elgg_view('page/layouts/default', $vars);