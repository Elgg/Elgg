<?php
/**
 * JSON river item view
 *
 * @uses $vars['item']
 */

$item = $vars['item'];
$object = $item->toObject();
if (elgg_view_exists($item->view, 'default')) {
	$object->description = elgg_view('river/elements/summary', ['item' => $item], false, false, 'default');
}

echo json_encode($object);
