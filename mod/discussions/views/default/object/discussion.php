<?php
/**
 * Forum topic entity view
 */

$full = elgg_extract('full_view', $vars, false);

$topic = elgg_extract('entity', $vars, false);
if (!$topic instanceof ElggDiscussion) {
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

$poster_icon = elgg_view_entity_icon($poster, 'small');

$comment_text = '';
$num_comments = $topic->countComments();
if ($num_comments != 0) {
	$comments = elgg_get_entities([
		'type' => 'object',
		'subtype' => 'comment',
		'container_guid' => $topic->getGUID(),
		'limit' => 1,
		'distinct' => false,
	]);
	
	/* @var ElggComment $last_comment */
	$last_comment = $comments[0];
	
	$poster = $last_comment->getOwnerEntity();
	$comment_time = elgg_view_friendly_time($last_comment->time_created);

	$comment_text = elgg_view('output/url', [
		'text' => elgg_echo('discussion:updated', [$poster->getDisplayName(), $comment_time]),
		'href' => $last_comment->getURL(),
		'is_trusted' => true,
	]);
}

if ($full) {
	$params = [
		'entity' => $topic,
		'title' => false,
		'handler' => 'discussion',
	];

	$params = $params + $vars;
	$summary = elgg_view('object/elements/summary', $params);

	$body = elgg_view('output/longtext', [
		'value' => $topic->description,
		'class' => 'clearfix',
	]);

	$responses = null;
	if ($topic->status == 'closed') {
		$body .= elgg_view('discussion/closed');
		
		// need to provide the comments as we can't disable the add form
		$responses = elgg_view_comments($topic, false);
	}

	echo elgg_view('object/elements/full', [
		'entity' => $topic,
		'icon' => $poster_icon,
		'summary' => $summary,
		'body' => $body,
		'responses' => $responses,
		'show_navigation' => true,
	]);
} else {
	// brief view
	$by_line = elgg_view('object/elements/imprint', $vars);
	
	$subtitle = "$by_line <span class=\"float-alt\">$comment_text</span>";

	$params = [
		'entity' => $topic,
		'handler' => 'discussion',
		'subtitle' => $subtitle,
		'content' => elgg_get_excerpt($topic->description),
		'icon' => $poster_icon,
	];
	$params = $params + $vars;
	echo elgg_view('object/elements/summary', $params);
}
