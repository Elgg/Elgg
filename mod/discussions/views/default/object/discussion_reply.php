<?php
/**
 * Forum reply entity view
*/

$reply = elgg_extract('entity', $vars, FALSE);
/* @var ElggDiscussionReply $reply */

if (!$reply) {
	return true;
}

$poster = $reply->getOwnerEntity();

$poster_icon = elgg_view_entity_icon($poster, 'tiny');
$poster_link = elgg_view('output/url', array(
	'href' => $poster->getURL(),
	'text' => $poster->name,
	'is_trusted' => true,
));
$poster_text = elgg_echo('byline', array($poster->name));

$date = elgg_view_friendly_time($reply->time_created);

$metadata = '';
if (!elgg_in_context('widgets')) {
	// only show entity menu outside of widgets
	$metadata = elgg_view_menu('entity', array(
		'entity' => $vars['entity'],
		'handler' => 'discussion_reply',
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
	));
}

if (elgg_in_context('activity')) {
	$content = '<div class="elgg-output elgg-inner" data-role="comment-text">';
	$content .= elgg_view('output/text', array(
		'value' => elgg_get_excerpt($reply->description)
	));
	$content .= '</div>';

	$date_link = elgg_view('output/url', [
		'href' => $reply->getURL(),
		'text' => $date,
		'is_trusted' => true,
	]);
} else {
	$content = elgg_view('output/longtext', array(
		'value' => $reply->description,
		'class' => 'elgg-inner',
		'data-role' => 'discussion-reply-text',
	));

	$date_link = elgg_view('output/url', [
		'href' => $reply->getURL(),
		'text' => $date,
		'is_trusted' => true,
	]);
}

$subtitle = "$poster_text $date_link";

$params = array(
	'entity' => $reply,
	'metadata' => $metadata,
	'subtitle' => $subtitle,
	'content' => $content,
	'icon' => $poster_icon,
);
$params = $params + $vars;
echo elgg_view('object/elements/summary', $params);
