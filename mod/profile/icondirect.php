<?php
/**
 * Elgg profile icon cache/bypass
 *
 *
 * @package ElggProfile
 */

$engine_dir = dirname(dirname(dirname(__FILE__))). '/engine/';

// Get DB settings
require_once $engine_dir . 'settings.php';

global $CONFIG;

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

$mysql_dblink = @mysql_connect($CONFIG->dbhost, $CONFIG->dbuser, $CONFIG->dbpass, true);
if ($mysql_dblink) {
	if (@mysql_select_db($CONFIG->dbname, $mysql_dblink)) {
		$q = "SELECT name, value FROM {$CONFIG->dbprefix}datalists WHERE name in ('dataroot', 'path')";
		$result = mysql_query($q, $mysql_dblink);
		if ($result) {
			$row = mysql_fetch_object($result);
			while ($row) {
				if ($row->name == 'dataroot') {
					$data_root = $row->value;
				} elseif ($row->name == 'path') {
					$elgg_path = $row->value;
				}

				$row = mysql_fetch_object($result);
			}
		}

		@mysql_close($mysql_dblink);

		if (isset($data_root) && isset($elgg_path)) {
			require_once $engine_dir . "classes/Elgg/EntityDirLocator.php";

			$locator = new Elgg_EntityDirLocator($guid);
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
	}
}

// something went wrong so load engine and try to forward to default icon
require_once $engine_dir . "start.php";
elgg_log("Profile icon direct failed.", "WARNING");
forward("_graphics/icons/user/default{$size}.gif");
