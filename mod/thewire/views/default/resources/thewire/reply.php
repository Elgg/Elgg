<?php
/**
 * Reply page
 */

$guid = elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid, 'object', 'thewire');

/* @var $post ElggWire */
$post = get_entity($guid);

elgg_push_entity_breadcrumbs($post, true);

$content = elgg_view('thewire/reply', ['post' => $post]);

$content .= elgg_view_form('thewire/add', [
	'class' => 'thewire-form',
	'prevent_double_submit' => true,
], [
	'post' => $post,
]);
$content .= elgg_view('input/urlshortener');

echo elgg_view_page(elgg_echo('reply'), [
	'content' => $content,
]);
