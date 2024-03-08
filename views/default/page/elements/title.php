<?php
/**
 * Elgg title element
 *
 * @uses $vars['tag']   The tag name to wrap the title (default: h1)
 * @uses $vars['title'] The page title
 * @uses $vars['class'] Optional class for heading
 */

$title = elgg_extract('title', $vars);
if (!is_string($title) || $title === '') {
	return;
}

$tag = (string) elgg_extract('tag', $vars, 'h1', false);

echo elgg_format_element($tag, ['class' => elgg_extract_class($vars)], elgg_get_excerpt($title, 250));
