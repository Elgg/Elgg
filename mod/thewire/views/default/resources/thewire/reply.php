<?php
/**
 * Reply page
 */

$guid = elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid, 'object', 'thewire');

/* @var $post ElggWire */
$post = get_entity($guid);

$title = elgg_echo('reply');

elgg_push_entity_breadcrumbs($post, true);

$content = elgg_view('thewire/reply', ['post' => $post]);
$form_vars = ['class' => 'thewire-form'];
$content .= elgg_view_form('thewire/add', $form_vars, ['post' => $post]);
$content .= elgg_view('input/urlshortener');


$body = elgg_view_layout('content', [
	'filter' => false,
	'content' => $content,
	'title' => $title,
]);

echo elgg_view_page($title, $body);
