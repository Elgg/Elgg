<?php
/**
 * ECML Daily Motion support
 *
 * @package ECML
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */

$base_url = 'http://www.dailymotion.com/video/';
$src = (isset($vars['src'])) ? str_replace($base_url, '', $vars['src']) : FALSE;
$width = (isset($vars['width'])) ? $vars['width'] : 480;
$height = (isset($vars['height'])) ? $vars['height'] : 270;

if ($src) {
	list($vid, $name) = explode('_', $src);
	$url =  "http://www.dailymotion.com/swf/video/$vid";

	echo "
	<p>
	<object width=\"$width\" height=\"$height\">
	<param name=\"movie\" value=\"$url\"></param>
	<param name=\"allowFullScreen\" value=\"true\"></param>
	<param name=\"allowScriptAccess\" value=\"always\"></param>
	<embed type=\"application/x-shockwave-flash\" src=\"$url\" width=\"$width\" height=\"$height\" allowfullscreen=\"true\" allowscriptaccess=\"always\"></embed>
	</object>
	</p>
";
}