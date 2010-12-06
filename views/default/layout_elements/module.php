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
 * @uses $vars['header_class'] Optional additional class for header
 * @uses $vars['body_class']   Optional additional class for body
 * @uses $vars['footer_class'] Optional additional class for footer
 * @uses $vars['skip_inner']   Optional flag to leave out inner div
 */

$title = elgg_get_array_value('title', $vars, '');
$header = elgg_get_array_value('header', $vars, '');
$body = elgg_get_array_value('body', $vars, '');
$footer = elgg_get_array_value('footer', $vars, '');
$skip_inner = elgg_get_array_value('skip_inner', $vars, false);

$class = 'elgg-module';
$additional_class = elgg_get_array_value('class', $vars, '');
if ($additional_class) {
	$class = "$class $additional_class";
}

$id = '';
if (isset($vars['id'])) {
	$id = "id=\"{$vars['id']}\"";
}


$header_class = 'elgg-header';
$additional_class = elgg_get_array_value('header_class', $vars, '');
if ($additional_class) {
	$header_class = "$header_class $additional_class";
}

if (isset($vars['header'])) {
	if ($vars['header']) {
		$header = "<div class=\"$header_class\">$header</div>";
	}
} else {
	$header = "<div class=\"$header_class\"><h3>$title</h3></div>";
}

$body_class = 'elgg-body';
$additional_class = elgg_get_array_value('body_class', $vars, '');
if ($additional_class) {
	$body_class = "$body_class $additional_class";
}
$body = "<div class=\"$body_class\">$body</div>";


$footer_class = 'elgg-footer';
$additional_class = elgg_get_array_value('footer_class', $vars, '');
if ($additional_class) {
	$body_class = "$footer_class $additional_class";
}

if (isset($vars['footer'])) {
	if ($vars['footer']) {
		$header = "<div class=\"$footer_class\">$footer</div>";
	}
}

$contents = $header . $body . $footer;
if (!$skip_inner) {
	$contents = "<div class=\"elgg-inner\">$contents</div>";
}

echo "<div class=\"$class\" $id>$contents</div>";
