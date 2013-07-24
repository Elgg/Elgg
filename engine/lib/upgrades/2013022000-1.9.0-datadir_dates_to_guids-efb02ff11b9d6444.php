<?php
/**
 * Elgg 1.9.0 upgrade 2013022000
 * datadir_dates_to_guids
 *
 * Rewrites user directories in data directory to use guids instead of creation dates
 */

_elgg_services()->db->disableQueryCache();

$data_root = elgg_get_config('dataroot');

$failed = array();
$existing_bucket_dirs = array();
$cleanup_years = array();
$users = new ElggBatch('elgg_get_entities', array('type' => 'user', 'limit' => 0, 'callback' => ''), null, 100);
foreach ($users as $user_row) {
	$from = $data_root . make_matrix_2013022000($user_row);
	$bucket_dir = $data_root . getLowerBucketBound_2013022000($user_row->guid);
	$to =  "$bucket_dir/" . $user_row->guid;

	if (!is_dir($from)) {
		continue;
	}

	// make sure bucket dir exists
	if (!in_array($bucket_dir, $existing_bucket_dirs)) {
		// for some reason this dir already exists.
		if (is_dir($bucket_dir)) {
			$existing_bucket_dirs[] = $bucket_dir;
		} else {
			// same perms as ElggDiskFilestore.
			if (!mkdir($bucket_dir, 0700, true)) {
				$failed[] = "[$user_row->guid] Failed creating `$bucket_dir`";
				continue;
			}
			$existing_bucket_dirs[] = $bucket_dir;
		}
	}
	
	if (!rename($from, $to)) {
		$failed[] = "[$user_row->guid] Failed moving `$from` to `$to`";
	}

	// store the year for cleanup
	$year = date('Y', $user_row->time_created);
	if (!in_array($year, $cleanup_years)) {
		$cleanup_years[] = $year;
	}
}

// remove all dirs that are empty.
foreach ($cleanup_years as $year) {
	remove_dir_if_empty_2013022000($data_root . $year);
}

if ($failed) {
	$h = fopen("$data_root/2013022000_data_migration.log", 'w');
	fwrite($h, implode("\n", $failed));
	fclose($h);
	register_error("Problems migrating user data. See the admin area for more information.");
	elgg_add_admin_notice('2013022000_data_migration',
			"There were problems migrating some users' data. See the log file at
		{$data_root}2013022000_data_migration.log for a list of users who were affected.");
}

_elgg_services()->db->enableQueryCache();


/**
 * End of script. Utility functions below
 */


/**
 * Get the old directory location
 *
 * @param stdClass $user_row
 * @return string
 */
function make_matrix_2013022000($user_row) {
	$time_created = date('Y/m/d', $user_row->time_created);

	return "$time_created/$user_row->guid/";
}

/**
 * Remove directory if all users moved out of it
 *
 * @param string $dir
 * @return bool
 */
function remove_dir_if_empty_2013022000($dir) {
	$files = scandir($dir);

	foreach ($files as $file) {
		if ($file == '..' || $file == '.') {
			continue;
		}

		// not empty.
		if (is_file("$dir/$file")) {
			return false;
		}

		// subdir not empty
		if (is_dir("$dir/$file") && !remove_dir_if_empty_2013022000("$dir/$file")) {
			return false;
		}
	}

	// only contains empty subdirs
	return rmdir($dir);
}

/**
 * Get the base directory name as int
 *
 * @param int $guid GUID of the user
 * @return int
 */
function getLowerBucketBound_2013022000($guid) {
	$bucket_size = Elgg_EntityDirLocator::BUCKET_SIZE;
	if ($guid < 1) {
		return false;
	}
	return (int) max(floor($guid / $bucket_size) * $bucket_size, 1);
}
