<?php
/**
 * Elgg file uploader/edit action
 */

// Get variables
$title = elgg_get_title_input();
$desc = get_input('description');
$access_id = (int) get_input('access_id');
$container_guid = (int) get_input('container_guid', 0);
$guid = (int) get_input('file_guid');
$tags = (string) get_input('tags');

$container_guid = $container_guid ?: elgg_get_logged_in_user_guid();

// check if upload attempted and failed
$uploaded_file = elgg_get_uploaded_file('upload', false);
if ($uploaded_file && !$uploaded_file->isValid()) {
	$error = elgg_get_friendly_upload_error($uploaded_file->getError());
	return elgg_error_response($error);
}

// check whether this is a new file or an edit
$new_file = empty($guid);

if ($new_file) {
	if (empty($uploaded_file)) {
		return elgg_error_response(elgg_echo('file:uploadfailed'));
	}
	
	$file = new ElggFile();
} else {
	// load original file object
	$file = get_entity($guid);
	if (!$file instanceof ElggFile) {
		return elgg_error_response(elgg_echo('file:cannotload'));
	}

	// user must be able to edit file
	if (!$file->canEdit()) {
		return elgg_error_response(elgg_echo('file:noaccess'));
	}
}

if (!elgg_is_empty($title)) {
	$file->title = $title;
}

$file->description = $desc;
$file->access_id = $access_id;
$file->container_guid = $container_guid;
$file->tags = elgg_string_to_array($tags);

$file->save();

if ($uploaded_file && $uploaded_file->isValid()) {
	if (!$file->acceptUploadedFile($uploaded_file)) {
		return elgg_error_response(elgg_echo('file:uploadfailed'));
	}
	
	if (!$file->save()) {
		return elgg_error_response(elgg_echo('file:uploadfailed'));
	}

	// remove old icons
	$file->deleteIcon();
	
	// update icons
	if ($file->getSimpleType() === 'image') {
		$file->saveIconFromElggFile($file);
	}
}

$forward = $file->getURL();

// handle results differently for new files and file updates
if ($new_file) {
	$container = get_entity($container_guid);
	if ($container instanceof \ElggGroup) {
		$forward = elgg_generate_url('collection:object:file:group', ['guid' => $container->guid]);
	} else {
		$forward = elgg_generate_url('collection:object:file:owner', ['username' => $container->username]);
	}
	
	elgg_create_river_item([
		'action_type' => 'create',
		'object_guid' => $file->guid,
	]);
}

return elgg_ok_response('', elgg_echo('file:saved'), $forward);
