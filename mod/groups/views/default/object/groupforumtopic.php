<?php
/**
 * Forum topic entity view
 *
 * @package ElggGroups
*/

//$full = elgg_get_array_value('full', $vars, FALSE);
$topic = elgg_get_array_value('entity', $vars, FALSE);

if (!$topic) {
	return true;
}

$poster = $topic->getOwnerEntity();
$group = $topic->getContainerEntity();
$excerpt = elgg_get_excerpt($topic->description);

$poster_icon = elgg_view('profile/icon', array('entity' => $poster, 'size' => 'tiny'));
$poster_link = elgg_view('output/url', array(
	'href' => $poster->getURL(),
	'text' => $poster->name,
));
$poster_text = elgg_echo('groups:started', array($poster->name));

$tags = elgg_view('output/tags', array('tags' => $topic->tags));
$date = elgg_view_friendly_time($topic->time_created);

$comments_link = '';
$comments_text = '';
$num_comments = elgg_count_comments($topic);
if ($num_comments != 0) {
	$last_comment = $topic->getAnnotations("generic_comment", 1, 0, "desc");
	$commenter = $last_comment[0]->getOwnerEntity();
	$comment_time = elgg_view_friendly_time($last_comment[0]->time_created);
	$comments_text = elgg_echo('groups:updated', array($commenter->name, $comment_time));
	
	$comments_link = elgg_view('output/url', array(
		'href' => $topic->getURL() . '#topic-comments',
		'text' => elgg_echo("comments") . " ($num_comments)",
	));
}

$metadata = elgg_view('layout/objects/list/metadata', array(
	'entity' => $topic,
	'handler' => 'discussion',
));

$subtitle = "$poster_text $date $comments_link <span class=\"groups-latest-comment\">$comments_text</span>";

// do not show the metadata and controls in widget view
if (elgg_in_context('widgets')) {
	$metadata = '';
}

if ($full) {

} else {
	// brief view

	$params = array(
		'entity' => $topic,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
		'content' => $excerpt,
	);
	$list_body = elgg_view('layout/objects/list/body', $params);

	echo elgg_view_image_block($poster_icon, $list_body);
}
