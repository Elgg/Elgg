<?php

$project_guid = (int)get_input('project_guid');

$title = elgg_echo('projects_contact:inbox');

elgg_push_breadcrumb($title);

$content = elgg_list_entities_from_metadata(array(
	'type' => 'object',
	'subtype' => 'projects-contact',
	'metadata_name' => 'toGuid',
	'metadata_value' => $project_guid,
));

$body_vars = array(
	'folder' => 'inbox',
	'list' => $content,
);
$content = elgg_view_form('projects-contact/process', array(), $body_vars);

$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
));

echo elgg_view_page($title, $body);


