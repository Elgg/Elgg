<?php
/**
 * Elgg module element
 *
 * @uses $vars['type']         The type of module (main, info, popup, aside, etc.)
 * @uses $vars['title']        Optional title text (do not pass header with this option)
 * @uses $vars['menu']         Module menu do display in the header
 * @uses $vars['header']       Optional HTML content of the header
 * @uses $vars['body']         HTML content of the body
 * @uses $vars['footer']       Optional HTML content of the footer
 * @uses $vars['class']        Optional additional class for module
 * @uses $vars['id']           Optional id for module
 * @uses $vars['show_inner']   Optional flag to leave out inner div (default: false)
 */

$type = elgg_extract('type', $vars);
$title = elgg_extract('title', $vars);
$body = elgg_extract('body', $vars);
$footer = elgg_extract('footer', $vars);
$show_inner = elgg_extract('show_inner', $vars, false);

$attrs = [
	'id' => elgg_extract('id', $vars),
	'class' => elgg_extract_class($vars, 'elgg-module'),
];

if (!elgg_is_empty($type)) {
	$attrs['class'][] = "elgg-module-$type";
}

$header = elgg_extract('header', $vars);
if (!elgg_is_empty($title)) {
	$header = elgg_format_element('h3', [], $title);
}

if ($header !== null) {
	$menu = elgg_extract('menu', $vars);
	if ($menu) {
		$header .= elgg_format_element('div', ['class' => 'elgg-module-menu'], $menu);
	}

	$header = elgg_format_element('div', ['class' => 'elgg-head'], $header);
}

$body = elgg_format_element('div', ['class' => 'elgg-body'], $body);
if (!elgg_is_empty($footer)) {
	$footer = elgg_format_element('div', ['class' => 'elgg-foot'], $footer);
}

$contents = $header . $body . $footer;
if ($show_inner) {
	$contents = elgg_format_element('div', ['class' => 'elgg-inner'], $contents);
}

echo elgg_format_element('div', $attrs, $contents);
