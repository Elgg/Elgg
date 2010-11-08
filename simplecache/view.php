<?php
/**
 * Simple cache viewer
 * Bypasses the engine to view simple cached CSS views.
 *
 * @package Elgg
 * @subpackage Core
 */

// Get DB settings, connect
require_once(dirname(dirname(__FILE__)) . '/engine/settings.php');

global $CONFIG, $viewinput, $override;
if (!isset($override)) {
	$override = FALSE;
}

$contents = '';

if (!isset($viewinput)) {
	$viewinput = $_GET;
}

if ($mysql_dblink = @mysql_connect($CONFIG->dbhost, $CONFIG->dbuser, $CONFIG->dbpass, true)) {
	$view_name = $viewinput['view'];
	$viewtype = $viewinput['viewtype'];
	if (empty($viewtype)) {
		$viewtype = 'default';
	}

	if (@mysql_select_db($CONFIG->dbname, $mysql_dblink)) {
		// get dataroot and simplecache_enabled in one select for efficiency
		$simplecache_enabled = true;
		if (!isset($dataroot)) {
			$query = "select name, value from {$CONFIG->dbprefix}datalists
				where name in ('dataroot', 'simplecache_enabled')";
			if ($result = mysql_query($query, $mysql_dblink)) {
				$row = mysql_fetch_object($result);

				while ($row) {
					if ($row->name == 'dataroot') {
						$dataroot = $row->value;
					} else if ($row->name == 'simplecache_enabled') {
						$simplecache_enabled = $row->value;
					}
					$row = mysql_fetch_object($result);
				}
				mysql_free_result($result);
			}
		}

		if ($simplecache_enabled || $override) {
			$filename = $dataroot . 'views_simplecache/' . md5($viewtype . $view_name);
			if (file_exists($filename)) {
				$contents = file_get_contents($filename);
			} else {
				$query = "INSERT into {$CONFIG->dbprefix}datalists
					set name = 'simplecache_lastupdate_$viewtype',
					value = '0' ON DUPLICATE KEY UPDATE value='0'";
				mysql_query($query);
			}
		}
	}
}

// load full engine if simplecache is disabled, overriden, or invalid
if (!$contents) {
	mysql_close($mysql_dblink);
	require_once(dirname(dirname(__FILE__)) . "/engine/start.php");
	$contents = elgg_view($view_name);
}

header("Content-Length: " . strlen($contents));

$split_output = str_split($contents, 1024);

foreach ($split_output as $chunk) {
	echo $chunk;
}
