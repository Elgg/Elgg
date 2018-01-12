<?php
/**
 * JSON river item view
 *
 * @uses $vars['item']
 */

$item = elgg_extract('item', $vars);
$object = $item->toObject();
if (elgg_view_exists($item->view, 'default')) {
	$object->description = elgg_view('river/elements/summary', ['item' => $item], 'default');
}

echo json_encode($object);
