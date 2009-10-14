<?php
/**
 * Elgg CSS file
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

/*

require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

$default_css = elgg_view("css");

header("Content-type: text/css", true);
header('Expires: ' . date('r',time() + 864000), true);
header("Pragma: public", true);
header("Cache-Control: public", true);
header("Content-Length: " . strlen($default_css));

echo $default_css;
*/

define('externalpage',true);

global $viewinput, $override;
//$override = true;
$viewinput['view'] = 'js/' . $_GET['js'];
$viewinput['viewtype'] = $_GET['viewtype'];

header('Content-type: text/javascript');
header('Expires: ' . date('r',time() + 864000000));
header("Pragma: public");
header("Cache-Control: public");
// header("Content-Length: " . strlen($return));

require_once(dirname(dirname(__FILE__)) . '/simplecache/view.php');