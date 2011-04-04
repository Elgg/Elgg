<?php
/**
 * Layout of a river item
 *
 * @uses $vars['item'] ElggRiverItem
 */

$item = $vars['item'];

$vars = array(
	'image' => elgg_view('river/elements/image', array('item' => $item)),
	'body' => elgg_view('river/elements/body', array('item' => $item)),
	'class' => 'elgg-river-item',
);

echo elgg_view('page/components/image_block', $vars);
