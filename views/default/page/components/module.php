<?php
/**
 * Elgg module element
 *
 * @uses $vars['type']         The type of module (main, info, popup, aside, etc.)
 * @uses $vars['title']        Optional title text (do not pass header with this option)
 * @uses $vars['header']       Optional HTML content of the header
 * @uses $vars['body']         HTML content of the body
 * @uses $vars['footer']       Optional HTML content of the footer
 * @uses $vars['class']        Optional additional class for module
 * @uses $vars['id']           Optional id for module
 * @uses $vars['show_inner']   Optional flag to leave out inner div (default: false)
 */

$type = elgg_extract('type', $vars, false);
$title = elgg_extract('title', $vars, '');
$header = elgg_extract('header', $vars, '');
$body = elgg_extract('body', $vars, '');
$footer = elgg_extract('footer', $vars, '');
$show_inner = elgg_extract('show_inner', $vars, false);

$class = 'elgg-module';
if ($type) {
	$class = "$class elgg-module-$type";
}
$additional_class = elgg_extract('class', $vars, '');
if ($additional_class) {
	$class = "$class $additional_class";
}

$id = '';
if (isset($vars['id'])) {
	$id = "id=\"{$vars['id']}\"";
}

if (isset($vars['header'])) {
	$header = "<div class=\"elgg-head\">$header</div>";
} elseif ($title) {
	$header = "<div class=\"elgg-head\"><h3>$title</h3></div>";
}

$body = "<div class=\"elgg-body\">$body</div>";

if ($footer) {
	$footer = "<div class=\"elgg-foot\">$footer</div>";
}

$contents = $header . $body . $footer;
if ($show_inner) {
	$contents = "<div class=\"elgg-inner\">$contents</div>";
}

echo "<div class=\"$class\" $id>$contents</div>";
