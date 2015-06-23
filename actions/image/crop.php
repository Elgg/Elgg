<?php
/**
 * Image crop action
 */

$guid = get_input('guid');
$entity = get_entity($guid);

if (!($entity instanceof ElggEntity) || !$entity->canEdit()) {
	register_error(elgg_echo('image:crop:fail'));
	forward(REFERER);
}

$x1 = (int) get_input('x1', 0);
$y1 = (int) get_input('y1', 0);
$x2 = (int) get_input('x2', 0);
$y2 = (int) get_input('y2', 0);

$filehandler = new ElggFile();
$filehandler->owner_guid = $entity->getGUID();
$filehandler->setFilename("icon/master.jpg");
$filename = $filehandler->getFilenameOnFilestore();

// Ensure that the image exists in the first place
if (!file_exists($filename)) {
	register_error(elgg_echo('image:crop:fail'));
	forward(REFERER);
}

$icon_sizes = elgg_get_config('icon_sizes');
unset($icon_sizes['master']);

// get the images and save their file handlers into an array
// so we can do clean up if one fails.
$files = array();
foreach ($icon_sizes as $name => $size_info) {
	$resized = get_resized_image_from_existing_file($filename, $size_info['w'], $size_info['h'], $size_info['square'], $x1, $y1, $x2, $y2, $size_info['upscale']);

	if ($resized) {
		//@todo Make these actual entities. See exts #348.
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

$entity->icontime = time();

$entity->x1 = $x1;
$entity->x2 = $x2;
$entity->y1 = $y1;
$entity->y2 = $y2;

system_message(elgg_echo('image:crop:success'));

$view = 'river/user/default/iconupdate';

elgg_delete_river(array(
	'subject_guid' => $entity->guid,
	'view' => $view,
));

elgg_create_river_item(array(
	'view' => $view,
	'action_type' => 'update',
	'subject_guid' => $entity->guid,
	'object_guid' => $entity->guid,
));

forward(REFERER);
