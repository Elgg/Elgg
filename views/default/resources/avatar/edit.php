<?php
/**
 * Upload and crop an avatar page
 */

use Elgg\Exceptions\Http\EntityPermissionsException;

$username = elgg_extract('username', $vars);
$entity = get_user_by_username($username);

if (!$entity instanceof ElggUser || !$entity->canEdit()) {
	throw new EntityPermissionsException(elgg_echo('avatar:noaccess'));
}

elgg_push_context('settings');
elgg_push_context('profile_edit');

elgg_set_page_owner_guid($entity->guid);

echo elgg_view_page(elgg_echo('avatar:edit'), [
	'content' => elgg_view('core/avatar/upload', ['entity' => $entity]),
	'show_owner_block_menu' => false,
	'filter_id' => 'avatar/edit',
]);
