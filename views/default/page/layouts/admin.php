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

$vars['breadcrumbs'] = false;

$vars['sidebar'] = elgg_view('admin/sidebar', $vars);

$header = elgg_view('page/layouts/elements/header', $vars);
$sidebar = elgg_view('page/layouts/elements/sidebar', $vars);
$body = elgg_view('page/layouts/elements/body', $vars);

if ($sidebar) {
	$class[] = 'elgg-layout-one-sidebar';
} else {
	$class[] = 'elgg-layout-one-column';
}

$layout = elgg_format_element('div', ['class' => 'elgg-layout-columns'], $sidebar . $body);

echo elgg_format_element('div', ['class' => $class], $header . $layout);
