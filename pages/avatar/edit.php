<?php
/**
 * Upload and crop an avatar page
 */

// Only logged in users
gatekeeper();

elgg_set_context('profile_edit');

$title = elgg_echo('avatar:edit');

$content = elgg_view('core/avatar/upload', array('entity' => elgg_get_page_owner_entity()));
$content .= elgg_view('core/avatar/crop', array('entity' => elgg_get_page_owner_entity()));

$params = array(
	'content' => $content,
	'title' => $title,
);
$body = elgg_view_layout('one_sidebar', $params);

echo elgg_view_page($title, $body);
