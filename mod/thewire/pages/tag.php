<?php
/**
 * Wire posts tagged with <tag>
 */

$tag = get_input('tag');
if (!$tag) {
	forward('thewire/all');
}

elgg_push_breadcrumb(elgg_echo('thewire'), 'thewire/all');
elgg_push_breadcrumb('#' . $tag);

// remove # from tag
$tag = trim($tag, '# ');

$title = elgg_echo('thewire:tags', array($tag));


$content = elgg_list_entities_from_metadata(array(
	'metadata_name' => 'tags',
	'metadata_value' => $tag,
	'metadata_case_sensitive' => false,
	'type' => 'object',
	'subtype' => 'thewire',
	'limit' => 15,
));

$body = elgg_view_layout('content', array(
	'filter' => false,
	'content' => $content,
	'title' => $title,
	'buttons' => false,
));

echo elgg_view_page($title, $body);
