<?php
/**
 * A wrapper to render a section of the page shell
 *
 * @uses $vars['section'] Section name (e.g. navbar, header etc)
 * @uses $vars['html']    Content of the element
 */

$section = elgg_extract('section', $vars);
$html = elgg_extract('html', $vars);

if (!empty($section) && elgg_view_exists("page/elements/{$section}/before")) {
	echo elgg_view_deprecated("page/elements/{$section}/before", $vars, 'Prepend the correct page/elements/<section>', '5.1');
}

if (!empty($html)) {
	$section_elements = [
		'body' => 'main',
		'topbar' => 'header',
		'footer' => 'footer',
	];
	
	$class = ['elgg-page-section'];
	if (!empty($section)) {
		$class[] = "elgg-page-{$section}";
	}
	
	$inner = elgg_format_element('div', ['class' => 'elgg-inner'], $html);
	
	$element = elgg_extract($section, $section_elements, 'div');
	
	echo elgg_format_element($element, ['class' => $class], $inner);
}

if (!empty($section) && elgg_view_exists("page/elements/{$section}/after")) {
	echo elgg_view_deprecated("page/elements/{$section}/after", $vars, 'Append the correct page/elements/<section>', '5.1');
}
