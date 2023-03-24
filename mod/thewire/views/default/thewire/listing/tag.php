<?php
/**
 * Show a listing of Wire posts with a given tag
 *
 * @uses $vars['tag'] the tag to show
 */

$tag = elgg_extract('tag', $vars);
if (elgg_is_empty($tag)) {
	return;
}

$vars['options'] = [
	'metadata_name_value_pairs' => [
		'name' => 'tags',
		'value' => $tag,
		'case_sensitive' => false,
	],
];

echo elgg_view('thewire/listing/all', $vars);
