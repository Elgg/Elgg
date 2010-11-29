<?php
/**
 * Cache handler.
 * 
 * External access to cached CSS and JavaScript views. The cached file URLS
 * should be of the form: cache/<type>/<view>/<viewtype>/<unique_id> where
 * type is either css or js, view is the name of the cached view, and
 * unique_id is an identifier that is updated every time the cache is flushed.
 * The simplest way to maintain a unique identifier is to use the lastcache
 * variable in Elgg's config object.
 *
 * @see elgg_view_register_simplecache()
 *
 * @package Elgg.Core
 * @subpackage Cache
 */

// Get dataroot
require_once(dirname(dirname(__FILE__)) . '/settings.php');
$mysql_dblink = mysql_connect($CONFIG->dbhost, $CONFIG->dbuser, $CONFIG->dbpass, true);
if (!$mysql_dblink) {
	echo 'Cache error: unable to connect to database server';
	exit;
}

if (!mysql_select_db($CONFIG->dbname, $mysql_dblink)) {
	echo 'Cache error: unable to connect to Elgg database';
	exit;
}

$query = "select name, value from {$CONFIG->dbprefix}datalists where name = 'dataroot'";
$result = mysql_query($query, $mysql_dblink);
if (!$result) {
	echo 'Cache error: unable to get the data root';
	exit;
}
$row = mysql_fetch_object($result);
$dataroot = $row->value;



$dirty_request = $_GET['request'];
// only alphanumeric characters plus / and . and no '..'
$filter = array("options" => array("regexp" => "/^(\.?[a-zA-Z0-9\/]+)+$/"));
$request = filter_var($dirty_request, FILTER_VALIDATE_REGEXP, $filter);
if (!$request) {
	echo 'Cache error: bad request';
	exit;
}
$request = explode('/', $request);


//cache/<type>/<view>/<viewtype>/
$type = $request[0];
$view = $request[1];
$viewtype = $request[2];

switch ($type) {
	case 'css':
		header("Content-type: text/css", true);
		header('Expires: ' . date('r', time() + 86400000), true);
		header("Pragma: public", true);
		header("Cache-Control: public", true);

		$view = "css/$view";
		break;
	case 'js':
		header('Content-type: text/javascript', true);
		header('Expires: ' . date('r', time() + 864000000), true);
		header("Pragma: public", true);
		header("Cache-Control: public", true);

		$view = "js/$view";
		break;
}

$filename = $dataroot . 'views_simplecache/' . md5($viewtype . $view);

if (file_exists($filename)) {
	$contents = file_get_contents($filename);
} else {
	// someone trying to access a non-cached file or a race condition with cache flushing
	mysql_close($mysql_dblink);
	require_once(dirname(dirname(__FILE__)) . "/start.php");
	elgg_set_viewtype($viewtype);
	$contents = elgg_view($view);
}

echo $contents;
