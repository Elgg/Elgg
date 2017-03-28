<?php

$entity = elgg_extract('entity', $vars);

if (!$entity instanceof ElggUser) {
	return;
}

$meta_block = elgg_view('page/elements/meta_block', $vars);
if ($meta_block) {
	$sidebar .= elgg_format_element('div', [
		'class' => 'card',
	], $meta_block);
}

$owner_block = elgg_view('page/elements/owner_block', [
	'owner_block_owner' => $entity,
	'owner_block_header' => '',
		]);
if ($owner_block) {
	$sidebar .= elgg_format_element('div', [
		'class' => 'card',
	], $owner_block);
}

$page_menu = elgg_view('page/elements/menu', $vars);
if ($page_menu) {
	$sidebar .= elgg_format_element('div', [
		'class' => 'card',
	], $page_menu);
}

echo $sidebar;