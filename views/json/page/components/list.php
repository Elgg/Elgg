<?php
/**
 * JSON list view
 *
 * @uses $vars['items']
 */

$items = $vars['items'];

$list = '[';
if (is_array($items) && sizeof($items) > 0) {
	foreach ($items as $item) {
		$list .= elgg_view_list_item($item, $vars);
		$list .= ',';
	}
	$list = rtrim($list, ',');
}

$list .= ']';

echo $list;
