<?php
/**
 * ECML Blip.tv support
 *
 * @package ECML
 */

$params = str_replace('?', '', $vars['ecml_params_string']);
$width = (isset($vars['width'])) ? $vars['width'] : 425;
$height = (isset($vars['height'])) ? $vars['height'] : 350;

if ($params) {
	$embed_src = elgg_http_add_url_query_elements($src, array('output' => 'embed'));
	$link_href = elgg_http_add_url_query_elements($src, array('source' => 'embed'));

	echo "
<p><script type='text/javascript' src='http://blip.tv/syndication/write_player?skin=js&cross_post_destination=-1&view=full_js&$params'></script></p>
";
}