<?php
/**
 * Error layout
 *
 * @uses $vars['content'] Main content area
 * @uses $vars['title']   Optional title string
 * @uses $vars['class']   Additional class for the layout
 */

$class = 'elgg-layout-error';
if (isset($vars['class'])) {
	$class = "$class {$vars['class']}";
}
$vars['class'] = $class;

echo elgg_view('page/layouts/one_column', $vars);
