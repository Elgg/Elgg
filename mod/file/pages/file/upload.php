<?php
/**
 * Upload a new file
 *
 * @package ElggFile
 */

elgg_load_library('elgg:file');

$owner = elgg_get_page_owner_entity();

gatekeeper();
group_gatekeeper();

$title = elgg_echo('file:add');

// set up breadcrumbs
elgg_push_breadcrumb(elgg_echo('file'), "file/all");
if (elgg_instanceof($owner, 'user')) {
	elgg_push_breadcrumb($owner->name, "file/owner/$owner->username");
} else {
	elgg_push_breadcrumb($owner->name, "file/group/$owner->guid/all");
}
elgg_push_breadcrumb($title);

// create form
$form_vars = array('enctype' => 'multipart/form-data');
$body_vars = file_prepare_form_vars();
$content = elgg_view_form('file/upload', $form_vars, $body_vars);

$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
	'buttons' => '',
));

echo elgg_view_page($title, $body);
