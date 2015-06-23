<?php
/**
 * Upload and crop an image
 */

// Only logged in users
elgg_gatekeeper();

$title = elgg_echo('image:edit');

$entity = get_entity(get_input('guid'));

if (!$entity instanceof ElggEntity) {
	register_error(elgg_echo('image:edit:not_found'));
	forward(REFERER);
}

if (!$entity->canEdit()) {
	register_error(elgg_echo('image:edit:noaccess'));
	forward(REFERER);
}

$content = elgg_view('core/image/upload', array('entity' => $entity));

// Offer the crop view only if an image has been uploaded
if (isset($entity->icontime)) {
	$content .= elgg_view('core/image/crop', array('entity' => $entity));
}

$params = array(
	'content' => $content,
	'title' => $title,
);
$body = elgg_view_layout('one_sidebar', $params);

echo elgg_view_page($title, $body);
