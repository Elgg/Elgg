<?php

$guid = get_input('guid');

$contact = get_entity($guid);
$project = get_entity($contact->toGuid);

$contact->readed = true;
$contact->save();

$url = "projects_contact/owner/{$project->alias}";
elgg_push_breadcrumb(elgg_echo('projects_contact:projects'), $url);

$title = elgg_echo('projects_contact:read');
elgg_push_breadcrumb($title);

$content = elgg_list_entities(array(
	'type' => 'object',
	'guid' => $guid,
	'subtype' => 'projects-contact',
));


$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
));

echo elgg_view_page($title, $body);
