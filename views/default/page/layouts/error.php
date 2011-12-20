<?php
/**
 * Error layout
 */

$class = 'elgg-layout-error';
if (isset($vars['class'])) {
	$class = "$class {$vars['class']}";
}
$vars['class'] = $class;

echo elgg_view('page/layouts/one_column', $vars);
