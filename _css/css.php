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

$viewinput['view'] = 'css';
$viewinput['viewtype'] = $_GET['viewtype'];

header("Content-type: text/css", true);
header('Expires: ' . date('r',time() + 86400000), true);
header("Pragma: public", true);
header("Cache-Control: public", true);

require_once(dirname(dirname(__FILE__)) . '/simplecache/view.php');
