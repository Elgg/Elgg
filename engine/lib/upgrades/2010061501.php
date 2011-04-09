<?php
/**
 * utf8 database conversion and file merging for usernames with multibyte chars
 *
 */


// check that we need to do the utf8 conversion
// C&P logic from 2010033101
$dbversion = (int) datalist_get('version');

if ($dbversion < 2009100701) {
	// start a new link to the DB to see what its defaults are.
	$link = mysql_connect($CONFIG->dbhost, $CONFIG->dbuser, $CONFIG->dbpass, TRUE);
	mysql_select_db($CONFIG->dbname, $link);

	$q = "SHOW VARIABLES LIKE 'character_set_client'";
	$r = mysql_query($q);
	$client = mysql_fetch_assoc($r);

	$q = "SHOW VARIABLES LIKE 'character_set_connection'";
	$r = mysql_query($q);
	$connection = mysql_fetch_assoc($r);

	// only run upgrade if not already talking utf8
	if ($client['Value'] != 'utf8' && $connection['Value'] != 'utf8') {
		$qs = array();
		$qs[] = "SET NAMES utf8";

		$qs[] = "ALTER TABLE {$CONFIG->dbprefix}users_entity DISABLE KEYS";
		$qs[] = "REPLACE INTO {$CONFIG->dbprefix}users_entity
			(guid, name, username, password, salt, email, language, code,
			banned, admin, last_action, prev_last_action, last_login, prev_last_login)

			SELECT guid, name, unhex(hex(convert(username using latin1))),
				password, salt, email, language, code,
				banned, admin, last_action, prev_last_action, last_login, prev_last_login
			FROM {$CONFIG->dbprefix}users_entity";

		$qs[] = "ALTER TABLE {$CONFIG->dbprefix}users_entity ENABLE KEYS";

		foreach ($qs as $q) {
			if (!update_data($q)) {
				throw new Exception('Couldn\'t execute upgrade query: ' . $q);
			}
		}

		global $DB_QUERY_CACHE, $DB_PROFILE, $ENTITY_CACHE;

		/**
			Upgrade file locations
		 */
		// new connection to force into utf8 mode to get the old name
		$link = mysql_connect($CONFIG->dbhost, $CONFIG->dbuser, $CONFIG->dbpass, TRUE);
		mysql_select_db($CONFIG->dbname, $link);

		// must be the first command
		mysql_query("SET NAMES utf8");

		$users = mysql_query("SELECT guid, username FROM {$CONFIG->dbprefix}users_entity
			WHERE username != ''", $link);
		while ($user = mysql_fetch_object($users)) {
			$DB_QUERY_CACHE = $DB_PROFILE = $ENTITY_CACHE = array();

			$to = $CONFIG->dataroot . user_file_matrix($user->guid);
			foreach (array('1_0', '1_1', '1_6') as $version) {
				$function = "file_matrix_$version";
				$from = $CONFIG->dataroot . $function($user->username);
				merge_directories($from, $to, $move = TRUE, $preference = 'from');
			}
		}
	}
}
