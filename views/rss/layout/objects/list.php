<?php
/**
 * RSS list view
 *
 * @uses $vars['items']
 */

$items = $vars['items'];
$full_view = elgg_get_array_value('full_view', $vars, false);

if (is_array($items) && sizeof($items) > 0) {
	foreach ($items as $item) {
		echo elgg_view_list_item($item, $full_view, $vars);
	}
}