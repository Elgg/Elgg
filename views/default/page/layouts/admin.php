<?php
/**
 * Elgg Admin Area Canvas
 *
 * @uses $vars['content'] Content string
 * @uses $vars['sidebar'] Optional sidebar content
 * @uses $vars['title']   Optional title string
 */

$class = elgg_extract_class($vars, [
	'elgg-layout',
	'elgg-layout-admin',
]);
unset($vars['class']);

$vars['sidebar'] = elgg_view('admin/sidebar', $vars);

$sidebar = elgg_view('page/layouts/elements/sidebar', $vars);
$body = elgg_view('page/layouts/elements/body', $vars);

$class[] = $sidebar ? 'elgg-layout-one-sidebar' : 'elgg-layout-one-column';

echo elgg_format_element('div', ['class' => $class], $sidebar . $body);
