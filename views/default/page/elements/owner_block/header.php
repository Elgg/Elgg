<?php

/**
 * Owner block header
 *
 * @uses $vars['owner_block_owner']  Override owner block owner
 * @uses $vars['owner_block_header'] Override owner block header
 */
$owner = elgg_extract('owner_block_owner', $vars);
if (!isset($owner)) {
	$owner = elgg_get_page_owner_entity();
}

if (!$owner instanceof ElggGroup && !$owner instanceof ElggUser) {
	return;
}

$header = elgg_extract('owner_block_header', $vars);
if (!isset($header)) {
	$icon = elgg_view_entity_icon($owner, 'medium', [
		'img_class' => 'rounded-circle',
		'use_hover' => false,
	]);

	$link = elgg_view('output/url', [
		'text' => $owner->getDisplayName(),
		'href' => $owner->getURL(),
		'class' => 'card-title',
	]);

	$header = elgg_format_element('div', [
		'class' => 'elgg-owner-block-header card-block',
			], $icon . $link);
}

if ($header) {
	echo $header;
}

