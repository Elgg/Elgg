<?php
/**
 * Wire posts tagged with <tag>
 */

use Elgg\Exceptions\Http\BadRequestException;

$tag = (string) elgg_extract('tag', $vars);
if (elgg_is_empty($tag)) {
	throw new BadRequestException();
}

elgg_push_collection_breadcrumbs('object', 'thewire');

// remove # from tag
$tag = trim($tag, '# ');

echo elgg_view_page(elgg_echo('thewire:tags', [$tag]), [
	'content' => elgg_view('thewire/listing/tag', [
		'tag' => $tag,
	]),
	'filter_value' => 'tag',
]);
