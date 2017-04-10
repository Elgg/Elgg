<?php
/**
 * Forum topic entity view
 */

$full = elgg_extract('full_view', $vars, false);
$topic = elgg_extract('entity', $vars, false);

if (!$topic) {
	return;
}

$poster = $topic->getOwnerEntity();
if (!$poster) {
	elgg_log("User {$topic->owner_guid} could not be loaded, and is needed to display entity {$topic->guid}", 'WARNING');
	if ($full) {
		forward('', '404');
	}
	return;
}

$excerpt = elgg_get_excerpt($topic->description);

$poster_icon = elgg_view_entity_icon($poster, 'tiny');

$by_line = elgg_view('object/elements/imprint', $vars);

$replies_link = '';
$reply_text = '';

$num_replies = elgg_get_entities([
	'type' => 'object',
	'subtype' => 'discussion_reply',
	'container_guid' => $topic->getGUID(),
	'count' => true,
	'distinct' => false,
]);

if ($num_replies != 0) {
	$last_reply = elgg_get_entities([
		'type' => 'object',
		'subtype' => 'discussion_reply',
		'container_guid' => $topic->getGUID(),
		'limit' => 1,
		'distinct' => false,
	]);
	if (isset($last_reply[0])) {
		$last_reply = $last_reply[0];
	}
	/* @var ElggDiscussionReply $last_reply */

	$poster = $last_reply->getOwnerEntity();
	$reply_time = elgg_view_friendly_time($last_reply->time_created);

	$reply_text = elgg_view('output/url', [
		'text' => elgg_echo('discussion:updated', [$poster->name, $reply_time]),
		'href' => $last_reply->getURL(),
		'is_trusted' => true,
	]);

	$replies_link = elgg_view('output/url', [
		'href' => $topic->getURL() . '#group-replies',
		'text' => elgg_echo('discussion:replies') . " ($num_replies)",
		'is_trusted' => true,
	]);
}

// do not show the metadata and controls in widget view
$metadata = '';
if (!elgg_in_context('widgets')) {
	// only show entity menu outside of widgets
	$metadata = elgg_view_menu('entity', [
		'entity' => $vars['entity'],
		'handler' => 'discussion',
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
	]);
}

if ($full) {
	$subtitle = "$by_line $replies_link";

	$params = [
		'entity' => $topic,
		'title' => false,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
	];

	$params = $params + $vars;
	$summary = elgg_view('object/elements/summary', $params);

	$body = elgg_view('output/longtext', [
		'value' => $topic->description,
		'class' => 'clearfix',
	]);

	$responses = '';
	if (elgg_extract('show_responses', $vars)) {
		$params = [
			'topic' => $topic,
			'show_add_form' => $topic->canWriteToContainer(0, 'object', 'discussion_reply'),
		];
		$responses = elgg_view('discussion/replies', $params);
		if ($topic->status == 'closed') {
			$responses .= elgg_view('discussion/closed');
		}
	}

	echo elgg_view('object/elements/full', [
		'entity' => $topic,
		'icon' => $poster_icon,
		'summary' => $summary,
		'body' => $body,
		'responses' => $responses,
	]);
} else {
	// brief view
	$subtitle = "$by_line $replies_link <span class=\"float-alt\">$reply_text</span>";

	$params = [
		'entity' => $topic,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'content' => $excerpt,
		'icon' => $poster_icon,
	];
	$params = $params + $vars;
	echo elgg_view('object/elements/summary', $params);
}
