<?php
/**
 * Elgg CSS file
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

global $viewinput, $override;

$viewinput['view'] = 'js/' . $_GET['js'];
$viewinput['viewtype'] = $_GET['viewtype'];

header('Content-type: text/javascript');
header('Expires: ' . date('r',time() + 864000000));
header("Pragma: public");
header("Cache-Control: public");

require_once(dirname(dirname(__FILE__)) . '/simplecache/view.php');