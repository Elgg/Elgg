<?php
/**
 * ECML vimeo support
 *
 * @package ECML
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */

$src = (isset($vars['src'])) ? $vars['src'] : FALSE;
$width = (isset($vars['width'])) ? $vars['width'] : 480;
$height = (isset($vars['height'])) ? $vars['height'] : 385;

// need to extract the video id.
// the src arg can take a full url or an id.
// assume if no youtube.com that it's an id.
if (strpos($src, 'vimeo.com') === FALSE) {
	$vid = $src;
} else {
	// we love vimeo.
	list($address, $vid) = explode('vimeo.com/', $src);
}

if ($vid) {
	$movie_url = "http://vimeo.com/moogaloop.swf?";
	$query = array('clip_id' => $vid, 'server' => 'vimeo.com');

	$params = array(
		'show_title' => 1,
		'show_byline' => 1,
		'show_portrait' => 0,
		'color' => '',
		'fullscreen' => 1
	);

	foreach ($params as $param => $default) {
		$query[$param] = (isset($vars[$param])) ? $vars[$param] : $default;
	}

	$query_str = http_build_query($query);
	$movie_url .= $query_str;

	echo "
<object width=\"$width\" height=\"$height\">
	<param name=\"allowfullscreen\" value=\"true\" />
	<param name=\"allowscriptaccess\" value=\"always\" />
	<param name=\"movie\" value=\"$movie_url\" />
	<embed src=\"$movie_url\" type=\"application/x-shockwave-flash\" allowfullscreen=\"true\" allowscriptaccess=\"always\" width=\"$width\" height=\"$height\">
	</embed>
</object>
	";
}