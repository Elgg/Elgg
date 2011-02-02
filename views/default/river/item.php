<?php
$item = $vars['item'];

$vars = array(
	'image' => elgg_view('core/river/image', array('item' => $item)),
	'body' => elgg_view('core/river/body', array('item' => $item)),
	'image_alt' => elgg_view('core/river/controls', array('item' => $item)),
	'class' => 'elgg-river-item',
);

echo elgg_view('layout/objects/image_block', $vars);