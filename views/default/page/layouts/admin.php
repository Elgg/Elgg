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

$class = elgg_extract_class($vars, [
	'elgg-layout',
	'elgg-layout-one-sidebar',
	'elgg-layout-admin',
	'clearfix',
]);
unset($vars['class']);

$vars['breadcrumbs'] = false;

$vars['sidebar'] = elgg_view('admin/sidebar', $vars);

$sidebar = elgg_view('page/layouts/elements/sidebar', $vars);
$body = elgg_view('page/layouts/elements/body', $vars);

echo elgg_format_element('div', [
	'class' => $class,
], $sidebar . $body);
