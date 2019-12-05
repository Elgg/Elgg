<?php
/**
 * Serve up html for a post's parent
 */

elgg_deprecated_notice('The resource "thewire/previous" has been deprecated', '3.1');

$guid = (int) elgg_extract('guid', $vars);
$title = elgg_echo('previous');

$parent = thewire_get_parent($guid);
if ($parent) {
	$body = elgg_view_entity($parent);
}

$body = elgg_view_layout('default', [
	'content' => $body,
	'title' => $title,
]);

echo elgg_view_page($title, $body);
