<?php
/**
 * Elgg tagcloud
 * Displays a tagcloud
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 * @uses $vars['tagcloud'] An array of stdClass objects with two elements: 'tag' (the text of the tag) and 'total' (the number of elements with this tag)
 * @uses $vars['value'] Sames as tagcloud
 * @uses $vars['type'] Entity type
 * @uses $vars['subtype'] Entity subtype
 */

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
	$cloud = "";
	$max = 0;
	foreach($vars['tagcloud'] as $tag) {
		if ($tag->total > $max) {
			$max = $tag->total;
		}
	}
	foreach($vars['tagcloud'] as $tag) {
		if (!empty($cloud)) {
			$cloud .= ", ";
		}
		// protecting against division by zero warnings
		$size = round((log($tag->total) / log($max + .0001)) * 100) + 30;
		if ($size < 60) {
			$size = 60;
		}
		$url = $vars['url'] . "pg/search/?q=". urlencode($tag->tag) . "&search_type=tags{$type}{$subtype}";
		$cloud .= "<a href=\"{$url}\" style=\"font-size: {$size}%\" title=\"".addslashes($tag->tag)." ({$tag->total})\" style=\"text-decoration:none;\">" . htmlentities($tag->tag, ENT_QUOTES, 'UTF-8') . "</a>";
	}
	echo $cloud;
}