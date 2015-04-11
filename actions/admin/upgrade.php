<?php

/**
 * Run a batch of an upgrade requested with XHR
 *
 * Run for 2 seconds per request as set by $batch_run_time_in_secs. This
 * includes the engine loading time.
 */

// from engine/start.php
global $START_MICROTIME;
$batch_run_time_in_secs = 2;

// Offset is the total amount of processed items so far
$offset = (int) get_input('processed', 0);
$class = get_input('class');

$access_status = access_get_show_hidden_status();
access_show_hidden_entities(true);

$upgrade = new $class;

// If upgrade has run correctly, mark it done
if (get_input('upgrade_completed')) {
	// Set the upgrade as completed

	/*
	// TODO Need to get rid of this way of doing things:
	$factory = new ElggUpgrade();
	$upgrade = $factory->getUpgradeFromPath('admin/upgrades/friendsprivateacls');

	if ($upgrade instanceof ElggUpgrade) {
		$upgrade->setCompleted();
	}
	*/

	// TODO This way of marking an upgrade done doesn't
	// exist, but maybe something like it should be added?
	elgg_upgrade_service()->setCompleted($upgrade);

	return true;
}

$success_count = 0;
$error_count = 0;

do {
	$class->run($offset);

	// TODO implement methods:
	$success_count = $class->getSuccessCount();
	$error_count = $class->getErrorCount();

} while ((microtime(true) - $START_MICROTIME) < $batch_run_time_in_secs);

access_show_hidden_entities($access_status);

// Give some feedback for the UI
echo json_encode(array(
	'numSuccess' => $success_count,
	'numErrors' => $error_count,
));
