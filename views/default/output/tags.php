<?php
/**
 * Elgg tags
 * Tags can be a single string (for one tag) or an array of strings
 *
 * @uses $vars['value']      Array of tags or a string
 * @uses $vars['type']       The entity type, optional
 * @uses $vars['subtype']    The entity subtype, optional
 * @uses $vars['entity']     Optional. Entity whose tags are being displayed (metadata ->tags)
 * @uses $vars['list_class'] Optional. Additional classes to be passed to <ul> element
 * @uses $vars['item_class'] Optional. Additional classes to be passed to <li> elements
 * @uses $vars['icon_class'] Optional. Additional classes to be passed to tags icon image
 * @uses $vars['base_url']   Base URL for tag link, defaults to search URL
 */

if (isset($vars['entity'])) {
	$vars['tags'] = $vars['entity']->tags;
	unset($vars['entity']);
}

if (empty($vars['tags']) && (!empty($vars['value']) || $vars['value'] === 0 || $vars['value'] === '0')) {
	$vars['tags'] = $vars['value'];
}

if (empty($vars['tags']) && $vars['value'] !== 0 && $vars['value'] !== '0') {
	return;
}

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
$list_items = ''; 

$params = $vars;
foreach($vars['tags'] as $tag) {
	if (is_string($tag) && strlen($tag) > 0) {
		$params['value'] = $tag;

		$list_items .= "<li class=\"$item_class\">";
		$list_items .= elgg_view('output/tag', $params);
		$list_items .= '</li>';
	}
}

if (empty($list_items)) {
	return;
}

$icon = elgg_view_icon('tag', $icon_class);

$list = <<<___HTML
	<div class="clearfix">
		<ul class="$list_class">
			<li>$icon</li>
			$list_items
		</ul>
	</div>
___HTML;

echo $list;
