<?php
/**
 * Elgg media element
 *
 * ---------------------------------------------------
 * |          |                                      |
 * | picture  |               body                   |
 * |  block   |               block                  |
 * |          |                                      |
 * ---------------------------------------------------
 *
 * @uses $vars['body']        HTML content of the body block
 * @uses $vars['pict']        HTML content of the picture block
 * @uses $vars['class']       Optional additional class for media element
 * @uses $vars['id']          Optional id for the media element
 */

$body = elgg_get_array_value('body', $vars, '');
$pict_block = elgg_get_array_value('pict', $vars, '');

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

echo <<<HTML
<div class="$class clearfix" $id>
	$pict_block$body
</div>
HTML;
