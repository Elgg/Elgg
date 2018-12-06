<?php
/**
 * Wire posts tagged with <tag>
 */

use Elgg\BadRequestException;

$tag = elgg_extract('tag', $vars);
if (!$tag) {
	throw new BadRequestException();
}

elgg_push_collection_breadcrumbs('object', 'thewire');

// remove # from tag
$tag = trim($tag, '# ');

$title = elgg_echo('thewire:tags', [$tag]);

$content = elgg_list_entities([
	'type' => 'object',
	'subtype' => 'thewire',
	'limit' => 15,
	'metadata_name_value_pairs' => [
		'name' => 'tags',
		'value' => $tag,
		'case_sensitive' => false,
	],
]);

$body = elgg_view_layout('content', [
	'filter' => false,
	'content' => $content,
	'title' => $title,
]);

echo elgg_view_page($title, $body);
