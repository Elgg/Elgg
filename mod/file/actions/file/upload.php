<?php
/**
 * Elgg file uploader/edit action
 */

$guid = (int) get_input('file_guid');

$values = [];
$uploaded_file = null;

$fields = elgg()->fields->get('object', 'file');
foreach ($fields as $field) {
	$value = null;
	
	$name = elgg_extract('name', $field);
	switch (elgg_extract('#type', $field)) {
		case 'file':
			$uploaded_file = elgg_get_uploaded_file('upload', false);
			if ($uploaded_file && !$uploaded_file->isValid()) {
				$error = elgg_get_friendly_upload_error($uploaded_file->getError());
				
				return elgg_error_response($error);
			}
			
			if (empty($guid) && empty($uploaded_file) && elgg_extract('required', $field)) {
				return elgg_error_response(elgg_echo('file:uploadfailed'));
			}
			continue(2);
		case 'tags':
			$value = elgg_string_to_array((string) get_input($name));
			break;
		default:
			if ($name === 'title') {
				$value = elgg_get_title_input();
			} else {
				$value = get_input($name);
			}
			break;
	}
	
	if (elgg_extract('required', $field) && elgg_is_empty($value)) {
		return elgg_error_response(elgg_echo('error:missing_data'));
	}
	
	$values[$name] = $value;
}

// check whether this is a new file or an edit
$new_file = empty($guid);

if ($new_file) {
	$file = new \ElggFile();
	$file->container_guid = (int) get_input('container_guid');
} else {
	// load original file object
	$file = get_entity($guid);
	if (!$file instanceof \ElggFile) {
		return elgg_error_response(elgg_echo('file:cannotload'));
	}

	// user must be able to edit file
	if (!$file->canEdit()) {
		return elgg_error_response(elgg_echo('file:noaccess'));
	}
}

foreach ($values as $name => $value) {
	$file->{$name} = $value;
}

if (!$file->save()) {
	return elgg_error_response(elgg_echo('file:uploadfailed'));
}

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
	$container = $file->getContainerEntity();
	if ($container instanceof \ElggGroup) {
		$forward = elgg_generate_url('collection:object:file:group', ['guid' => $container->guid]);
	} else {
		$forward = elgg_generate_url('collection:object:file:owner', ['username' => $container->username]);
	}
	
	elgg_create_river_item([
		'action_type' => 'create',
		'object_guid' => $file->guid,
		'target_guid' => $file->container_guid,
	]);
}

return elgg_ok_response('', elgg_echo('file:saved'), $forward);
