<?php
/**
 * Reply page
 * 
 */

gatekeeper();

$post = get_entity(get_input('guid'));

$title = elgg_echo('thewire:reply');

elgg_push_breadcrumb(elgg_echo('thewire'), 'thewire/all');
elgg_push_breadcrumb($title);

$content = elgg_view('thewire/reply', array('post' => $post));
$content .= elgg_view_form('thewire/add', array(), array('post' => $post));
$content .= elgg_view('input/urlshortener');


$body = elgg_view_layout('content', array(
	'filter' => false,
	'content' => $content,
	'title' => $title,
	'buttons' => false,
));

echo elgg_view_page($title, $body);
