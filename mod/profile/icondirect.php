<?php
/**
 * Elgg profile icon cache/bypass
 * 
 * 
 * @package ElggProfile
 */

require dirname(dirname(dirname(__FILE__))) . '/engine/start-minimal.php';

// won't be able to serve anything if no joindate or guid
if (!isset($_GET['joindate']) || !isset($_GET['guid'])) {
	header("HTTP/1.1 404 Not Found");
	exit;
}

$join_date = (int)$_GET['joindate'];
$last_cache = (int)$_GET['lastcache']; // icontime
$guid = (int)$_GET['guid'];

// If is the same ETag, content didn't changed.
$etag = $last_cache . $guid;
if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && trim($_SERVER['HTTP_IF_NONE_MATCH']) == "\"$etag\"") {
	header("HTTP/1.1 304 Not Modified");
	exit;
}

$size = strtolower($_GET['size']);
if (!in_array($size, array('large', 'medium', 'small', 'tiny', 'master', 'topbar'))) {
	$size = "medium";
}

$values = _elgg_minimal_boot_api()->fetchDatalistValues('dataroot');
$dataroot = $values['dataroot'];

$locator = new Elgg_EntityDirLocator($guid);
$user_path = $dataroot . $locator->getPath();

$filename = $user_path . "profile/{$guid}{$size}.jpg";

if (is_file($filename) && is_readable($filename)) {
	$size = filesize($filename);

	header("Content-type: image/jpeg");
	header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', strtotime("+6 months")), true);
	header("Pragma: public");
	header("Cache-Control: public");
	header("Content-Length: $size");
	header("ETag: \"$etag\"");

	readfile($filename);
	exit;
}

// something went wrong so load engine and try to forward to default icon
_elgg_minimal_boot_api()->bootElgg();

elgg_log("Profile icon direct failed.", "WARNING");
forward("_graphics/icons/user/default{$size}.gif");
