<?php
/**
 * View an image
 */

$guid = get_input('guid');

$entity = get_entity($guid);

// If entity doesn't exist, return default icon
if (!$entity) {
	forward(elgg_get_simplecache_url("icons/default/$size.png"));
}

$icon_sizes = elgg_get_config('icon_sizes');

// Get the size
$size = strtolower(get_input('size'));
if (!in_array($size, $icon_sizes)) {
	$size = 'medium';
}

$guid = $entity->getGUID();

// Try and get the icon
$filehandler = new ElggFile();
$filehandler->owner_guid = $guid;
$filehandler->setFilename("icon/{$size}.jpg");

$success = false;

try {
	if ($filehandler->open("read")) {
		$contents = $filehandler->read($filehandler->getSize());
		if ($contents) {
			$success = true;
		}
	}
} catch (InvalidParameterException $e) {
	elgg_log("Unable to get image for entity with GUID $guid", 'ERROR');
}

if (!$success) {
	forward(elgg_get_simplecache_url("icons/default/$size.png"));
}

header("Content-type: image/jpeg", true);
header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', strtotime("+6 months")), true);
header("Pragma: public", true);
header("Cache-Control: public", true);
header("Content-Length: " . strlen($contents));

echo $contents;
