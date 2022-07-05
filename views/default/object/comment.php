<?php
/**
 * Elgg comment view
 *
 * @uses $vars['entity']       ElggComment
 * @uses $vars['full_view']    Display full view or brief view
 * @uses $vars['show_excerpt'] Should the body text be an excerpt
 */

$full_view = elgg_extract('full_view', $vars, true);

$comment = elgg_extract('entity', $vars);
if (!$comment instanceof \ElggComment) {
	return;
}

$entity = get_entity($comment->container_guid);
$commenter = get_user($comment->owner_guid);
if (!$entity || !$commenter) {
	return;
}

if ($comment->canEdit()) {
	elgg_require_js('elgg/comments');
}

if ($full_view) {
	if (elgg_extract('show_excerpt', $vars)) {
		$body = elgg_view('output/longtext', [
			'value' => elgg_get_excerpt($comment->description),
		]);
	} else {
		$body = elgg_view('output/longtext', [
			'value' => $comment->description,
		]);
	
		if (elgg_extract('show_add_form', $vars, true) && $comment->canComment()) {
			$body .= elgg_view('output/url', [
				'text' => elgg_echo('generic_comments:add'),
				'href' => "#elgg-form-comment-save-{$comment->guid}",
				'class' => ['elgg-toggle', 'elgg-subtext'],
			]);
		}
	}

	$params = [
		'icon_entity' => $commenter,
		'time_href' => $comment->getURL(),
		'access' => false,
		'title' => false,
		'show_summary' => true,
		'content' => $body,
		'imprint' => elgg_extract('imprint', $vars, []),
		'class' => elgg_extract_class($vars),
	];
	$params = $params + $vars;
	
	if (!empty(elgg()->thread_preloader->getChildren($comment->guid))) {
		$params['class'][] = 'with-children';
	}
	
	if ($comment->isCreatedByContentOwner()) {
		$params['class'][] = 'elgg-comment-by-owner';
		
		$params['imprint'][] = [
			'icon_name' => 'user-edit',
			'content' => elgg_echo('generic_comment:by_owner'),
		];
	}
	
	echo elgg_view('object/elements/full', $params);
} else {
	// brief view
	$commenter_icon = elgg_view_entity_icon($commenter, 'small');

	$friendlytime = elgg_view_friendly_time($comment->time_created);

	$commenter_link = elgg_view_entity_url($commenter);

	$entity_link = elgg_view('output/url', [
		'href' => $entity->getURL(),
		'text' => $entity->getDisplayName() ?: elgg_echo('untitled'),
	]);

	$excerpt = elgg_get_excerpt($comment->description, 80);
	$posted = elgg_echo('generic_comment:on', [$commenter_link, $entity_link]);

	$body = elgg_format_element('span', [
		'class' => 'elgg-subtext',
	], "$posted ($friendlytime): $excerpt");

	echo elgg_view_image_block($commenter_icon, $body);
}
