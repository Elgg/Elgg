<?php

/**
 * Conditional upgrade for UTF8 as described in http://trac.elgg.org/ticket/1928
 */

// get_version() returns the code version.
// we want the DB version.
$dbversion = (int) datalist_get('version');

// 2009100701 was the utf8 upgrade for 1.7.
// if we've already upgraded, don't try again.
if ($dbversion < 2009100701) {
	// if the default client connection is utf8 there is no reason
	// to run this upgrade because the strings are already stored correctly.

	// start a new link to the DB to see what its defaults are.
	$link = mysql_connect($CONFIG->dbhost, $CONFIG->dbuser, $CONFIG->dbpass, TRUE);
	mysql_select_db($CONFIG->dbname, $link);

	$q = "SHOW VARIABLES LIKE 'character_set_client'";
	$r = mysql_query($q);
	$client = mysql_fetch_assoc($r);

	$q = "SHOW VARIABLES LIKE 'character_set_connection'";
	$r = mysql_query($q);
	$connection = mysql_fetch_assoc($r);

	// only run upgrade if not already talking utf8.
	if ($client['Value'] != 'utf8' && $connection['Value'] != 'utf8') {
		$qs = array();
		$qs[] = "SET NAMES utf8";

		$qs[] = "ALTER TABLE {$CONFIG->dbprefix}metastrings DISABLE KEYS";
		$qs[] = "REPLACE INTO {$CONFIG->dbprefix}metastrings (id, string)
			SELECT id, unhex(hex(convert(string using latin1)))
			FROM {$CONFIG->dbprefix}metastrings";
		$qs[] = "ALTER TABLE {$CONFIG->dbprefix}metastrings ENABLE KEYS";

		$qs[] = "ALTER TABLE {$CONFIG->dbprefix}groups_entity DISABLE KEYS";
		$qs[] = "REPLACE INTO {$CONFIG->dbprefix}groups_entity (guid, name, description)
			SELECT guid, unhex(hex(convert(name using latin1))),
				unhex(hex(convert(description using latin1)))
			FROM {$CONFIG->dbprefix}groups_entity";
		$qs[] = "ALTER TABLE {$CONFIG->dbprefix}groups_entity ENABLE KEYS";

		$qs[] = "ALTER TABLE {$CONFIG->dbprefix}objects_entity DISABLE KEYS";
		$qs[] = "REPLACE INTO {$CONFIG->dbprefix}objects_entity (guid, title, description)
			SELECT guid, unhex(hex(convert(title using latin1))),
				unhex(hex(convert(description using latin1)))
			FROM {$CONFIG->dbprefix}objects_entity";
		$qs[] = "ALTER TABLE {$CONFIG->dbprefix}objects_entity ENABLE KEYS";

		$qs[] = "ALTER TABLE {$CONFIG->dbprefix}users_entity DISABLE KEYS";
		$qs[] = "REPLACE INTO {$CONFIG->dbprefix}users_entity
			(guid, name, username, password, salt, email, language, code,
			banned, last_action, prev_last_action, last_login, prev_last_login)
				SELECT guid, unhex(hex(convert(name using latin1))),
					username, password, salt, email, language, code,
					banned, last_action, prev_last_action, last_login, prev_last_login
				FROM {$CONFIG->dbprefix}users_entity";
		$qs[] = "ALTER TABLE {$CONFIG->dbprefix}users_entity ENABLE KEYS";

		foreach ($qs as $q) {
			if (!update_data($q)) {
				throw new Exception('Couldn\'t execute upgrade query: ' . $q);
			}
		}
	}
}
