<?php
/**
 * Elgg file uploader/edit action
 *
 * @package ElggFile
 */

// Get variables
$title = htmlspecialchars(get_input('title', '', false), ENT_QUOTES, 'UTF-8');
$desc = get_input("description");
$access_id = (int) get_input("access_id");
$container_guid = (int) get_input('container_guid', 0);
$guid = (int) get_input('file_guid');
$tags = get_input("tags");

if ($container_guid == 0) {
	$container_guid = elgg_get_logged_in_user_guid();
}

elgg_make_sticky_form('file');

// check if upload attempted and failed
if (!empty($_FILES['upload']['name']) && $_FILES['upload']['error'] != 0) {
	$error = elgg_get_friendly_upload_error($_FILES['upload']['error']);

	register_error($error);
	forward(REFERER);
}

// check whether this is a new file or an edit
$new_file = true;
if ($guid > 0) {
	$new_file = false;
}

if ($new_file) {
	// must have a file if a new file upload
	if (empty($_FILES['upload']['name'])) {
		$error = elgg_echo('file:nofile');
		register_error($error);
		forward(REFERER);
	}

	$file = new ElggFile();
	$file->subtype = "file";

	// if no title on new upload, grab filename
	if (empty($title)) {
		$title = htmlspecialchars($_FILES['upload']['name'], ENT_QUOTES, 'UTF-8');
	}

} else {
	// load original file object
	$file = get_entity($guid);
	if (!$file instanceof ElggFile) {
		register_error(elgg_echo('file:cannotload'));
		forward(REFERER);
	}
	/* @var ElggFile $file */

	// user must be able to edit file
	if (!$file->canEdit()) {
		register_error(elgg_echo('file:noaccess'));
		forward(REFERER);
	}

	if (!$title) {
		// user blanked title, but we need one
		$title = $file->title;
	}
}

$file->title = $title;
$file->description = $desc;
$file->access_id = $access_id;
$file->container_guid = $container_guid;
$file->tags = string_to_tag_array($tags);

// we have a file upload, so process it
if (isset($_FILES['upload']['name']) && !empty($_FILES['upload']['name'])) {

	$prefix = "file/";

	// if previous file, delete it
	if (!$new_file) {
		$filename = $file->getFilenameOnFilestore();
		if (file_exists($filename)) {
			unlink($filename);
		}
	}

	$filestorename = elgg_strtolower(time().$_FILES['upload']['name']);

	$file->setFilename($prefix . $filestorename);
	$file->originalfilename = $_FILES['upload']['name'];
	$mime_type = $file->detectMimeType($_FILES['upload']['tmp_name'], $_FILES['upload']['type']);

	$file->setMimeType($mime_type);
	$file->simpletype = elgg_get_file_simple_type($mime_type);

	// Open the file to guarantee the directory exists
	$file->open("write");
	$file->close();
	move_uploaded_file($_FILES['upload']['tmp_name'], $file->getFilenameOnFilestore());

	$guid = $file->save();

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
} else {
	// not saving a file but still need to save the entity to push attributes to database
	$file->save();
}

// file saved so clear sticky form
elgg_clear_sticky_form('file');


// handle results differently for new files and file updates
if ($new_file) {
	if ($guid) {
		$message = elgg_echo("file:saved");
		system_message($message);
		elgg_create_river_item(array(
			'view' => 'river/object/file/create',
			'action_type' => 'create',
			'subject_guid' => elgg_get_logged_in_user_guid(),
			'object_guid' => $file->guid,
		));
	} else {
		// failed to save file object - nothing we can do about this
		$error = elgg_echo("file:uploadfailed");
		register_error($error);
	}

	$container = get_entity($container_guid);
	if (elgg_instanceof($container, 'group')) {
		forward("file/group/$container->guid/all");
	} else {
		forward("file/owner/$container->username");
	}

} else {
	if ($guid) {
		system_message(elgg_echo("file:saved"));
	} else {
		register_error(elgg_echo("file:uploadfailed"));
	}

	forward($file->getURL());
}
