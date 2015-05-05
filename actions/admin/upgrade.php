<?php

/**
 * Run a upgrade on a batch of items
 *
 * Run for 2 seconds per request as set by $batch_run_time_in_secs.
 * This includes the engine loading time.
 */

$offset = (int) get_input('offset', 0);

// Disable the system log for upgrades to avoid exceptions when the schema changes
elgg_unregister_event_handler('log', 'systemlog', 'system_log_default_logger');
elgg_unregister_event_handler('all', 'all', 'system_log_listener');

$batch_run_time_in_secs = 2;

$class_name = get_input('class_name');
$class_name = '\Elgg\Upgrades\\' . $class_name;

$upgrade = new $class_name;

$upgrades = new \Elgg\UpgradeService();
$upgrades->prepareUpgrade($upgrade, $offset);

// If upgrade has run correctly, mark it done
if (get_input('upgrade_completed')) {
	// Set the upgrade as completed
	$upgrades->setProcessedUpgrade($upgrade);

	return true;
}

$result = $upgrades->runUpgrade();

// Give feedback for the UI
echo json_encode($result);
