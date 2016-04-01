<?php
/**
 * Elgg title element
 *
 * @uses $vars['title'] The page title
 * @uses $vars['class'] Optional class for heading
 */

$title = elgg_extract('title', $vars);
if (!is_string($title) || $title === '') {
	return;
}

$attributes = [];
$class = elgg_extract('class', $vars);
if (!empty($class)) {
	$attributes['class'] = $class;
}

echo elgg_format_element('h2', $attributes, $title);
