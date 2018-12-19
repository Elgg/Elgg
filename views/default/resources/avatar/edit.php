<?php
/**
 * Upload and crop an avatar page
 */

$username = elgg_extract('username', $vars);
$entity = get_user_by_username($username);

if (!$entity instanceof ElggUser || !$entity->canEdit()) {
	throw new \Elgg\EntityPermissionsException(elgg_echo('avatar:noaccess'));
}

elgg_push_context('settings');
elgg_push_context('profile_edit');

$title = elgg_echo('avatar:edit');

elgg_set_page_owner_guid($entity->guid);

$content = elgg_view('core/avatar/upload', ['entity' => $entity]);

// only offer the crop view if an avatar has been uploaded
if ($entity->hasIcon('master')) {
	$content .= elgg_view('core/avatar/crop', ['entity' => $entity]);
}

$body = elgg_view_layout('one_sidebar', [
	'title' => $title,
	'content' => $content,
	'show_owner_block_menu' => false,
]);

echo elgg_view_page($title, $body);
