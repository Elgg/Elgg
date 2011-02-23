<?php
/**
 * View for page object
 *
 * @package ElggPages
 *
 * @uses $vars['entity']   The page object
 * @uses $vars['full']     Whether to display the full view
 * @uses $vars['revision'] This parameter not supported by elgg_view_entity()
 */


$full = elgg_extract('full', $vars, FALSE);
$page = elgg_extract('entity', $vars, FALSE);
$revision = elgg_extract('revision', $vars, FALSE);

if (!$page) {
	return TRUE;
}

if ($revision) {
	$annotation = $revision;
} else {
	$annotation = $page->getAnnotations('page', 1, 0, 'desc');
	if ($annotation) {
		$annotation = $annotation[0];
	}
}

$page_icon = elgg_view('pages/icon', array('annotation' => $annotation, 'size' => 'small'));

$editor = get_entity($annotation->owner_guid);
$editor_link = elgg_view('output/url', array(
	'href' => "pg/pages/owner/$editor->username",
	'text' => $editor->name,
));

$date = elgg_view_friendly_time($annotation->time_created);
$editor_text = elgg_echo('pages:strapline', array($date, $editor_link));
$tags = elgg_view('output/tags', array('tags' => $page->tags));

$comments_count = $page->countComments();
//only display if there are commments
if ($comments_count != 0 && !$revision) {
	$text = elgg_echo("comments") . " ($comments_count)";
	$comments_link = elgg_view('output/url', array(
		'href' => $page->getURL() . '#page-comments',
		'text' => $text,
	));
} else {
	$comments_link = '';
}

$history_link = elgg_view('output/url', array(
	'href' => "pg/pages/history/$page->guid",
	'text' => elgg_echo('pages:history'),
));

$metadata = elgg_view('navigation/menu/metadata', array(
	'entity' => $page,
	'handler' => 'pages',
	'links' => array($history_link),
));

$subtitle = "$editor_text $categories $comments_link";

// do not show the metadata and controls in widget view
if (elgg_in_context('widgets') || $revision) {
	$metadata = '';
}

if ($full) {
	$body = elgg_view('output/longtext', array('value' => $annotation->value));

	$params = array(
		'entity' => $page,
		'title' => false,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
	);
	$list_body = elgg_view('page/components/list/body', $params);

	$info = elgg_view_image_block($page_icon, $list_body);

	echo <<<HTML
$info
$body
HTML;

} else {
	// brief view

	$excerpt = elgg_get_excerpt($page->description);

	$params = array(
		'entity' => $page,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
		'content' => $excerpt,
	);
	$list_body = elgg_view('page/components/list/body', $params);

	echo elgg_view_image_block($page_icon, $list_body);
}
