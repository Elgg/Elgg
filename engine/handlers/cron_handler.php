<?php
/**
 * Cron handlers
 *
 * This file dispatches cron actions.  It is called via a URL rewrite in .htaccess
 * from http://site/p/.  Anything after 'action/' is considered the action
 * and will be passed to {@link action()}.
 *
 * @package Elgg.Core
 * @subpackage Actions
 * @link http://docs.elgg.org/Tutorials/Actions
 *
 * @todo
 */

require_once(dirname(dirname(__FILE__)) . "/start.php");

$period = get_input('period');
if (!$period) {
	throw new CronException(elgg_echo('CronException:unknownperiod', array($period)));
}

// Get a list of parameters
$params = array();
$params['time'] = time();

foreach ($CONFIG->input as $k => $v) {
	$params[$k] = $v;
}

// Data to return to
$std_out = "";
$old_stdout = "";
ob_start();

$old_stdout = elgg_trigger_plugin_hook('cron', $period, $params, $old_stdout);
$std_out = ob_get_clean();

// Return event
echo $std_out . $old_stdout;