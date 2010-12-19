<?php

/**
 * Elgg profile icon cache/bypass
 * 
 * @package ElggProfile
 */


// Get DB settings
require_once(dirname(dirname(dirname(__FILE__))). '/engine/settings.php');

global $CONFIG;

$joindate = (int)$_GET['joindate'];
$guid = (int)$_GET['guid'];

$size = strtolower($_GET['size']);
if (!in_array($size,array('large','medium','small','tiny','master','topbar'))) {
	$size = "medium";
}

$mysql_dblink = @mysql_connect($CONFIG->dbhost,$CONFIG->dbuser,$CONFIG->dbpass, true);
if ($mysql_dblink) {
	if (@mysql_select_db($CONFIG->dbname,$mysql_dblink)) {

		// get dataroot and simplecache_enabled in one select for efficiency
		if ($result = mysql_query("select name, value from {$CONFIG->dbprefix}datalists where name in ('dataroot','simplecache_enabled')",$mysql_dblink)) {
			$simplecache_enabled = true;
			$row = mysql_fetch_object($result);
			while ($row) {
				if ($row->name == 'dataroot') {
					$dataroot = $row->value;
				} else if ($row->name == 'simplecache_enabled') {
					$simplecache_enabled = $row->value;
				}
				$row = mysql_fetch_object($result);
			}
		}

		@mysql_close($mysql_dblink);

		// if the simplecache is enabled, we get icon directly
		if ($simplecache_enabled) {

			// first try to read icon directly
			$user_path = date('Y/m/d/', $joindate) . $guid;
			$filename = "$dataroot$user_path/profile/{$guid}{$size}.jpg";
			$contents = @file_get_contents($filename);
			if (!empty($contents)) {
				header("Content-type: image/jpeg");
				header('Expires: ' . date('r',time() + 864000));
				header("Pragma: public");
				header("Cache-Control: public");
				header("Content-Length: " . strlen($contents));
				$splitString = str_split($contents, 1024);
				foreach($splitString as $chunk) {
					echo $chunk;
				}
				exit;
			}
		}
	}

}

// simplecache is not turned on or something went wrong so load engine and try that way
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
$user = get_entity($guid);
set_input('username', $user->username);
require_once(dirname(__FILE__).'/icon.php');
