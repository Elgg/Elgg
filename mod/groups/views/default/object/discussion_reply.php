<?php
/**
 * Forum reply entity view
 *
 * @package ElggGroups
*/

$reply = elgg_extract('entity', $vars, FALSE);

if (!$reply) {
	return true;
}

$poster = $reply->getOwnerEntity();
$topic = $reply->getContainerEntity();

$poster_icon = elgg_view_entity_icon($poster, 'tiny');
$poster_link = elgg_view('output/url', array(
	'href' => $poster->getURL(),
	'text' => $poster->name,
	'is_trusted' => true,
));
$poster_text = elgg_echo('byline', array($poster->name));

$date = elgg_view_friendly_time($reply->time_created);

// Do not show the metadata and controls in widget view
if (elgg_in_context('widgets')) {
	$metadata = '';
} else {
	$metadata = elgg_view_menu('entity', array(
		'entity' => $vars['entity'],
		'handler' => 'discussion_reply',
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
	));
}

$subtitle = "$poster_text $date";

$content = elgg_view('output/longtext', array(
	'value' => $reply->description,
	'class' => 'elgg-inner',
	'data-role' => 'discussion-reply-text',
));

$params = array(
	'entity' => $reply,
	'metadata' => $metadata,
	'subtitle' => $subtitle,
	'content' => $content,
);
$params = $params + $vars;
$list_body = elgg_view('object/elements/summary', $params);

echo elgg_view_image_block($poster_icon, $list_body);
