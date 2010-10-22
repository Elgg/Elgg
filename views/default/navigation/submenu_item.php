<?php
/**
 * Elgg submenu item.  Displays the <li> part of a submenu.
 *
 * @uses $vars['group']
 * @uses $vars['item']
 * @uses $vars['children_html']
 * @package Elgg
 * @subpackage Core
 */

$group = (isset($vars['group'])) ? $vars['group'] : 'default';
$item = (isset($vars['item'])) ? $vars['item'] : FALSE;
$children_html = (isset($vars['children_html'])) ? $vars['children_html'] : '';

if ($item) {
	$has_children = (isset($item->children) && $item->children) ? TRUE : FALSE;
	$selected = (isset($item->selected) && $item->selected == TRUE) ? 'class="selected"' : '';
	$js = (isset($vars['js'])) ? $vars['js'] : '';

	$child_indicator = '';
	if ($has_children) {
		if ($selected) {
			$child_indicator = '<span class="close_child">-</span>';
			$child_indicator .= '<span class="hidden open_child">+</span>';
		} else {
			$child_indicator = '<span class="hidden close_child">-</span>';
			$child_indicator .= '<span class="open_child">+</span>';
		}

		$child_indicator = "<span class=\"child_indicator\">$child_indicator </span>";
	}

	$url = htmlentities($item->href);
	$text = $child_indicator . htmlentities($item->text);

	$link_vars = array_merge($vars, array(
		'href' => $item->href,
		'text' => $text,
		'encode_text' => FALSE
	));

	$link = elgg_view('output/url', $link_vars);
}

echo "<li $selected>$link$children_html</li>";
