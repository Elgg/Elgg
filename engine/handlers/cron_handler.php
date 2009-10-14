<?php
/**
 * Elgg Cron handler.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

// Load Elgg engine
define('externalpage',true);
require_once("../start.php");
global $CONFIG;

// Get basic parameters
$period = get_input('period');
if (!$period) {
	throw new CronException(sprintf(elgg_echo('CronException:unknownperiod'), $period));
}

// Get a list of parameters
$params = array();
$params['time'] = time();

foreach ($CONFIG->input as $k => $v) {
	$params[$k] = $v;
}

// Trigger hack

// Data to return to
$std_out = "";
$old_stdout = "";
ob_start();

$old_stdout = trigger_plugin_hook('cron', $period, $params, $old_stdout);
$std_out = ob_get_clean();

// Return event
echo $std_out . $old_stdout;