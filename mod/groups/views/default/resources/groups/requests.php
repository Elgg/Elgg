<?php

elgg_gatekeeper();

$guid = elgg_extract('guid', $vars);
elgg_set_page_owner_guid($guid);

$group = get_entity($guid);
if (!elgg_instanceof($group, 'group') || !$group->canEdit()) {
	register_error(elgg_echo('groups:noaccess'));
	forward(REFERER);
}

$title = elgg_echo('groups:membershiprequests');

elgg_push_breadcrumb($group->name, $group->getURL());
elgg_push_breadcrumb($title);

$requests = elgg_get_entities_from_relationship(array(
	'type' => 'user',
	'relationship' => 'membership_request',
	'relationship_guid' => $guid,
	'inverse_relationship' => true,
	'limit' => 0,
));
$content = elgg_view('groups/membershiprequests', array(
	'requests' => $requests,
	'entity' => $group,
));

$params = array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
);
$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);