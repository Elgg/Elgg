<?php
/**
 * Upload and crop an avatar page
 */

$user = elgg_get_page_owner_entity();

elgg_push_entity_breadcrumbs($user);

echo elgg_view_page(elgg_echo('avatar:edit'), [
	'content' => elgg_view('core/avatar/upload', ['entity' => $user]),
	'show_owner_block_menu' => false,
	'filter_id' => 'profile/edit',
]);
