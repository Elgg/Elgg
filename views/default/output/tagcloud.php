<?php
/**
 * Elgg tagcloud
 * Displays a tagcloud. Accepts all output/tag options
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['tagcloud'] An array of stdClass objects with two elements: 'tag' (the text of the tag) and 'total' (the number of elements with this tag)
 * @uses $vars['value'] Sames as tagcloud
 */

if (empty($vars['tagcloud']) && !empty($vars['value'])) {
	$vars['tagcloud'] = $vars['value'];
}

if (!empty($vars['tagcloud']) && is_array($vars['tagcloud'])) {
	
	$counter = 0;
	$max = 0;
	
	foreach ($vars['tagcloud'] as $tag) {
		if ($tag->total > $max) {
			$max = $tag->total;
		}
	}

	$params = $vars;
	unset($params['tagcloud']);

	$tags = array();

	foreach ($vars['tagcloud'] as $tag) {

		$params['value'] = $tag->tag;

		// protecting against division by zero warnings
		$size = round((log($tag->total) / log($max + .0001)) * 100) + 30;
		if ($size < 100) {
			$size = 100;
		}
		$params['style'] = "font-size: $size%;";
		$params['title'] = "$tag->tag ($tag->total)";

		$tags[] = elgg_view('output/tag', $params);
	}
	
	$cloud = implode(', ', $tags);
	
	$cloud .= elgg_view('tagcloud/extend');

	echo "<div class=\"elgg-tagcloud\">$cloud</div>";
}
