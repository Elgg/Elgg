<?php
/**
 * Image upload action
 */

$guid = get_input('guid');
$entity = get_entity($guid);

if (!($entity instanceof ElggEntity) || !$entity->canEdit()) {
	register_error(elgg_echo('image:upload:fail'));
	forward(REFERER);
}

$error = elgg_get_friendly_upload_error($_FILES['image']['error']);
if ($error) {
	register_error($error);
	forward(REFERER);
}

$icon_sizes = elgg_get_config('icon_sizes');

// get the images and save their file handlers into an array
// so we can do clean up if one fails.
$files = array();
foreach ($icon_sizes as $name => $size_info) {
	$resized = get_resized_image_from_uploaded_file('image', $size_info['w'], $size_info['h'], $size_info['square'], $size_info['upscale']);

	if ($resized) {
		//@todo Make these actual entities.  See exts #348.
		$file = new ElggFile();
		$file->owner_guid = $guid;
		$file->setFilename("icon/{$name}.jpg");
		$file->open('write');
		$file->write($resized);
		$file->close();
		$files[] = $file;
	} else {
		// cleanup on fail
		foreach ($files as $file) {
			$file->delete();
		}

		register_error(elgg_echo('image:resize:fail'));
		forward(REFERER);
	}
}

// reset crop coordinates
$entity->x1 = 0;
$entity->x2 = 0;
$entity->y1 = 0;
$entity->y2 = 0;

$entity->icontime = time();
if (elgg_trigger_event('iconupdate', $entity->type, $entity)) {
	system_message(elgg_echo("image:upload:success"));

	$view = 'river/entity/default/iconupdate';
	elgg_delete_river(array(
		'subject_guid' => $entity->guid,
		'view' => $view,
	));

	elgg_create_river_item(array(
		'view' => $view,
		'action_type' => 'update',
		'subject_guid' => elgg_get_logged_in_user_guid(),
		'object_guid' => $entity->guid,
	));
}

forward(REFERER);
