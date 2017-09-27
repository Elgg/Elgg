<?php
/**
 * Elgg file uploader/edit action
 *
 * @package ElggFile
 */

// Get variables
$title = elgg_get_title_input();
$desc = get_input('description');
$access_id = (int) get_input('access_id');
$container_guid = (int) get_input('container_guid', 0);
$guid = (int) get_input('file_guid');
$tags = get_input('tags');

$container_guid = $container_guid ?: elgg_get_logged_in_user_guid();

elgg_make_sticky_form('file');

// check if upload attempted and failed
$uploaded_file = elgg_get_uploaded_file('upload', false);
if ($uploaded_file && !$uploaded_file->isValid()) {
	$error = elgg_get_friendly_upload_error($uploaded_file->getError());
	return elgg_error_response($error);
}

// check whether this is a new file or an edit
$new_file = empty($guid);

if ($new_file) {
	$file = new ElggFile();
} else {
	// load original file object
	$file = get_entity($guid);
	if (!$file instanceof ElggFile) {
		return elgg_error_response(elgg_echo('file:cannotload'));
	}
	/* @var ElggFile $file */

	// user must be able to edit file
	if (!$file->canEdit()) {
		return elgg_error_response(elgg_echo('file:noaccess'));
	}
}

if ($title) {
	$file->title = $title;
}
$file->description = $desc;
$file->access_id = $access_id;
$file->container_guid = $container_guid;
$file->tags = string_to_tag_array($tags);

if ($uploaded_file && $uploaded_file->isValid()) {
	if ($file->acceptUploadedFile($uploaded_file)) {
		$guid = $file->save();
	}
	
	if ($guid && $file->saveIconFromElggFile($file)) {
		$file->thumbnail = $file->getIcon('small')->getFilename();
		$file->smallthumb = $file->getIcon('medium')->getFilename();
		$file->largethumb = $file->getIcon('large')->getFilename();
	} else {
		$file->deleteIcon();
		unset($file->thumbnail);
		unset($file->smallthumb);
		unset($file->largethumb);
	}
} else if ($file->exists()) {
	$file->save();

	if (isset($reset_icon_urls)) {
		// we touch the thumbs because we want new URLs from \Elgg\FileService\File::getURL
		$thumbnails = [$file->thumbnail, $file->smallthumb, $file->largethumb];
		foreach ($thumbnails as $thumbnail) {
			$thumbfile = new ElggFile();
			$thumbfile->owner_guid = $file->owner_guid;
			$thumbfile->setFilename($thumbnail);
			if ($thumbfile->exists()) {
				$thumb_filename = $thumbfile->getFilenameOnFilestore();
				touch($thumb_filename);
			}
		}
	}
}

// file saved so clear sticky form
elgg_clear_sticky_form('file');

if (empty($guid)) {
	return elgg_error_response(elgg_echo('file:uploadfailed'));
}

$forward = $file->getURL();

// handle results differently for new files and file updates
if ($new_file) {
	
	$container = get_entity($container_guid);
	if (elgg_instanceof($container, 'group')) {
		$forward = "file/group/{$container->guid}/all";
	} else {
		$forward = "file/owner/{$container->username}";
	}
	
	elgg_create_river_item([
		'view' => 'river/object/file/create',
		'action_type' => 'create',
		'subject_guid' => elgg_get_logged_in_user_guid(),
		'object_guid' => $file->guid,
	]);
}

return elgg_ok_response('', elgg_echo('file:saved'), $forward);
