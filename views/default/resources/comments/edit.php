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

elgg_push_breadcrumb($target->getDisplayName(), $target->getURL());

echo elgg_view_page(elgg_echo('generic_comments:edit'), [
	'content' => elgg_view_form('comment/save', [
		'prevent_double_submit' => true,
	], [
		'entity' => $target,
		'comment' => $comment,
	]),
]);
