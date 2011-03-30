<?php
/**
 * Serve up html for a post's parent
 */

$guid = (int) get_input('guid');
$title = elgg_echo('thewire:previous');

$parent = thewire_get_parent($guid);
if ($parent) {
	$body = elgg_view_entity($parent);
}

$body = elgg_view_layout('content', array(
	'filter' => false,
	'content' => $body,
	'title' => $title,
	'buttons' => false,
));

echo elgg_view_page($title, $body);