<?php
/**
 * List files by type
 *
 * @package ElggFile
 */

$page_owner_guid = get_input('page_owner', null);
if ($page_owner_guid) {
	elgg_set_page_owner_guid($page_owner_guid);
}
$owner = elgg_get_page_owner_entity();

group_gatekeeper();

// Get input
$md_type = 'simpletype';
$tag = get_input('tag');
$listtype = get_input('listtype');
$friends = get_input('friends', false);


// breadcrumbs
elgg_push_breadcrumb(elgg_echo('file'), "file/all");
if ($owner) {
	if (elgg_instanceof($owner, 'user')) {
		elgg_push_breadcrumb($owner->name, "file/owner/$owner->username");
	} else {
		elgg_push_breadcrumb($owner->name, "file/group/$owner->guid/all");
	}
}
if ($friends && $owner) {
	elgg_push_breadcrumb(elgg_echo('friends'), "file/friends/$owner->username");
}
if ($tag) {
	elgg_push_breadcrumb(elgg_echo("file:type:$tag"));
} else {
	elgg_push_breadcrumb(elgg_echo('all'));
}

// title
if (!$owner) {
	// world files
	$title = elgg_echo('all') . ' ' . elgg_echo("file:type:$tag");
} else {
	$friend_string = $friends ? elgg_echo('file:title:friends') : '';
	$type_string = elgg_echo("file:type:$tag");
	$title = elgg_echo('file:list:title', array($owner->name, $friend_string, $type_string));
}


$sidebar = file_get_type_cloud($page_owner_guid, $friends);

if ($friends) {
	// elgg_does not support getting objects that belong to an entity's friends
	$friend_entities = get_user_friends($page_owner_guid, "", 999999, 0);
	if ($friend_entities) {
		$friend_guids = array();
		foreach ($friend_entities as $friend) {
			$friend_guids[] = $friend->getGUID();
		}
	}
	$page_owner_guid = $friend_guids;
}

$limit = 10;
if ($listtype == "gallery") {
	$limit = 12;
}

$params = array(
	'types' => 'object',
	'subtypes' => 'file',
	'container_guid' => $page_owner_guid,
	'limit' => $limit,
	'full_view' => false,
);

if ($tag) {
	$params['metadata_name'] = $md_type;
	$params['metadata_value'] = $tag;
	$content = elgg_list_entities_from_metadata($params);
} else {
	$content = elgg_list_entities($params);
}

$body = elgg_view_layout('content', array(
	'filter' => '',
	'buttons' => '',
	'content' => $content,
	'title' => $title,
	'sidebar' => $sidebar,
));

echo elgg_view_page($title, $body);