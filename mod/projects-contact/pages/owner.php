<?php

$project_guid = elgg_get_page_owner_guid();

$title = elgg_echo('projects_contact:inbox');

elgg_push_breadcrumb($title);

$list = elgg_list_entities_from_metadata(array(
	'type' => 'object',
	'subtype' => 'projects-contact',
	'metadata_name' => 'toGuid',
	'metadata_value' => $project_guid,
	'full_view' => false,
));

$content = elgg_view_form('projects-contact/process', array(), array('list' => $list));

$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
));

echo elgg_view_page($title, $body);


