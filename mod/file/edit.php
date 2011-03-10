<?php
/**
 * Edit a file
 *
 * @package ElggFile
 */

elgg_load_library('elgg:file');

gatekeeper();

$file_guid = (int) get_input('guid');
$file = new FilePluginFile($file_guid);
if (!$file) {
	forward();
}
if (!$file->canEdit()) {
	forward();
}

$title = elgg_echo('file:edit');

elgg_push_breadcrumb(elgg_echo('file'), "file/all");
elgg_push_breadcrumb($file->title, $file->getURL());
elgg_push_breadcrumb($title);

elgg_set_page_owner_guid($file->getContainerGUID());

$form_vars = array('enctype' => 'multipart/form-data');
$body_vars = file_prepare_form_vars($file);

$content = elgg_view_form('file/upload', $form_vars, $body_vars);

$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
	'buttons' => '',
));

echo elgg_view_page($title, $body);
