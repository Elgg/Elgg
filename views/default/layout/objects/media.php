<?php
/**
 * Elgg media element
 *
 * Common pattern where there is an image, icon, media object to the left
 * and a descriptive block of text to the left.
 * 
 * ---------------------------------------------------------------
 * |          |                                      |    alt    |
 * | picture  |               body                   |  picture  |
 * |  block   |               block                  |   block   |
 * |          |                                      | (optional)|
 * ---------------------------------------------------------------
 *
 * @uses $vars['body']        HTML content of the body block
 * @uses $vars['pict']        HTML content of the picture block
 * @uses $vars['pict_alt']    HTML content of the alternate picture block
 * @uses $vars['class']       Optional additional class for media element
 * @uses $vars['id']          Optional id for the media element
 */

$body = elgg_get_array_value('body', $vars, '');
$pict_block = elgg_get_array_value('pict', $vars, '');
$alt_pict_block = elgg_get_array_value('pict_alt', $vars, '');

$class = 'elgg-media';
$additional_class = elgg_get_array_value('class', $vars, '');
if ($additional_class) {
	$class = "$class $additional_class";
}

$id = '';
if (isset($vars['id'])) {
	$id = "id=\"{$vars['id']}\"";
}


$body = "<div class=\"elgg-body\">$body</div>";

if ($pict_block) {
	$pict_block = "<div class=\"elgg-pict\">$pict_block</div>";
}

if ($alt_pict_block) {
	$alt_pict_block = "<div class=\"elgg-pict-alt\">$alt_pict_block</div>";
}

echo <<<HTML
<div class="$class clearfix" $id>
	$pict_block$alt_pict_block$body
</div>
HTML;
