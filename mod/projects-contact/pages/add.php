<?php
	
$page_owner = elgg_get_page_owner_entity();

$title = elgg_echo('projects_contact:add');
elgg_push_breadcrumb($page_owner->name);

$vars = projects_contact_prepare_form_vars();
$content = elgg_view_form('projects-contact/save', array(), $vars);

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);
