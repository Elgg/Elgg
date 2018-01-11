<?php
/**
 * Upload a new file
 *
 * @package ElggFile
 */

$owner = elgg_get_page_owner_entity();

elgg_gatekeeper();
elgg_group_gatekeeper();

$title = elgg_echo('file:add');

// set up breadcrumbs
elgg_push_breadcrumb(elgg_echo('file'), "file/all");
if ($owner instanceof ElggUser) {
	elgg_push_breadcrumb($owner->getDisplayName(), "file/owner/$owner->username");
} else {
	elgg_push_breadcrumb($owner->getDisplayName(), "file/group/$owner->guid/all");
}

// create form
$form_vars = ['enctype' => 'multipart/form-data'];
$body_vars = file_prepare_form_vars();
$content = elgg_view_form('file/upload', $form_vars, $body_vars);

$body = elgg_view_layout('content', [
	'content' => $content,
	'title' => $title,
	'filter' => '',
]);

echo elgg_view_page($title, $body);
