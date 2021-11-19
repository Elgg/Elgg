<?php
/**
 * Upload and crop an avatar page
 */

elgg_push_context('settings');
elgg_push_context('profile_edit');

echo elgg_view_page(elgg_echo('avatar:edit'), [
	'content' => elgg_view('core/avatar/upload', ['entity' => elgg_get_page_owner_entity()]),
	'show_owner_block_menu' => false,
	'filter_id' => 'avatar/edit',
]);
