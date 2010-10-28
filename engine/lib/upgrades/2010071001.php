<?php
/**
 *	Change profile image names to use guid rather than username
 */

/**
 * Need the same function to generate a user matrix, but can't call it
 * the same thing as the previous update.
 *
 * @param int $guid User guid.
 *
 * @return string File matrix
 */
function user_file_matrix_2010071001($guid) {
	// lookup the entity
	$user = get_entity($guid);
	if ($user->type != 'user') {
		// only to be used for user directories
		return FALSE;
	}

	if (!$user->time_created) {
		// no idea where this user has its files
		return FALSE;
	}

	$time_created = date('Y/m/d', $user->time_created);
	return "$time_created/$user->guid/";
}

$sizes = array('large', 'medium', 'small', 'tiny', 'master', 'topbar');

global $DB_QUERY_CACHE, $DB_PROFILE, $ENTITY_CACHE, $CONFIG;
$users = mysql_query("SELECT guid, username FROM {$CONFIG->dbprefix}users_entity
	WHERE username != ''");
while ($user = mysql_fetch_object($users)) {
	$DB_QUERY_CACHE = $DB_PROFILE = $ENTITY_CACHE = array();

	$user_directory = user_file_matrix_2010071001($user->guid);
	if (!$user_directory) {
		continue;
	}
	$profile_directory = $CONFIG->dataroot . $user_directory . "profile/";
	if (!file_exists($profile_directory)) {
		continue;
	}

	foreach ($sizes as $size) {
		$old_filename = "$profile_directory{$user->username}{$size}.jpg";
		$new_filename = "$profile_directory{$user->guid}{$size}.jpg";
		if (file_exists($old_filename)) {
			if (!rename($old_filename, $new_filename)) {
				error_log("Failed to rename profile photo for $user->username");
			}
		}
	}
}
