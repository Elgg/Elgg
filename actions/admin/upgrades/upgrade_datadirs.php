<?php
/**
 * Move user data directories
 * 
 * Run for 2 seconds per request as set by $batch_run_time_in_secs. This includes
 * the engine loading time.
 */

// Migrate also directories that belong to hidden users
$access_status = access_get_show_hidden_status();
access_show_hidden_entities(true);

$helper = new Elgg\Upgrades\Helper2013022000(
	elgg_get_site_entity()->guid,
	elgg_get_config('dbprefix')
);

// from engine/start.php
global $START_MICROTIME;
$batch_run_time_in_secs = 2;

$data_root = elgg_get_config('dataroot');
$cleanup_years = array();
$num_successes = 0;
$num_errors = 0;
$is_complete = true;

_elgg_services()->db->disableQueryCache();

$batch = new ElggBatch('elgg_get_entities', $helper->getBatchOptions(), null, 50, false);

foreach ($batch as $user_row) {
	if ((microtime(true) - $START_MICROTIME) > $batch_run_time_in_secs) {
		$is_complete = false;
		break;
	}

	$guid = $user_row->guid;
	$from = $data_root . $helper->makeMatrix($user_row);
	$bucket_dir = $data_root . $helper->getLowerBucketBound($guid);
	$to = "$bucket_dir/$guid";

	if (!is_dir($from)) {
		$num_successes += 1;
		$helper->markSuccess($guid);
		continue;
	}

	// make sure bucket dir exists
	if (!is_dir($bucket_dir)) {
		// same perms as ElggDiskFilestore.
		if (!mkdir($bucket_dir, 0700, true)) {
			register_error("[$guid] Failed creating `$bucket_dir`");
			$num_errors += 1;
			$helper->markFailure($guid);
			continue;
		}
	}

	if (!rename($from, $to)) {
		register_error("[$guid] Failed moving `$from` to `$to`");
		$num_errors += 1;
		$helper->markFailure($guid);
	} else {
		$num_successes += 1;
		$helper->markSuccess($guid);
	}

	// store the year for cleanup
	$year = date('Y', $user_row->time_created);
	if (!in_array($year, $cleanup_years)) {
		$cleanup_years[] = $year;
	}
}

// remove all dirs that are empty.
// @todo this could take some time, so we may want to lower the batch run time to compensate.
foreach ($cleanup_years as $year) {
	$helper->removeDirIfEmpty($data_root . $year);
}

if ($is_complete && !$helper->hasFailures()) {
	// migration has completed, lets clean up
	$helper->forgetSuccesses();

	// set the upgrade as completed
	$factory = new ElggUpgrade();
	$upgrade = $factory->getUpgradeFromPath('admin/upgrades/datadirs');
	if ($upgrade instanceof ElggUpgrade) {
		$upgrade->setCompleted();
	}
}

access_show_hidden_entities($access_status);

_elgg_services()->db->enableQueryCache();

echo json_encode(array(
	'numSuccess' => $num_successes,
	'numErrors' => $num_errors,
));
