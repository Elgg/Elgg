<?php
/**
 * Elgg comment view
 *
 * @uses $vars['entity']    ElggComment
 * @uses $vars['full_view'] Display full view or brief view
 */

$full_view = elgg_extract('full_view', $vars, true);

$comment = elgg_extract('entity', $vars);
if (!$comment instanceof \ElggComment) {
	return;
}

$entity = $comment->getContainerEntity();
$commenter = $comment->getOwnerEntity();
if (!$entity instanceof \ElggEntity || !$commenter instanceof \ElggEntity) {
	return;
}

if ($comment->canEdit()) {
	elgg_import_esm('elgg/comments');
}

$params = [
	'icon_entity' => $commenter,
	'time_href' => $comment->getURL(),
	'access' => false,
	'title' => false,
	'tag_name' => 'article',
	'imprint' => elgg_extract('imprint', $vars, []),
	'class' => elgg_extract_class($vars),
];
$params = $params + $vars;

if ($comment->isCreatedByContentOwner()) {
	$params['class'][] = 'elgg-comment-by-owner';
	
	$params['imprint'][] = [
		'icon_name' => 'user-edit',
		'content' => elgg_echo('generic_comment:by_owner'),
	];
}

if ($full_view) {
	$params['show_summary'] = true;
	
	$body = elgg_view('output/longtext', [
		'value' => $comment->description,
	]);
	
	if (elgg_extract('show_add_form', $vars, true) && $comment->canComment()) {
		$body .= elgg_view('output/url', [
			'text' => elgg_echo('generic_comments:add'),
			'href' => "#elgg-form-comment-save-{$comment->guid}",
			'data-load-comment' => $comment->guid,
			'class' => ['elgg-subtext', 'elgg-toggle-comment'],
		]);
	}
	
	$params['content'] = $body;
	
	if (!empty(elgg()->thread_preloader->getChildren($comment->guid))) {
		$params['class'][] = 'with-children';
	}
	
	echo elgg_view('object/elements/full', $params);
} else {
	$params['content'] = elgg_get_excerpt((string) $comment->description);
	
	echo elgg_view('object/elements/summary', $params);
}
