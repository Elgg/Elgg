<?php

elgg_pop_breadcrumb();
elgg_push_breadcrumb(elgg_echo('discussion'));

$user = elgg_get_logged_in_user_entity();
if ($user && $user->canWriteToContainer(0, 'object', 'discussion')) {
	elgg_register_title_button();
}

$content = elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'discussion',
	'order_by' => 'e.last_action desc',
	'limit' => max(20, elgg_get_config('default_limit')),
	'full_view' => false,
	'no_results' => elgg_echo('discussion:none'),
	'preload_owners' => true,
	'preload_containers' => true,
));

$title = elgg_echo('discussion:latest');

$params = array(
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('discussion/sidebar'),
	'filter' => '',
);
$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);