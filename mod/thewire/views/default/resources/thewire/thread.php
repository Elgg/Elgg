<?php
/**
 * View conversation thread
 */

$thread_id = elgg_extract('thread_id', $vars);

$title = elgg_echo('thewire:thread');

elgg_push_breadcrumb(elgg_echo('thewire'), 'thewire/all');
elgg_push_breadcrumb($title);

$content = elgg_list_entities([
	"metadata_name" => "wire_thread",
	"metadata_value" => $thread_id,
	"type" => "object",
	"subtype" => "thewire",
	"limit" => max(20, elgg_get_config('default_limit')),
	'preload_owners' => true,
]);

$body = elgg_view_layout('content', [
	'filter' => false,
	'content' => $content,
	'title' => $title,
]);

echo elgg_view_page($title, $body);
