<?php

/**
 * Elgg installation
 * Various functions to assist with installing and upgrading the system
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

/**
 * Check that the installed version of PHP meets the minimum requirements (currently 5.2 or greater).
 *
 * @return bool
 */
function php_check_version() {
	if (version_compare(phpversion(), '5.1.2', '>=')) {
		return true;
	}

	return false;
}

/**
 * Validate the platform Elgg is being installed on.
 *
 * @throws ConfigurationException if the validation fails.
 * @return bool
 */
function validate_platform() {
	// Get database version
	if (!db_check_version()) {
		throw new ConfigurationException(elgg_echo('ConfigurationException:BadDatabaseVersion'));
	}

	// Now check PHP
	if (!php_check_version()) {
		throw new ConfigurationException(elgg_echo('ConfigurationException:BadPHPVersion'));
	}

	// TODO: Consider checking for installed modules etc
	return true;
}

/**
 * Confirm the settings for the database
 *
 * @param string $user
 * @param string $password
 * @param string $dbname
 * @param string $host
 * @return bool
 */
function db_check_settings($user, $password, $dbname, $host) {
	$mysql_dblink = mysql_connect($host, $user, $password, true);
	if ($mysql_dblink == FALSE) {
		return $FALSE;
	}

	$result = mysql_select_db($dbname, $mysql_dblink);

	mysql_close($mysql_dblink);
	
	return $result;
}


/**
 * Returns whether or not the database has been installed
 *
 * @return true|false Whether the database has been installed
 */
function is_db_installed() {
	global $CONFIG;

	if (isset($CONFIG->db_installed)) {
		return $CONFIG->db_installed;
	}

	if ($dblink = get_db_link('read')) {
		mysql_query("select name from {$CONFIG->dbprefix}datalists limit 1", $dblink);
		if (mysql_errno($dblink) > 0) {
			return false;
		}
	} else {
		return false;
	}

	// Set flag if db is installed (if false then we want to check every time)
	$CONFIG->db_installed = true;

	return true;
}

/**
 * Returns whether or not other settings have been set
 *
 * @return true|false Whether or not the rest of the installation has been followed through with
 */
function is_installed() {
	global $CONFIG;
	return datalist_get('installed');
}

/**
 * Copy and create a new settings.php from settings.example.php, substituting the variables in
 * $vars where appropriate.
 *
 * $vars is an associate array of $key => $value, where $key is the variable text you wish to substitute (eg
 * CONFIG_DBNAME will replace {{CONFIG_DBNAME}} in the settings file.
 *
 * @param array $vars The array of vars
 * @param string $in_file Optional input file (if not settings.example.php)
 * @return string The file containing substitutions.
 */
function create_settings(array $vars, $in_file="engine/settings.example.php") {
	$file = file_get_contents($in_file);

	if (!$file) {
		return false;
	}

	foreach ($vars as $k => $v) {
		$file = str_replace("{{".$k."}}", $v, $file);
	}

	return $file;
}

/**
 * Initialisation for installation functions
 *
 */
function install_init() {
	register_action("systemsettings/install",true);
}

register_elgg_event_handler("boot","system","install_init");