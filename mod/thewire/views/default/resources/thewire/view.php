<?php
/**
 * View individual wire post
 */
$guid = elgg_extract('guid', $vars);

elgg_entity_gatekeeper($guid, 'object', 'thewire');

$post = get_entity($guid);

$owner = $post->getOwnerEntity();
if (!$owner) {
	forward();
}

$title = elgg_echo('thewire:by', [$owner->name]);

elgg_push_breadcrumb(elgg_echo('thewire'), 'thewire/all');
elgg_push_breadcrumb($owner->name, 'thewire/owner/' . $owner->username);
elgg_push_breadcrumb($title);

$content = elgg_view_entity($post);

$body = elgg_view_layout('content', [
	'filter' => false,
	'content' => $content,
	'title' => $title,
]);

echo elgg_view_page($title, $body);
