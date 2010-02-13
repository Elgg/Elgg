<?php
/**
 * Elgg tags
 * Displays a list of tags, separated by commas
 *
 * Tags can be a single string (for one tag) or an array of strings
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 * @uses $vars['tags'] The tags to display
 * @uses $vars['tagtype'] The tagtype, optionally
 */

if (!empty($vars['subtype'])) {
	$subtype = "&subtype=" . urlencode($vars['subtype']);
} else {
	$subtype = "";
}
if (!empty($vars['object'])) {
	$object = "&object=" . urlencode($vars['object']);
} else {
	$object = "";
}

if (empty($vars['tags']) && !empty($vars['value'])) {
	$vars['tags'] = $vars['value'];
}

$tag_names_str = '';
if (isset($vars['tag_names'])) {
	if (is_array($vars['tag_names'])) {
		foreach ($vars['tag_names'] as $tag_name) {
			$tag_names_str .= "&tag_names[]=$tag_name";
		}
	} else {
		$tag_names_str = "&tag_names={$vars['tag_names']}";
	}
}

if (!empty($vars['tags'])) {
	$tagstr = "";
	if (!is_array($vars['tags'])) {
		$vars['tags'] = array($vars['tags']);
	}

	foreach($vars['tags'] as $tag) {
		if (!empty($tagstr)) {
			$tagstr .= ", ";
		}
		if (!empty($vars['type'])) {
			$type = "&type={$vars['type']}";
		} else {
			$type = "";
		}
		if (is_string($tag)) {
			$tagstr .= "<a rel=\"tag\" href=\"{$vars['url']}pg/search/?q=".urlencode($tag) . "&search_type=tags{$type}{$subtype}{$object}{$tag_names_str}\">" . htmlentities($tag, ENT_QUOTES, 'UTF-8') . "</a>";
		}
	}
	echo $tagstr;
}
