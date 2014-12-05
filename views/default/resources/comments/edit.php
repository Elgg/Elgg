<?php
$page = $vars['segments'];

elgg_gatekeeper();

if (empty($page[1])) {
	register_error(elgg_echo('generic_comment:notfound'));
	forward(REFERER);
}
$comment = get_entity($page[1]);
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

$params = array(
	'entity' => $target,
	'comment' => $comment,
	'is_edit_page' => true,
);
$content = elgg_view_form('comment/save', null, $params);

$params = array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
);
$body = elgg_view_layout('content', $params);
echo elgg_view_page($title, $body);