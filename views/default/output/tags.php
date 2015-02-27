<?php
/**
 * Elgg tags
 * Tags can be a single string (for one tag) or an array of strings. Accepts all output/tag options
 *
 * @uses $vars['value']      Array of tags or a string
 * @uses $vars['entity']     Optional. Entity whose tags are being displayed (metadata ->tags)
 * @uses $vars['list_class'] Optional. Additional classes to be passed to <ul> element
 * @uses $vars['item_class'] Optional. Additional classes to be passed to <li> elements
 * @uses $vars['icon_class'] Optional. Additional classes to be passed to tags icon image
 */

if (isset($vars['entity'])) {
	$vars['tags'] = $vars['entity']->tags;
	unset($vars['entity']);
}

$value = elgg_extract('value', $vars);
unset($vars['value']);
if (empty($vars['tags']) && (!empty($value) || $value === 0 || $value === '0')) {
	$vars['tags'] = $value;
}

if (empty($vars['tags']) && $value !== 0 && $value !== '0') {
	return;
}

$tags = $vars['tags'];
unset($vars['tags']);

if (!is_array($tags)) {
	$tags = array($tags);
}

$list_class = "elgg-tags";
if (isset($vars['list_class'])) {
	$list_class = "$list_class {$vars['list_class']}";
	unset($vars['list_class']);
}

$item_class = "elgg-tag";
if (isset($vars['item_class'])) {
	$item_class = "$item_class {$vars['item_class']}";
	unset($vars['item_class']);
}

$icon_class = elgg_extract('icon_class', $vars);
unset($vars['icon_class']);

$list_items = ''; 

$params = $vars;
foreach($tags as $tag) {
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
