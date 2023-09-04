<?php
/**
 * View individual wire post
 */

$guid = (int) elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid, 'object', 'thewire');

/* @var $post \ElggWire */
$post = get_entity($guid);

elgg_push_entity_breadcrumbs($post);

echo elgg_view_page(elgg_echo('thewire:by', [$post->getOwnerEntity()->getDisplayName()]), [
	'content' => elgg_view_entity($post),
	'entity' => $post,
	'filter_id' => 'thewire/view',
]);
