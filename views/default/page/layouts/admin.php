<?php
/**
 * Elgg Admin Area Canvas
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['content'] Content string
 * @uses $vars['sidebar'] Optional sidebar content
 * @uses $vars['title']   Optional title string
 */

elgg_push_breadcrumb(elgg_echo('admin'), 'admin');

$vars['class'] = elgg_extract_class($vars, [
	'elgg-layout',
	'elgg-layout-one-sidebar',
	'elgg-layout-admin',
	'clearfix',
]);
unset($vars['class']);

$vars['owner_block'] = false;
$vars['page_menu_params']['show_section_headers'] = true;

echo elgg_view('page/layouts/default', $vars);