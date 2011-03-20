<?php
/**
 * RSS list view
 *
 * @uses $vars['items']
 */

$items = $vars['items'];

if (is_array($items) && sizeof($items) > 0) {
	foreach ($items as $item) {
		echo elgg_view_list_item($item, $vars);
	}
}