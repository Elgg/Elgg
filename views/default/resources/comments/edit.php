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

echo elgg_view_page($title, [
	'content' => elgg_view_form('comment/save', [], [
		'entity' => $target,
		'comment' => $comment,
	]),
]);
