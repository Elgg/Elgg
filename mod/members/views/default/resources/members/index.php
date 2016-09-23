<?php
/**
 * Members index
 */

// The URL segment after members/
$page = elgg_extract('page', $vars);

$tabs = elgg_get_filter_tabs('members', $page);
if (!array_key_exists($page, $tabs)) {
	$page = array_values($tabs)[0]['name'];
}

$params = array(
	'options' => [
		'type' => 'user',
		'full_view' => false,
	],
);

// @todo: in 3.0, replace the hook with the listing view
$content = elgg_trigger_plugin_hook('members:list', $page, $params, null);
if ($content === null) {
	forward('', '404');
}

$title = elgg_echo("members:title:$page");

$body = elgg_view_layout('content', [
	'content' => $content,
	'sidebar' => elgg_view('members/sidebar'),
	'title' => $title,
	'filter' => $tabs,
]);

echo elgg_view_page($title, $body);
