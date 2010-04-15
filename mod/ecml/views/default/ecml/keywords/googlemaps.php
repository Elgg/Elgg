<?php
/**
 * ECML Google Maps support
 *
 * @package ECML
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */

$src = (isset($vars['src'])) ? $vars['src'] : FALSE;
$width = (isset($vars['width'])) ? $vars['width'] : 425;
$height = (isset($vars['height'])) ? $vars['height'] : 350;

if ($src) {
	$embed_src = elgg_http_add_url_query_elements($src, array('output' => 'embed'));
	$link_href = elgg_http_add_url_query_elements($src, array('source' => 'embed'));

	echo "

<iframe width=\"$width\" height=\"$height\" frameborder=\"0\" scrolling=\"no\" marginheight=\"0\" marginwidth=\"0\" src=\"$embed_src\"></iframe>
<br />
<small>
	<a href=\"$link_href\" style=\"color:#0000FF;text-align:left\">
		" . elgg_echo('ecml:googlemaps:view_larger_map') . "
	</a>
</small>

";
}