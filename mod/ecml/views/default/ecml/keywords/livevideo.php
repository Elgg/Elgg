<?php
/**
 * ECML Live Video support
 *
 * @package ECML
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */

$base_url = 'http://www.livevideo.com/video/';
$src = (isset($vars['src'])) ? str_replace($base_url, '', $vars['src']) : FALSE;
$width = (isset($vars['width'])) ? $vars['width'] : 445;
$height = (isset($vars['height'])) ? $vars['height'] : 369;

if ($src) {
	$parts = explode('/', $src);
	$vid = $parts[0];
	
	// it automatically autostarts, but not passing it causes control issues
	$url =  "http://www.livevideo.com/flvplayer/embed/$vid&autoStart=1";

	echo "
	<p>
	<embed src=\"$url\" type=\"application/x-shockwave-flash\" quality=\"high\" WIDTH=\"$width\" HEIGHT=\"$height\" wmode=\"transparent\"></embed>
	</p>
";
}