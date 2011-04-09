<?php

/**
 * Move user's data directories from using username to registration date
 */

/**
 * Generates a file matrix like Elgg 1.0 did
 *
 * @param string $username Username of user
 *
 * @return string File matrix path
 */
function file_matrix_1_0($username) {
	$matrix = "";

	$len = strlen($username);
	if ($len > 5) {
		$len = 5;
	}

	for ($n = 0; $n < $len; $n++) {
		if (ctype_alnum($username[$n])) {
			$matrix .= $username[$n] . "/";
		}
	}

	return $matrix . $username . "/";
}


/**
 * Generate a file matrix like Elgg 1.1, 1.2 and 1.5
 *
 * @param string $filename The filename
 *
 * @return string
 */
function file_matrix_1_1($filename) {
	$matrix = "";

	$name = $filename;
	$filename = mb_str_split($filename);
	if (!$filename) {
		return false;
	}

	$len = count($filename);
	if ($len > 5) {
		$len = 5;
	}

	for ($n = 0; $n < $len; $n++) {
		$matrix .= $filename[$n] . "/";
	}

	return $matrix . $name . "/";
}

/**
 * Handle splitting multibyte strings
 *
 * @param string $string  String to split.
 * @param string $charset Charset to use.
 *
 * @return array|false
 */
function mb_str_split($string, $charset = 'UTF8') {
	if (is_callable('mb_substr')) {
		$length = mb_strlen($string);
		$array = array();

		while ($length) {
			$array[] = mb_substr($string, 0, 1, $charset);
			$string = mb_substr($string, 1, $length, $charset);

			$length = mb_strlen($string);
		}

		return $array;
	} else {
		return str_split($string);
	}

	return false;
}


/**
 * 1.6 style file matrix
 *
 * @param string $filename The filename
 *
 * @return string
 */
function file_matrix_1_6($filename) {
	$invalid_fs_chars = '*\'\\/"!$%^&*.%(){}[]#~?<>;|¬`@-+=';

	$matrix = "";

	$name = $filename;
	$filename = mb_str_split($filename);
	if (!$filename) {
		return false;
	}

	$len = count($filename);
	if ($len > 5) {
		$len = 5;
	}

	for ($n = 0; $n < $len; $n++) {

		// Prevent a matrix being formed with unsafe characters
		$char = $filename[$n];
		if (strpos($invalid_fs_chars, $char) !== false) {
			$char = '_';
		}

		$matrix .= $char . "/";
	}

	return $matrix . $name . "/";
}


/**
 * Scans a directory and moves any files from $from to $to
 * preserving structure and handling existing paths.
 * Will no overwrite files in $to.
 *
 * TRAILING SLASHES REQUIRED.
 *
 * @param string $from       From dir.
 * @param string $to         To dir.
 * @param bool   $move       True to move, false to copy.
 * @param string $preference to|from If file collisions, which dir has preference.
 *
 * @return bool
 */
function merge_directories($from, $to, $move = false, $preference = 'to') {
	if (!$entries = scandir($from)) {
		return false;
	}

	// character filtering needs to be elsewhere.
	if (!is_dir($to)) {
		mkdir($to, 0700, true);
	}

	if ($move === true) {
		$f = 'rename';
	} else {
		$f = 'copy';
	}

	foreach ($entries as $entry) {
		if ($entry == '.' || $entry == '..') {
			continue;
		}

		$from_path = $from . $entry;
		$to_path = $to . $entry;

		// check to see if the path exists and is a dir, if so, recurse.
		if (is_dir($from_path) && is_dir($to_path)) {
			$from_path .= '/';
			$to_path .= '/';
			merge_directories($from_path, $to_path, $move, $preference);

			// since it's a dir that already exists we don't need to move it
			continue;
		}

		// only move if target doesn't exist or if preference is for the from dir
		if (!file_exists($to_path) || $preference == 'from') {

			if ($f($from_path, $to_path)) {
				//elgg_dump("Moved/Copied $from_path to $to_path");
			}
		} else {
			//elgg_dump("Ignoring $from_path -> $to_path");
		}
	}
}

/**
 * Create a 1.7 style user file matrix based upon date.
 *
 * @param int $guid Guid of owner
 *
 * @return string File matrix path
 */
function user_file_matrix($guid) {
	// lookup the entity
	$user = get_entity($guid);
	if ($user->type != 'user') {
		// only to be used for user directories
		return FALSE;
	}

	$time_created = date('Y/m/d', $user->time_created);
	return "$time_created/$user->guid/";
}

global $DB_QUERY_CACHE, $DB_PROFILE, $ENTITY_CACHE;
/**
 * Upgrade file locations
 */
$users = mysql_query("SELECT guid, username
	FROM {$CONFIG->dbprefix}users_entity WHERE username != ''");
while ($user = mysql_fetch_object($users)) {
	$DB_QUERY_CACHE = $DB_PROFILE = $ENTITY_CACHE = array();

	$to = $CONFIG->dataroot . user_file_matrix($user->guid);
	foreach (array('1_0', '1_1', '1_6') as $version) {
		$function = "file_matrix_$version";
		$from = $CONFIG->dataroot . $function($user->username);
		merge_directories($from, $to, $move = TRUE, $preference = 'from');
	}
}
