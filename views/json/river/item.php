<?php
/**
 * JSON river item view
 *
 * @uses $vars['item']
 */

$item = $vars['item'];
if (elgg_view_exists($item->view, 'default')) {
	$item->string = elgg_view('river/elements/summary', array('item' => $item), FALSE, FALSE, 'default');
}

echo json_encode($item);
