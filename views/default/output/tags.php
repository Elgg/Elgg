<?php
/**
 * Elgg tags
 * Tags can be a single string (for one tag) or an array of strings
 *
 * @uses $vars['value']   Array of tags or a string
 * @uses $vars['type']    The entity type, optional
 * @uses $vars['subtype'] The entity subtype, optional
 * @uses $vars['entity']  Optional. Entity whose tags are being displayed (metadata ->tags)
 * @uses $vars['list_class'] Optional. Additional classes to be passed to <ul> element
 * @uses $vars['item_class'] Optional. Additional classes to be passed to <li> elements
 * @uses $vars['icon_class'] Optional. Additional classes to be passed to tags icon image
 */

if (isset($vars['entity'])) {
	$vars['tags'] = $vars['entity']->tags;
	unset($vars['entity']);
}

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

if (empty($vars['tags']) && isset($vars['entity'])) {
	$vars['tags'] = $vars['entity']->tags;
}

if (!empty($vars['tags'])) {
	if (!is_array($vars['tags'])) {
		$vars['tags'] = array($vars['tags']);
	}

	$list_class = "elgg-tags";
	if (isset($vars['list_class'])) {
		$list_class = "$list_class {$vars['list_class']}";
	}

	$item_class = "elgg-tag";
	if (isset($vars['item_class'])) {
		$item_class = "$item_class {$vars['item_class']}";
	}

	$icon_class = elgg_extract('icon_class', $vars);
	$list_items = '<li>' . elgg_view_icon('tag', $icon_class) . '</li>';

	foreach($vars['tags'] as $tag) {
		if (!empty($vars['type'])) {
			$type = "&type={$vars['type']}";
		} else {
			$type = "";
		}
		$url = elgg_get_site_url() . 'search?q=' . urlencode($tag) . "&search_type=tags{$type}{$subtype}{$object}";
		if (is_string($tag)) {
			$list_items .= "<li class=\"$item_class\">";
			$list_items .= elgg_view('output/url', array('href' => $url, 'text' => $tag, 'rel' => 'tag'));
			$list_items .= '</li>';
		}
	}

	$list = <<<___HTML
		<div class="clearfix">
			<ul class="$list_class">
				$list_items
			</ul>
		</div>
___HTML;

	echo $list;
}

