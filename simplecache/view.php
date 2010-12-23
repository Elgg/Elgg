<?php
/**
 * Simple cache viewer
 * Bypasses the engine to view simple cached CSS views.
 *
 * @package Elgg
 * @subpackage Core
 */

// Get DB settings, connect
require_once(dirname(dirname(__FILE__)). '/engine/settings.php');

global $CONFIG, $viewinput, $override;

if (!isset($override)) {
	$override = FALSE;
}

$contents = '';

if (!isset($viewinput)) {
	$viewinput = $_GET;
}

$viewtype = isset($viewinput['viewtype']) ? $viewinput['viewtype'] : 'default';
if (empty($viewtype)) {
	$viewtype = 'default';
}

$mysql_dblink = @mysql_connect($CONFIG->dbhost,$CONFIG->dbuser,$CONFIG->dbpass, true);
$db = @mysql_select_db($CONFIG->dbname, $mysql_dblink);

$view = $viewinput['view'];
$viewtype = mysql_real_escape_string($viewtype, $mysql_dblink);

if ($db) {
	// get dataroot and simplecache_enabled in one select for efficiency
	$simplecache_enabled = true;
	if (!isset($dataroot)) {
		$result = mysql_query("select name, value from {$CONFIG->dbprefix}datalists where name in ('dataroot','simplecache_enabled')", $mysql_dblink);
		if ($result) {
			$row = mysql_fetch_object($result);

			while ($row) {
				switch ($row->name) {
					case 'dataroot':
						$dataroot = $row->value;
						break;

					case 'simplecache_enabled':
						$simplecache_enabled = $row->value;
						break;
				}

				$row = mysql_fetch_object($result);
			}
		}
	}

	if ($simplecache_enabled && !$override) {
		// check against valid view type cache
		$view_types_file = $dataroot . 'view_types';
		$valid_view_type = false;

		if ($viewtype == 'default') {
			$valid_view_type = true;
		} elseif (file_exists($view_types_file)) {
			$cached_view_types = file_get_contents($view_types_file);
			$valid_view_types = unserialize($cached_view_types);
			$valid_view_type = in_array($viewtype, $valid_view_types);
		}

		if ($valid_view_type) {
			$filename = $dataroot . 'views_simplecache/' . md5($viewtype . $view);
			if (file_exists($filename)) {
				$contents = file_get_contents($filename);
			} else {
				mysql_query("INSERT into {$CONFIG->dbprefix}datalists set name = 'simplecache_lastupdate_$viewtype', value = '0' ON DUPLICATE KEY UPDATE value='0'", $mysql_dblink);
			}
		}
	}
}

// load full engine if simplecache is disabled, overriden, or invalid
if (!$contents) {
	mysql_close($mysql_dblink);
	require_once(dirname(dirname(__FILE__)) . "/engine/start.php");
	$contents = elgg_view($view);
}

header("Content-Length: " . strlen($contents));

$split_output = str_split($contents, 1024);

foreach($split_output as $chunk) {
	echo $chunk;
}
