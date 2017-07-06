<?php

$guid = elgg_extract('guid', $vars);

elgg_gatekeeper();

if (!$guid) {
	register_error(elgg_echo('generic_comment:notfound'));
	forward(REFERER);
}
$comment = get_entity($guid);
if (!($comment instanceof \ElggComment) || !$comment->canEdit()) {
	register_error(elgg_echo('generic_comment:notfound'));
	forward(REFERER);
}

$target = $comment->getContainerEntity();
if (!($target instanceof \ElggEntity)) {
	register_error(elgg_echo('generic_comment:notfound'));
	forward(REFERER);
}

$title = elgg_echo('generic_comments:edit');
elgg_push_breadcrumb($target->getDisplayName(), $target->getURL());
elgg_push_breadcrumb($title);

$params = [
	'entity' => $target,
	'comment' => $comment,
];
$content = elgg_view_form('comment/save', null, $params);

$params = [
	'content' => $content,
	'title' => $title,
	'filter' => '',
];
$body = elgg_view_layout('content', $params);
echo elgg_view_page($title, $body);
