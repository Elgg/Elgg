<?php
/**
 * Elgg module
 *
 * @uses $vars['title']        Title text
 * @uses $vars['header']       HTML content of the header
 * @uses $vars['body']         HTML content of the body
 * @uses $vars['footer']       HTML content of the footer
 * @uses $vars['class']        Optional additional class for module
 * @uses $vars['header_class'] Optional additional class for header
 * @uses $vars['body_class']   Optional additional class for body
 */

$title = elgg_get_array_value('title', $vars, '');
$header = elgg_get_array_value('header', $vars, '');
$body = elgg_get_array_value('body', $vars, '');
$footer = elgg_get_array_value('footer', $vars, '');

$class = 'elgg-module';
$additional_class = elgg_get_array_value('class', $vars, '');
if ($additional_class) {
	$class = "$class $additional_class";
}

$header_class = 'elgg-header';
$additional_class = elgg_get_array_value('header_class', $vars, '');
if ($additional_class) {
	$header_class = "$header_class $additional_class";
}
$header = "<div class=\"$header_class\"><h3>$title</h3></div>";
//if ($header) {
//	$header = "<div class=\"$header_class\">$header</div>";
//}

$body_class = 'elgg-body';
$additional_class = elgg_get_array_value('body_class', $vars, '');
if ($additional_class) {
	$body_class = "$body_class $additional_class";
}
$body = "<div class=\"$body_class\">$body</div>";


if ($footer) {
	$footer = "<div class=\"elgg-footer\">$footer</div>";
}

echo <<<HTML
<div class="$class">
	<div class="elgg-inner">
		$header
		$body
		$footer
	</div>
</div>
HTML;
