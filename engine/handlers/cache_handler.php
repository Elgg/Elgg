<?php
/**
 * Cache handler.
 *
 * External access to cached CSS and JavaScript views. The cached file URLS
 * should be of the form: cache/<id>/<viewtype>/<name/of/view> where
 * id is an identifier that is updated every time the cache is flushed.
 * The simplest way to maintain a unique identifier is to use the lastcache
 * timestamp in Elgg's config object.
 *
 * @see elgg_register_simplecache_view()
 *
 * @package Elgg.Core
 * @subpackage Cache
 */

// Get dataroot
require_once(dirname(dirname(__FILE__)) . '/settings.php');
/* @var stdClass $CONFIG */

if (! empty($CONFIG->dataroot) && isset($CONFIG->simplecache_enabled)) {
	$dataroot = $CONFIG->dataroot;
	$simplecache_enabled = $CONFIG->simplecache_enabled;
} else {
	// get settings from datalists table

	$mysql_dblink = mysql_connect($CONFIG->dbhost, $CONFIG->dbuser, $CONFIG->dbpass, true);
	if (!$mysql_dblink) {
		echo 'Cache error: unable to connect to database server';
		exit;
	}

	if (!mysql_select_db($CONFIG->dbname, $mysql_dblink)) {
		echo 'Cache error: unable to connect to Elgg database';
		exit;
	}

	$query = "SELECT `name`, `value` FROM {$CONFIG->dbprefix}datalists
		WHERE `name` IN ('dataroot', 'simplecache_enabled')";

	$result = mysql_query($query, $mysql_dblink);
	if ($result) {
		while ($row = mysql_fetch_object($result)) {
			${$row->name} = $row->value;
		}
		mysql_free_result($result);
	}
	/* @var string $simplecache_enabled */
	/* @var string $dataroot */
	if (!$result || !isset($dataroot, $simplecache_enabled)) {
		echo 'Cache error: unable to get the data root';
		exit;
	}
}

$dirty_request = $_GET['request'];
// only alphanumeric characters plus /, ., and _ and no '..'
$filter = array("options" => array("regexp" => "/^(\.?[_a-zA-Z0-9\/]+)+$/"));
$request = filter_var($dirty_request, FILTER_VALIDATE_REGEXP, $filter);
if (!$request || !$simplecache_enabled) {
	echo 'Cache error: bad request';
	exit;
}

// testing showed regex to be marginally faster than array / string functions over 100000 reps
// it won't make a difference in real life and regex is easier to read.
// <ts>/<viewtype>/<name/of/view.and.dots>.<type>
$regex = '#([0-9]+)/([^/]+)/(.+)$#';
if (!preg_match($regex, $request, $matches)) {
	echo 'Cache error: bad request';
	exit;
}

$ts = $matches[1];
$viewtype = $matches[2];
$view = $matches[3];

// If is the same ETag, content didn't change.
$etag = $ts;
if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && trim($_SERVER['HTTP_IF_NONE_MATCH']) == "\"$etag\"") {
	header("HTTP/1.1 304 Not Modified");
	exit;
}

$segments = explode('/', $view);

switch ($segments[0]) {
	case 'css':
		header("Content-type: text/css", true);
		break;
	case 'js':
		header('Content-type: text/javascript', true);
		break;
}

header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', strtotime("+6 months")), true);
header("Pragma: public", true);
header("Cache-Control: public", true);
header("ETag: \"$etag\"");

$filename = $dataroot . 'views_simplecache/' . md5("$viewtype|$view");

if (file_exists($filename)) {
	// stream file from disk to output (fast)
	readfile($filename);
} else {
	// someone trying to access a non-cached file or a race condition with cache flushing
	if (isset($mysql_dblink)) {
		mysql_close($mysql_dblink);
	}

	require_once(dirname(dirname(__FILE__)) . "/start.php");
	// TODO: If ts == lastcache, cache ONLY the requested view and serve it.
	elgg_regenerate_simplecache();

	elgg_set_viewtype($viewtype);
	echo elgg_view($view);
}
