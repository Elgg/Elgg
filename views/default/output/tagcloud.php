<?php
/**
 * Elgg tagcloud
 * Displays a tagcloud
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['tagcloud'] An array of stdClass objects with two elements: 'tag' (the text of the tag) and 'total' (the number of elements with this tag)
 * @uses $vars['value'] Sames as tagcloud
 * @uses $vars['type'] Entity type
 * @uses $vars['subtype'] Entity subtype
 */

$context = elgg_get_context();

if (!empty($vars['subtype'])) {
	$subtype = "&entity_subtype=" . urlencode($vars['subtype']);
} else {
	$subtype = "";
}
if (!empty($vars['type'])) {
	$type = "&entity_type=" . urlencode($vars['type']);
} else {
	$type = "";
}

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
	
	$cloud = '';
	foreach ($vars['tagcloud'] as $tag) {
		if ($cloud != '') {
			$cloud .= ', ';
		}
		// protecting against division by zero warnings
		$size = round((log($tag->total) / log($max + .0001)) * 100) + 30;
		if ($size < 100) {
			$size = 100;
		}
		$url = elgg_get_site_url()."pg/search/?q=". urlencode($tag->tag) . "&search_type=tags$type$subtype";
		$url = elgg_format_url($url);
		$cloud .= "<a href=\"$url\" style=\"font-size: $size%\" title=\"".addslashes($tag->tag)." ($tag->total)\">" . htmlspecialchars($tag->tag, ENT_QUOTES, 'UTF-8') . "</a>";
	}
		
	if ($context != 'tags') {
		$text = elgg_echo('tagcloud:allsitetags');
		$cloud .= '<p class="small">';
		$cloud .= '<span class="elgg-icon elgg-icon-tag"></span>';
		$cloud .= "<a href=\"" . elgg_get_site_url() . "pg/tags\">$text</a>";
		$cloud .= '</p>';
	}
	
	$cloud .= elgg_view('tagcloud/extend');

	if ($context != 'tags') {
		echo elgg_view_module('aside', elgg_echo('tagcloud'), $cloud, array('class' => 'elgg-tagcloud'));
	} else {
		echo "<div class=\"elgg-tagcloud\">$cloud</div>";
	}
}
