<?php
/**
 * Error layout
 *
 * @uses $vars['content'] Main content area
 * @uses $vars['title']   Optional title string
 * @uses $vars['class']   Additional class for the layout
 */

$class = elgg_extract_class($vars, [
	'elgg-layout',
	'elgg-layout-one-column',
	'elgg-layout-error',
	'clearfix',
]);
unset($vars['class']);

$vars['breadcrumbs'] = false;
$vars['filter'] = false;

$header = elgg_view('page/layouts/elements/header', $vars);
$body = elgg_view('page/layouts/elements/body', $vars);

$layout = elgg_format_element('div', [
	'class' => 'elgg-layout-columns',
], $body);

echo elgg_format_element('div', [
	'class' => $class,
], $header . $layout);
