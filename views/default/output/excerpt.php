<?php
/**
 * Output view for elgg_get_excerpt
 *
 * @uses $vars['text'] The text to get the excerpt for
 * @uses $vars['num_chars'] The max number of characters of the excerpt
 * @uses $vars['suffix'] The suffix to be added if text is cut
 * @uses $vars['url'] URL of the Read more link
 */

$text = elgg_extract('text', $vars);
$text = trim(elgg_strip_tags($text));

$url = elgg_extract('url', $vars);

$suffix = elgg_extract('suffix', $vars);
if (!$suffix) {
	$suffix = $url ? elgg_echo('excerpt:readmore') : elgg_echo('excerpt:suffix');
}


$string_length = elgg_strlen($text);
$num_chars = (int) elgg_extract('num_chars', $vars, 250);

if ($string_length <= $num_chars) {
	echo $text;
	return;
}

$num_chars -= strlen($suffix) + 1;

// handle cases
$excerpt = elgg_substr($text, 0, $num_chars);
$space = elgg_strrpos($excerpt, ' ', 0);

// don't crop if can't find a space.
if ($space === false) {
	$space = $num_chars;
}
$excerpt = trim(elgg_substr($excerpt, 0, $space));

if ($url) {
	$suffix = elgg_view('output/url', [
		'text' => $suffix,
		'href' => $url,
		'class' => 'elgg-excerpt-read-more',
	]);
}

if ($string_length != elgg_strlen($excerpt)) {
	$excerpt .= ' ' . $suffix;
}

echo $excerpt;
