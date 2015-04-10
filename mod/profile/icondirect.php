<?php
/**
 * Elgg profile icon cache/bypass
 * 
 * 
 * @package ElggProfile
 */

// won't be able to serve anything if no joindate or guid
if (!isset($_GET['joindate']) || !isset($_GET['guid'])) {
	header("HTTP/1.1 404 Not Found");
	exit;
}

$join_date = (int)$_GET['joindate'];
$last_cache = empty($_GET['lastcache']) ? 0 : (int)$_GET['lastcache']; // icontime
$guid = (int)$_GET['guid'];

// If is the same ETag, content didn't changed.
$etag = $last_cache . $guid;
if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && trim($_SERVER['HTTP_IF_NONE_MATCH']) == "\"$etag\"") {
	header("HTTP/1.1 304 Not Modified");
	exit;
}

$base_dir = dirname(dirname(dirname(__FILE__)));

// Get DB settings
require_once $base_dir . '/engine/settings.php';
require_once $base_dir . '/vendor/autoload.php';

global $CONFIG;

$size = "medium";
if (!empty($_GET['size'])) {
	$size = strtolower($_GET['size']);
	if (!in_array($size, array('large', 'medium', 'small', 'tiny', 'master', 'topbar'))) {
		$size = "medium";
	}
}

$path = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR;

$data_root = call_user_func(function () use ($CONFIG) {
	if (isset($CONFIG->dataroot)) {
		return rtrim($CONFIG->dataroot, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
	}

	// must get from DB
	$conf = new \Elgg\Database\Config($CONFIG);
	$db = new \Elgg\Database($conf, new \Elgg\Logger(new \Elgg\PluginHooksService()));

	try {
		$row = $db->getDataRow("
			SELECT `value`
			FROM {$db->getTablePrefix()}datalists
			WHERE `name` = 'dataroot'
		");
		if (!$row) {
			return "";
		}
	} catch (\DatabaseException $e) {
		// we're going to let the engine figure out what's happening...
		return '';
	}

	return rtrim($row->value, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
});

if ($data_root) {
	$locator = new \Elgg\EntityDirLocator($guid);
	$user_path = $data_root . $locator->getPath();

	$filename = $user_path . "profile/{$guid}{$size}.jpg";
	$filesize = @filesize($filename);

	if ($filesize) {
		header("Content-type: image/jpeg");
		header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', strtotime("+6 months")), true);
		header("Pragma: public");
		header("Cache-Control: public");
		header("Content-Length: $filesize");
		header("ETag: \"$etag\"");
		readfile($filename);
		exit;
	}
}

// something went wrong so load engine and try to forward to default icon
require_once $base_dir . "/engine/start.php";
elgg_log("Profile icon direct failed.", "WARNING");
forward("_graphics/icons/user/default{$size}.gif");
