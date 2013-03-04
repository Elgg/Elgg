<?php
/**
 * Elgg 1.9.0 upgrade 2013022000
 * datadir_dates_to_guids
 *
 * Rewrites data directory to use owner guids instead of creation dates
 */

$data_root = elgg_get_config('dataroot');

$failed = array();
$existing_bucket_dirs = array();
$cleanup_years = array();
$users = new ElggBatch('elgg_get_entities', array('type' => 'user', 'limit' => 0), 50);
foreach ($users as $user) {
	$from = $data_root . make_matrix_2013022000($user->getGUID());
	$bucket_dir = $data_root . getLowerBucketBound_2013022000($user->guid);
	$to =  "$bucket_dir/" . $user->getGUID();

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
				$failed[] = "[$user->guid ($user->username)] Failed creating `$bucket_dir`";
				continue;
			}
			$existing_bucket_dirs[] = $bucket_dir;
		}
	}
	
	if (!rename($from, $to)) {
		$failed[] = "[$user->guid ($user->username)] Failed moving `$from` to `$to`";
	}

	// store the year for cleanup
	$year = date('Y', $user->time_created);
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


function make_matrix_2013022000($guid) {
		$entity = get_entity($guid);

		if (!($entity instanceof ElggEntity) || !$entity->time_created) {
			return false;
		}

		$time_created = date('Y/m/d', $entity->time_created);

		return "$time_created/$entity->guid/";
}


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

function getLowerBucketBound_2013022000($guid, $bucket_size = null) {
		if (!$bucket_size || $bucket_size < 1) {
			$bucket_size = Elgg_EntityDirLocator::BUCKET_SIZE;
		}
		if ($guid < 1) {
			return false;
		}
		return (int) max(floor($guid / $bucket_size) * $bucket_size, 1);
	}