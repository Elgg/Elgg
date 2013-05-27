<?php

$guid = get_input('guid');

$contact = get_entity($guid);
$projectcontact = get_entity($contact->toGuid);

$contact->readed = true;
$contact->save();

$url = "projects_contact/owner/{$projectcontact->guid}/{$projectcontact->name}";
$title = elgg_echo('projects_contact:projects');
elgg_push_breadcrumb($title, $url);

$title = elgg_echo('projects_contact:read');
elgg_push_breadcrumb($title);

$content = elgg_list_entities(array(
	'type' => 'object',
	'guid' => $guid,
	'subtype' => 'projects-contact',
	'typeof' => 'single',
	'view_toggle_type' => false,
	'no_results' => elgg_echo("projects-contact:none")
));


$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
));

echo elgg_view_page($title, $body);







