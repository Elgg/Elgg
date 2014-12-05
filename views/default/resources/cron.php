<?php
$page = $vars['segments'];

if (!isset($page[0])) {
	forward();
}

$period = strtolower($page[0]);

$allowed_periods = elgg_get_config('elgg_cron_periods');

if (!in_array($period, $allowed_periods)) {
	throw new \CronException("$period is not a recognized cron period.");
}

// Get a list of parameters
$params = array();
$params['time'] = time();

// Data to return to
$old_stdout = "";
ob_start();

$old_stdout = elgg_trigger_plugin_hook('cron', $period, $params, $old_stdout);
$std_out = ob_get_clean();

echo $std_out . $old_stdout;
