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
	}

	$params = [
		'icon_entity' => $commenter,
		'time_href' => $comment->getURL(),
		'access' => false,
		'title' => false,
		'content' => $body,
	];
	$params = $params + $vars;
	echo elgg_view('object/elements/summary', $params);
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
