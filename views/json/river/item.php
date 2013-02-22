<?php
/**
 * JSON river item view
 *
 * @uses $vars['item']
 */

$item = $vars['item'];
$object = $item->toObject();
if (elgg_view_exists($item->view, 'default')) {
	$object->description = elgg_view('river/elements/summary', array('item' => $item), FALSE, FALSE, 'default');
}

echo json_encode($object);
