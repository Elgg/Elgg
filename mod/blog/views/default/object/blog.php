<?php

/**
 * Blog view
 */
$full = elgg_extract('full_view', $vars);
$blog = elgg_extract('entity', $vars);

if (!$blog instanceof ElggEntity) {
	return;
}

$comments_link = '';
// The "on" status changes for comments, so best to check for !Off
if ($blog->comments_on != 'Off') {
	$comments_count = $blog->countComments();
	if ($comments_count) {
		$comments_link = elgg_view('output/url', array(
			'href' => $blog->getURL() . '#comments',
			'text' => elgg_echo('comments'),
			'icon' => 'comments',
			'badge' => $comments_count,
		));
	}
}

$status = null;
if ($blog->canEdit()) {
	$status = elgg_echo("status:{$blog->status}");
}

$view = $full ? 'object/elements/full' : 'object/elements/summary';
echo elgg_view($view, array(
	'entity' => $blog,
	'responses_link' => $comments_link,
	'status' => $status,
));
