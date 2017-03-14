<?php

/**
 * A wrapper to render a section of the page shell
 *
 * @uses $vars['section'] Section name (e.g. navbar, header etc)
 * @uses $vars['html']    Content of the element
 */
$section = elgg_extract('section', $vars);
$html = elgg_extract('html', $vars);

if ($section && elgg_view_exists("page/elements/$section/before")) {
	echo elgg_view("page/elements/$section/before", $vars);
}

if (!empty($html)) {
	$class = ['elgg-page-section'];
	if ($section) {
		$class[] = "elgg-page-$section";
	}

	$inner = elgg_format_element('div', [
		'class' => 'elgg-inner',
			], $html);

	echo elgg_format_element('div', [
		'class' => $class,
			], $inner);
}

if ($section && elgg_view_exists("page/elements/$section/after")) {
	echo elgg_view("page/elements/$section/after", $vars);
}
