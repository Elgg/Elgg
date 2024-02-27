<?php
/**
 * Show a listing of Wire posts with a given tag
 *
 * @uses $vars['options'] Additional listing options
 * @uses $vars['tag'] the tag to show
 */

$options = (array) elgg_extract('options', $vars);
$tag = elgg_extract('tag', $vars);
if (elgg_is_empty($tag)) {
	return;
}

$tag_options = [
	'metadata_name_value_pairs' => [
		'name' => 'tags',
		'value' => $tag,
		'case_sensitive' => false,
	],
];

$vars['options'] = array_merge($options, $tag_options);

echo elgg_view('thewire/listing/all', $vars);
