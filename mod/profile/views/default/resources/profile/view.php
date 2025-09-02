<?php

/* @var $user \ElggUser */
$user = elgg_get_page_owner_entity();

$content = elgg_view('profile/wrapper', [
	'entity' => $user,
]);

$content .= elgg_view_layout('widgets', [
	'num_columns' => 2,
	'owner_guid' => $user->guid,
]);

echo elgg_view_page($user->getDisplayName(), [
	'content' => $content,
	'entity' => $user,
	'sidebar_alt' => elgg_view('profile/owner_block', [
		'entity' => $user,
	]),
	'sidebar' => false,
	'filter' => false,
]);
