<?php

$entity = $vars['entity'];
/* @var ElggObject $entity */

$model = $vars['model'];
/* @var \Elgg\DeferredViews\RiverComments $model */

// This first call will batch fetch all comment counts for the whole river view.
$comment_count = $model->numComments($entity->guid);
if (!$comment_count) {
	return;
}

$comments = $model->latestComments($entity->guid);
if (!$comments) {
	return;
}

// why is this reversing it? because we're asking for the 3 latest
// comments by sorting desc and limiting by 3, but we want to display
// these comments with the latest at the bottom.
$comments = array_reverse($comments);

echo elgg_view_entity_list($comments, array('list_class' => 'elgg-river-comments'));

if ($comment_count > count($comments)) {
	$num_more_comments = $comment_count - count($comments);
	$url = $entity->getURL();
	$params = array(
		'href' => $url,
		'text' => elgg_echo('river:comments:more', array($num_more_comments)),
		'is_trusted' => true,
	);
	$link = elgg_view('output/url', $params);
	echo "<div class=\"elgg-river-more\">$link</div>";
}
