<?php
/**
 * Wire posts tagged with <tag>
 */

use Elgg\Exceptions\Http\BadRequestException;

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

echo elgg_view_page($title, [
	'content' => $content,
	'filter_value' => 'tag',
]);
