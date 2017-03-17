<?php
/**
 * Wire posts tagged with <tag>
 */

$tag = elgg_extract('tag', $vars);
if (!$tag) {
	forward('thewire/all');
}

elgg_push_breadcrumb(elgg_echo('thewire'), 'thewire/all');
elgg_push_breadcrumb('#' . $tag);

// remove # from tag
$tag = trim($tag, '# ');

$title = elgg_echo('thewire:tags', [$tag]);


$content = elgg_list_entities_from_metadata([
	'metadata_name' => 'tags',
	'metadata_value' => $tag,
	'metadata_case_sensitive' => false,
	'type' => 'object',
	'subtype' => 'thewire',
	'limit' => 15,
]);

$body = elgg_view_layout('content', [
	'filter' => false,
	'content' => $content,
	'title' => $title,
]);

echo elgg_view_page($title, $body);
