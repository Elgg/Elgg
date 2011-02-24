<?php
$item = $vars['item'];

$vars = array(
	'image' => elgg_view('river/item/image', array('item' => $item)),
	'body' => elgg_view('river/item/body', array('item' => $item)),
	'image_alt' => elgg_view('river/item/controls', array('item' => $item)),
	'class' => 'elgg-river-item',
);

echo elgg_view('page/components/image_block', $vars);