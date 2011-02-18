<?php
/**
 * Elgg module element
 *
 * @uses $vars['title']        Title text
 * @uses $vars['header']       HTML content of the header
 * @uses $vars['body']         HTML content of the body
 * @uses $vars['footer']       HTML content of the footer
 * @uses $vars['class']        Optional additional class for module
 * @uses $vars['id']           Optional id for module
 * @uses $vars['show_inner']   Optional flag to leave out inner div (default: false)
 */

$title = elgg_extract('title', $vars, '');
$header = elgg_extract('header', $vars, '');
$body = elgg_extract('body', $vars, '');
$footer = elgg_extract('footer', $vars, '');
$show_inner = elgg_extract('show_inner', $vars, false);

$class = 'elgg-module';
$additional_class = elgg_extract('class', $vars, '');
if ($additional_class) {
	$class = "$class $additional_class";
}

$id = '';
if (isset($vars['id'])) {
	$id = "id=\"{$vars['id']}\"";
}

if (isset($vars['header'])) {
	if ($vars['header']) {
		$header = "<div class=\"elgg-head\">$header</div>";
	}
} else {
	$header = "<div class=\"elgg-head\"><h3>$title</h3></div>";
}

$body = "<div class=\"elgg-body\">$body</div>";

if (isset($vars['footer'])) {
	if ($vars['footer']) {
		$footer = "<div class=\"elgg-foot\">$footer</div>";
	}
}

$contents = $header . $body . $footer;
if ($show_inner) {
	$contents = "<div class=\"elgg-inner\">$contents</div>";
}

echo "<div class=\"$class\" $id>$contents</div>";
