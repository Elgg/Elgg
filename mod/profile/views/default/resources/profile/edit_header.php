<?php
/**
 * Edit profile page header
 */

$user = elgg_get_page_owner_entity();

elgg_push_breadcrumb($user->getDisplayName(), $user->getURL());

echo elgg_view_page(elgg_echo('profile:edit'), [
	'content' => elgg_view_form('profile/edit/header', [], ['entity' => $user]),
	'show_owner_block_menu' => false,
	'filter_id' => 'profile/edit',
]);
