<?php
/**
 * View individual wire post
 */

$post = get_entity(get_input('guid'));
if (!$post) {
	register_error(elgg_echo('noaccess'));
	$_SESSION['last_forward_from'] = current_page_url();
	forward('');
}
$owner = $post->getOwnerEntity();
if (!$owner) {
	forward();
}

$title = elgg_echo('thewire:by', array($owner->name));

elgg_push_breadcrumb(elgg_echo('thewire'), 'thewire/all');
elgg_push_breadcrumb($owner->name, 'thewire/owner/' . $owner->username);
elgg_push_breadcrumb($title);

$content = elgg_view_entity($post);

$body = elgg_view_layout('content', array(
	'filter' => false,
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);
