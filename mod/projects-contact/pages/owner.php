<?php

$projectGuid = (int)get_input('projectGuid');

$title = elgg_echo('projects_contact:inbox');

elgg_push_breadcrumb($title);

$content = elgg_list_entities_from_metadata(array(
	'type' => 'object',
	'subtype' => 'projects-contact',
	'metadata_name' => 'toGuid',
	'metadata_value' => $projectGuid,
	'limit' => 10,
	'typeof' => '', //established on the form, depending on whether the message is readed or not 
	'view_toggle_type' => false,
	'no_results' => elgg_echo("projects-contact:none"),
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


