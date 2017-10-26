<?php
/**
 * Elgg comment view
 *
 * @uses $vars['entity']    ElggComment
 * @uses $vars['full_view'] Display full view or brief view
 */

$full_view = elgg_extract('full_view', $vars, true);

$comment = elgg_extract('entity', $vars);
if (!($comment instanceof \ElggComment)) {
	return;
}

$entity = get_entity($comment->container_guid);
$commenter = get_user($comment->owner_guid);
if (!$entity || !$commenter) {
	return true;
}

$friendlytime = elgg_view_friendly_time($comment->time_created);

$commenter_icon = elgg_view_entity_icon($commenter, 'tiny');
$commenter_link = elgg_view('output/url', [
	'href' => $commenter->getURL(),
	'text' => $commenter->getDisplayName(),
]);

if ($full_view) {
	
	$imprint = elgg_view('object/elements/imprint/byline', $vars);
	
	$imprint .= elgg_view('object/elements/imprint/element', [
		'icon_name' => 'history',
		'content' => elgg_view('output/url', [
			'href' => $comment->getURL(),
			'text' => $friendlytime,
			'is_trusted' => true,
		]),
		'class' => 'elgg-listing-time',
	]);
	
	$subtitle = elgg_format_element('div', [
		'class' => 'elgg-listing-imprint',
	], $imprint);
	
	if (elgg_in_context('activity')) {
		$body = elgg_view('output/longtext', [
			'value' => elgg_get_excerpt($comment->description),
		]);
	} else {
		$body = elgg_view('output/longtext', [
			'value' => $comment->description,
		]);
	}

	$params = [
		'subtitle' => $subtitle,
		'icon' => $commenter_icon,
		'title' => false,
		'handler' => 'comment',
		'content' => $body,
	];
	$params = $params + $vars;
	echo elgg_view('object/elements/summary', $params);
} else {
	// brief view
	
	$entity_link = elgg_view('output/url', [
		'href' => $entity->getURL(),
		'text' => $entity->getDisplayName() ?: elgg_echo('untitled'),
	]);

	$excerpt = elgg_get_excerpt($comment->description, 80);
	$posted = elgg_echo('generic_comment:on', [$commenter_link, $entity_link]);

	$body = <<<HTML
<span class="elgg-subtext">
	$posted ($friendlytime): $excerpt
</span>
HTML;

	echo elgg_view_image_block($commenter_icon, $body);
}
