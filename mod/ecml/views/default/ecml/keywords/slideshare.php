<?php
/**
 * ECML Slideshare support
 *
 * @package ECML
 */

// this wants the "wordpress.com" embed code.
// to make life easier on users, don't require them to add the "s
// and just chop out the id= bit here from the full attr list

$id = str_replace('id=', '', $vars['ecml_params_string']);
$width = (isset($vars['width'])) ? $vars['width'] : 450;
$height = (isset($vars['height'])) ? $vars['height'] : 369;

if ($id) {
	// @todo need to check if the & should be encoded.

	$slide_url = "http://static.slideshare.net/swf/ssplayer2.swf?id=$id";

	echo "
<object type=\"application/x-shockwave-flash\" wmode=\"opaque\" data=\"$slide_url\" width=\"$width\" height=\"$height\">
	<param name=\"movie\" value=\"$slide_url\" />
	<param name=\"allowFullScreen\" value=\"true\" />
	<param name=\"allowScriptAccess\" value=\"always\" />

	<embed src=\"$slide_url\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"$width\" height=\"$height\"></embed>
</object>
";
}