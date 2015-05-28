<?php

elgg_push_breadcrumb(elgg_echo('search'));

$tag = get_input("tag");
$display_query = _elgg_get_display_query($tag);
$title = elgg_echo('groups:search:title', array($display_query));

// groups plugin saves tags as "interests" - see groups_fields_setup() in start.php
$params = array(
	'metadata_name' => 'interests',
	'metadata_value' => $tag,
	'type' => 'group',
	'full_view' => false,
	'no_results' => elgg_echo('groups:search:none'),
);
$content = elgg_list_entities_from_metadata($params);

$sidebar = elgg_view('groups/sidebar/find');
$sidebar .= elgg_view('groups/sidebar/featured');

$params = array(
	'content' => $content,
	'sidebar' => $sidebar,
	'filter' => false,
	'title' => $title,
);
$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);