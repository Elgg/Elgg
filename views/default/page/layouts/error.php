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
	'clearfix',
]);
unset($vars['class']);

$vars['breadcrumbs'] = false;

$body = elgg_view('page/layouts/elements/body', $vars);

echo elgg_format_element('div', [
	'class' => $class,
], $body);
