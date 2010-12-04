<?php
/**
 * Elgg media element
 *
 * ---------------------------------------------------
 * |          |                                      |
 * |  image   |               body                   |
 * |  block   |               block                  |
 * |          |                                      |
 * ---------------------------------------------------
 *
 * @uses $vars['body']        HTML content of the body block
 * @uses $vars['img']         HTML content of the image block
 * @uses $vars['class']       Optional additional class for media element
 * @uses $vars['body_class']  Optional additional class for body block
 * @uses $vars['img_class']   Optional additional class for image block
 */

$body = elgg_get_array_value('body', $vars, '');
$image_block = elgg_get_array_value('img', $vars, '');

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

$img_class = 'elgg-img';
$additional_class = elgg_get_array_value('img_class', $vars, '');
if ($additional_class) {
	$img_class = "$img_class $additional_class";
}
if ($image_block) {
	$image_block = "<div class=\"$img_class\">$image_block</div>";
}

echo <<<HTML
<div class="$class clearfix">
	$image_block$body
</div>
HTML;
