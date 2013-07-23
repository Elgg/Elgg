<?php
/**
 * Elgg default layout
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['content'] Content string
 * @uses $vars['class']   Additional class to apply to layout
 * @uses $vars['nav']     Optional override of the page nav (default: breadcrumbs)
 * @uses $vars['title']   Optional title for main content area
 * @uses $vars['header']  Optional override for the header
 * @uses $vars['footer']  Optional footer
 */

$class = 'elgg-layout elgg-layout-default clearfix';
if (isset($vars['class'])) {
	$class = "$class {$vars['class']}";
}

echo "<div class=\"$class\">";

echo elgg_extract('nav', $vars, elgg_view('navigation/breadcrumbs'));

echo elgg_view('page/layouts/elements/header', $vars);

echo $vars['content'];

// @deprecated 1.8
if (isset($vars['area1'])) {
	echo $vars['area1'];
}

echo elgg_view('page/layouts/elements/footer', $vars);

echo '</div>';
