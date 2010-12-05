<?php
/**
 * Elgg media element
 *
 * ---------------------------------------------------
 * |          |                                      |
 * |  icon    |               body                   |
 * |  block   |               block                  |
 * |          |                                      |
 * ---------------------------------------------------
 *
 * @uses $vars['body']        HTML content of the body block
 * @uses $vars['icon']        HTML content of the icon block
 * @uses $vars['class']       Optional additional class for media element
 * @uses $vars['body_class']  Optional additional class for body block
 * @uses $vars['icon_class']   Optional additional class for icon block
 */

$body = elgg_get_array_value('body', $vars, '');
$icon_block = elgg_get_array_value('icon', $vars, '');

$class = 'elgg-media';
$additional_class = elgg_get_array_value('class', $vars, '');
if ($additional_class) {
	$class = "$class $additional_class";
}

$body_class = 'elgg-body';
$additional_class = elgg_get_array_value('body_class', $vars, '');
if ($additional_class) {
	$body_class = "$body_class $additional_class";
}
$body = "<div class=\"$body_class\">$body</div>";

$icon_class = 'elgg-icon';
$additional_class = elgg_get_array_value('icon_class', $vars, '');
if ($additional_class) {
	$icon_class = "$icon_class $additional_class";
}
if ($icon_block) {
	$icon_block = "<div class=\"$icon_class\">$icon_block</div>";
}

echo <<<HTML
<div class="$class clearfix">
	$icon_block$body
</div>
HTML;
