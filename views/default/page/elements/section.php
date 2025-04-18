<?php
/**
 * A wrapper to render a section of the page shell
 *
 * @uses $vars['section'] Section name (e.g. navbar, header etc)
 * @uses $vars['html']    Content of the element
 */

$html = elgg_extract('html', $vars);
if (empty($html)) {
	return;
}

$section_elements = [
	'body' => 'main',
	'topbar' => 'header',
	'footer' => 'footer',
];

$class = ['elgg-page-section'];

$section = elgg_extract('section', $vars);
if (!empty($section)) {
	$class[] = "elgg-page-{$section}";
}

$inner = elgg_format_element('div', ['class' => 'elgg-inner'], $html);

$element = elgg_extract($section, $section_elements, 'div');

$section_attributes = ['class' => $class];
if ($element === 'main') {
	$section_attributes['id'] = 'main-content';
}

echo elgg_format_element($element, $section_attributes, $inner);
