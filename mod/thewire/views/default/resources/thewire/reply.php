<?php
/**
 * Reply page
 *
 */

elgg_gatekeeper();

$post = get_entity(elgg_extract('guid', $vars));

$title = elgg_echo('reply');

elgg_push_breadcrumb(elgg_echo('thewire'), 'thewire/all');
elgg_push_breadcrumb($title);

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
