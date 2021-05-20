<?php
/**
 * View individual wire post
 */

$guid = elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid, 'object', 'thewire');

/* @var $post ElggWire */
$post = get_entity($guid);

$title = elgg_echo('thewire:by', [$post->getOwnerEntity()->getDisplayName()]);

elgg_push_entity_breadcrumbs($post, false);

echo elgg_view_page($title, [
	'content' => elgg_view_entity($post),
	'entity' => $post,
	'filter_id' => 'thewire/view',
]);
