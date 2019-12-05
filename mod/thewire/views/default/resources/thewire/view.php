<?php
/**
 * View individual wire post
 */

$guid = elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid, 'object', 'thewire');

/* @var $post ElggWire */
$post = get_entity($guid);

$owner = $post->getOwnerEntity();

$title = elgg_echo('thewire:by', [$owner->getDisplayName()]);

elgg_push_entity_breadcrumbs($post, false);

$content = elgg_view_entity($post);

$body = elgg_view_layout('default', [
	'title' => $title,
	'content' => $content,
	'entity' => $post,
]);

echo elgg_view_page($title, $body);
