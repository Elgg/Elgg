<?php

use Elgg\Exceptions\Http\EntityNotFoundException;
use Elgg\Exceptions\Http\EntityPermissionsException;

elgg_deprecated_notice('The resource "comments/edit" is deprecated. Do not use this view.', '5.1');

$guid = (int) elgg_extract('guid', $vars);

$comment = get_entity($guid);
if (!$comment instanceof \ElggComment || !$comment->canEdit()) {
	throw new EntityPermissionsException(elgg_echo('generic_comment:notfound'));
}

$target = $comment->getContainerEntity();
if (!$target instanceof \ElggEntity) {
	throw new EntityNotFoundException(elgg_echo('generic_comment:notfound'));
}

elgg_push_entity_breadcrumbs($target);

echo elgg_view_page(elgg_echo('generic_comments:edit'), [
	'content' => elgg_view_form('comment/save', [], [
		'entity' => $target,
		'comment' => $comment,
	]),
	'filter_id' => 'comment/edit',
]);
