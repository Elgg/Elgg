<?php
/**
 * Output view for elgg_get_excerpt
 *
 * @uses $vars['text'] The text to get the excerpt for
 * @uses $vars['num_chars'] The max number of characters of the excerpt
 * @uses $vars['suffix'] The suffix to be added if text is cut
 */

$text = elgg_extract('text', $vars);
$text = trim(elgg_strip_tags($text));

$suffix = elgg_extract('suffix', $vars, '...');

$string_length = elgg_strlen($text);
$num_chars = (int) elgg_extract('num_chars', $vars, 250);

if ($string_length <= $num_chars) {
	echo $text;
	return;
}

// handle cases
$excerpt = elgg_substr($text, 0, $num_chars);
$space = elgg_strrpos($excerpt, ' ', 0);

// don't crop if can't find a space.
if ($space === false) {
	$space = $num_chars;
}
$excerpt = trim(elgg_substr($excerpt, 0, $space));

if ($string_length != elgg_strlen($excerpt)) {
	$excerpt .= $suffix;
}

echo $excerpt;
