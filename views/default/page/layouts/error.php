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
]);
unset($vars['class']);

$vars['filter'] = false;

echo elgg_format_element('div', ['class' => $class], elgg_view('page/layouts/elements/body', $vars));
