<?php
/**
 * Elgg profile icon cache/bypass
 * 
 * 
 * @package ElggProfile
 */

// Get DB settings
require_once(dirname(dirname(dirname(__FILE__))). '/engine/settings.php');

global $CONFIG;

$join_date = (int)$_GET['joindate'];
$last_cache = (int)$_GET['lastcache']; // icontime
$guid = (int)$_GET['guid'];

// If is the same ETag, content didn't changed.
$etag = $last_cache . $guid;
if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && trim($_SERVER['HTTP_IF_NONE_MATCH']) == $etag) {
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
		$result = mysql_query("select name, value from {$CONFIG->dbprefix}datalists where name='dataroot'", $mysql_dblink);
		if ($result) {
			$row = mysql_fetch_object($result);
			while ($row) {
				if ($row->name == 'dataroot') {
					$data_root = $row->value;
				}
				$row = mysql_fetch_object($result);
			}
		}

		@mysql_close($mysql_dblink);

		if (isset($data_root)) {

			// this depends on ElggDiskFilestore::makeFileMatrix()
			$user_path = date('Y/m/d/', $join_date) . $guid;

			$filename = "$data_root$user_path/profile/{$guid}{$size}.jpg";
			$contents = @file_get_contents($filename);
			if (!empty($contents)) {
				header("Content-type: image/jpeg");
				header('Expires: ' . date('r', strtotime("+6 months")), true);
				header("Pragma: public");
				header("Cache-Control: public");
				header("Content-Length: " . strlen($contents));
				header("ETag: $etag");
				// this chunking is done for supposedly better performance
				$split_string = str_split($contents, 1024);
				foreach ($split_string as $chunk) {
					echo $chunk;
				}
				exit;
			}
		}
	}

}

// something went wrong so load engine and try to forward to default icon
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
elgg_log("Profile icon direct failed.", "WARNING");
forward("_graphics/icons/user/default{$size}.gif");
