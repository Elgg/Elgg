<?php
/**
 * Forum topic entity view
 *
 * @package ElggGroups
*/

$full = elgg_extract('full', $vars, FALSE);
$topic = elgg_extract('entity', $vars, FALSE);

if (!$topic) {
	return true;
}

$poster = $topic->getOwnerEntity();
$group = $topic->getContainerEntity();
$excerpt = elgg_get_excerpt($topic->description);

$poster_icon = elgg_view_entity_icon($poster, 'tiny');
$poster_link = elgg_view('output/url', array(
	'href' => $poster->getURL(),
	'text' => $poster->name,
));
$poster_text = elgg_echo('groups:started', array($poster->name));

$tags = elgg_view('output/tags', array('tags' => $topic->tags));
$date = elgg_view_friendly_time($topic->time_created);

$comments_link = '';
$comments_text = '';
$num_comments = $topic->countComments();
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

$metadata = elgg_view('navigation/menu/metadata', array(
	'entity' => $topic,
	'handler' => 'discussion',
));

// do not show the metadata and controls in widget view
if (elgg_in_context('widgets')) {
	$metadata = '';
}

if ($full) {
	$subtitle = "$poster_text $date $comments_link";

	$params = array(
		'entity' => $topic,
		'title' => false,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
	);
	$list_body = elgg_view('page/components/list/body', $params);

	$info = elgg_view_image_block($poster_icon, $list_body);

	$body = elgg_view('output/longtext', array('value' => $topic->description));

	echo <<<HTML
$header
$info
$body
HTML;

} else {
	// brief view
	$subtitle = "$poster_text $date $comments_link <span class=\"groups-latest-comment\">$comments_text</span>";

	$params = array(
		'entity' => $topic,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
		'content' => $excerpt,
	);
	$list_body = elgg_view('page/components/list/body', $params);

	echo elgg_view_image_block($poster_icon, $list_body);
}
