<?php

$guid = elgg_extract('owner_guid', $vars);
elgg_set_page_owner_guid($guid);

elgg_entity_gatekeeper($guid);
elgg_group_gatekeeper();

$container = get_entity($guid);
$type = $container->type;
$subtype = $container->getSubtype();
if (!discussion_is_context_allowed($container->type, $container->getSubtype())) {
	forward('', '404');
}

elgg_push_breadcrumb($container->getDisplayName(), $container->getURL());
elgg_push_breadcrumb(elgg_echo('item:object:discussion'));

if ($container->canWriteToContainer(0, 'object', 'discussion')) {
	elgg_register_title_button();
}

$title = elgg_echo('item:object:discussion');

$options = array(
	'type' => 'object',
	'subtype' => 'discussion',
	'limit' => max(20, elgg_get_config('default_limit')),
	'order_by' => 'e.last_action desc',
	'container_guid' => $guid,
	'full_view' => false,
	'no_results' => elgg_echo('discussion:none'),
	'preload_owners' => true,
);
$content = elgg_list_entities($options);

$params = array(
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('discussion/sidebar'),
	'filter' => '',
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);