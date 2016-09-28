<?php
/**
 * Elgg title element
 *
 * @uses $vars['tag']   The tag name to wrap the title (default: h2)
 * @uses $vars['title'] The page title
 * @uses $vars['class'] Optional class for heading
 */

$title = elgg_extract('title', $vars);
if (!is_string($title) || $title === '') {
	return;
}

$attributes = [
	'class' => elgg_extract_class($vars),
];

$tag = elgg_extract('tag', $vars, 'h2', false);

echo elgg_format_element($tag, $attributes, $title);
