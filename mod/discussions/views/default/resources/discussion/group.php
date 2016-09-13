<?php
/**
 * Lists discussions created inside a specific group
 */

$guid = elgg_extract('guid', $vars);
elgg_set_page_owner_guid($guid);

elgg_group_gatekeeper();

$group = get_entity($guid);
if (!elgg_instanceof($group, 'group')) {
	forward('', '404');
}
elgg_push_breadcrumb($group->name, $group->getURL());
elgg_push_breadcrumb(elgg_echo('item:object:discussion'));

elgg_register_title_button('discussion', 'add', 'object', 'discussion');

$title = elgg_echo('item:object:discussion');

$content = elgg_view('discussion/listing/group', [
	'entity' => $group,
]);

$params = array(
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('discussion/sidebar'),
	'filter' => '',
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);