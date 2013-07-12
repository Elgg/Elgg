<?php
/**
 * Upload and crop an avatar page
 */

// Only logged in users
elgg_gatekeeper();

elgg_push_context('settings');
elgg_push_context('profile_edit');

$title = elgg_echo('avatar:edit');

$entity = elgg_get_page_owner_entity();
if (!elgg_instanceof($entity, 'user') || !$entity->canEdit()) {
	register_error(elgg_echo('avatar:noaccess'));
	forward(REFERER);
}

$content = elgg_view('core/avatar/upload', array('entity' => $entity));

// only offer the crop view if an avatar has been uploaded
if (isset($entity->icontime)) {
	$content .= elgg_view('core/avatar/crop', array('entity' => $entity));
}

$params = array(
	'content' => $content,
	'title' => $title,
);
$body = elgg_view_layout('one_sidebar', $params);

echo elgg_view_page($title, $body);
