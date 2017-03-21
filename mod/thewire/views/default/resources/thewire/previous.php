<?php
/**
 * Serve up html for a post's parent
 */

$guid = (int) elgg_extract('guid', $vars);
$title = elgg_echo('previous');

$parent = thewire_get_parent($guid);
if ($parent) {
	$body = elgg_view_entity($parent);
}

$body = elgg_view_layout('content', [
	'filter' => false,
	'content' => $body,
	'title' => $title,
]);

echo elgg_view_page($title, $body);
