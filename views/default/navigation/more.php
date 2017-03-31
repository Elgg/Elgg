<?php

/**
 * More link
 * Uses all vars accepted by output/url
 *
 * @uses $vars['#class'] Wrapper class
 */

$class = (array) elgg_extract('#class', $vars, []);
unset($vars['#class']);

$link = elgg_view('output/url', $vars);
if (!$link) {
	return;
}

$class[] = 'elgg-list-more';

echo elgg_format_element('div', [
	'class' => $class,
], $link);