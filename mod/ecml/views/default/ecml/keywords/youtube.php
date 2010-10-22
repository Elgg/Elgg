<?php
/**
 * ECML Youtube support
 *
 * @package ECML
 */

$src = (isset($vars['src'])) ? $vars['src'] : FALSE;
$width = (isset($vars['width'])) ? $vars['width'] : 480;
$height = (isset($vars['height'])) ? $vars['height'] : 385;

// need to extract the video id.
// the src arg can take a full url or an id.
// assume if no youtube.com that it's an id.
if (strpos($src, 'youtube.com') === FALSE) {
	$vid = $src;
} else {
	// grab the v param
	if ($parts = parse_url($src)) {
		if (isset($parts['query'])) {
			parse_str($parts['query'], $query_arr);
			$vid = (isset($query_arr['v'])) ? $query_arr['v'] : FALSE;
		}
	}
}

if ($vid) {
	$movie_url = "http://www.youtube.com/v/$vid";

	echo "
<object width=\"$width\" height=\"$height\">
	<param name=\"movie\" value=\"$movie_url\"></param>
	<param name=\"allowFullScreen\" value=\"true\"></param>
	<param name=\"allowscriptaccess\" value=\"always\"></param>

	<embed src=\"$movie_url\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"$width\" height=\"$height\"></embed>
</object>
	";
}