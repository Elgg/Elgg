<?php
/**
 * Elgg module layout
 *
 * @uses $vars['header'] HTML content of the header
 * @uses $vars['body']   HTML content of the body
 * @uses $vars['footer'] HTML content of the footer
 * @uses $vars['class']  Optional additional class for module
 */

$header = elgg_get_array_value('header', $vars, '');
$body = elgg_get_array_value('body', $vars, '');
$footer = elgg_get_array_value('footer', $vars, '');

$class = 'elgg-module';
$additional_class = elgg_get_array_value('class', $vars, '');
if ($additional_class) {
	$class = "$class $additional_class";
}

if ($header) {
	$header = "<div class=\"elgg-header\">$header</div>";
}

if ($footer) {
	$footer = "<div class=\"elgg-footer\">$footer</div>";
}

echo <<<HTML
<div class="$class">
	<div class="elgg-inner">
		$header
		<div class="elgg-body">
			$body
		</div>
		$footer
	</div>
</div>
HTML;
