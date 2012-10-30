<?php
/**
 * Icon display
 *
 * @package ElggGroups
 */

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

$group_guid = get_input('group_guid');

/* @var ElggGroup $group */
$group = get_entity($group_guid);
if (!($group instanceof ElggGroup)) {
	header("HTTP/1.1 404 Not Found");
	exit;
}

// If is the same ETag, content didn't changed.
$etag = $group->icontime . $group_guid;
if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && trim($_SERVER['HTTP_IF_NONE_MATCH']) == "\"$etag\"") {
	header("HTTP/1.1 304 Not Modified");
	exit;
}

$size = strtolower(get_input('size'));
if (!in_array($size, array('large', 'medium', 'small', 'tiny', 'master', 'topbar')))
	$size = "medium";

$success = false;

$filehandler = new ElggFile();
$filehandler->owner_guid = $group->owner_guid;
$filehandler->setFilename("groups/" . $group->guid . $size . ".jpg");

$success = false;
if ($filehandler->open("read")) {
	if ($contents = $filehandler->read($filehandler->size())) {
		$success = true;
	}
}

if (!$success) {
	$location = elgg_get_plugins_path() . "groups/graphics/default{$size}.gif";
	$contents = @file_get_contents($location);
}

header("Content-type: image/jpeg");
header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', strtotime("+10 days")), true);
header("Pragma: public");
header("Cache-Control: public");
header("Content-Length: " . strlen($contents));
header("ETag: \"$etag\"");
echo $contents;
