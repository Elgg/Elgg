<?php
/**
 * Elgg engine bootstrapper
 * Loads the various elements of the Elgg engine
 *
 * @package Elgg
 * @subpackage Core
 */

/*
 * Basic profiling
 */
global $START_MICROTIME;
$START_MICROTIME = microtime(true);

/*
 * Create global CONFIG object
 */
global $CONFIG;
if (!isset($CONFIG)) {
	$CONFIG = new stdClass;
}

$lib_dir = dirname(__FILE__) . '/lib/';

// bootstrapping with required files in a required order
$required_files = array(
	'exceptions.php', 'elgglib.php', 'views.php', 'access.php', 'system_log.php', 'export.php',
	'sessions.php', 'languages.php', 'input.php', 'install.php', 'cache.php', 'output.php'
);

foreach ($required_files as $file) {
	$path = $lib_dir . $file;
	if (!include($path)) {
		echo "Could not load file '$path'. "
		. 'Please check your Elgg installation for all required files.';
		exit;
	}
}

// Use fallback view until sanitised
$oldview = get_input('view', 'default');
set_input('view', 'failsafe');

// Register the error handler
set_error_handler('__elgg_php_error_handler');
set_exception_handler('__elgg_php_exception_handler');

// attempt to save settings.php and .htaccess if in installation.
if ($sanitised = sanitised()) {
	/**
	 * Load the system settings
	 */
	if (!include_once(dirname(__FILE__) . "/settings.php")) {
		throw new InstallationException("Elgg could not load the settings file.");
	}

	// Get config
	global $CONFIG;

	// load the rest of the library files from engine/lib/
	$lib_files = array(
		// these want to be loaded first apparently?
		'database.php', 'actions.php',

		'admin.php', 'annotations.php', 'api.php', 'cache.php',
		'calendar.php', 'configuration.php', 'cron.php', 'entities.php',
		'export.php', 'extender.php', 'filestore.php', 'group.php',
		'input.php', 'install.php', 'location.php', 'mb_wrapper.php',
		'memcache.php', 'metadata.php', 'metastrings.php', 'notification.php',
		'objects.php', 'opendd.php', 'pagehandler.php',
		'pageowner.php', 'pam.php', 'plugins.php', 'query.php',
		'relationships.php', 'river.php', 'sites.php', 'social.php',
		'statistics.php', 'system_log.php', 'tags.php', 'usersettings.php',
		'users.php', 'version.php', 'widgets.php', 'xml.php', 'xml-rpc.php'
	);

	foreach($lib_files as $file) {
		$file = $lib_dir . $file;
		elgg_log("Loading $file...");
		if (!include_once($file)) {
			throw new InstallationException("Could not load {$file}");
		}
	}
} else {
	throw new InstallationException(elgg_echo('installation:error:configuration'));
}

// Autodetect some default configuration settings
set_default_config();

// Trigger events
trigger_elgg_event('boot', 'system');

// Check if installed
$installed = is_installed();
$db_installed = is_db_installed();

// Forward if we haven't been installed
if ((!$installed || !$db_installed)
	&& !substr_count($_SERVER["PHP_SELF"], "install.php")
	&& !substr_count($_SERVER["PHP_SELF"], "css.php")
	&& !substr_count($_SERVER["PHP_SELF"], "action_handler.php")) {

		header("Location: install.php");
		exit;
}

// Load plugins
if (($installed) && ($db_installed) && ($sanitised)) {
	load_plugins();

	trigger_elgg_event('plugins_boot', 'system');
}

// Trigger system init event for plugins
if (!substr_count($_SERVER["PHP_SELF"], "install.php")
	&& !substr_count($_SERVER["PHP_SELF"], "setup.php")) {

	trigger_elgg_event('init', 'system');
}

// System booted, return to normal view
if (!elgg_is_valid_view_type($oldview)) {
	if (empty($CONFIG->view)) {
		$oldview = 'default';
	} else {
		$oldview = $CONFIG->view;
	}
}
set_input('view', $oldview);

// Regenerate the simple cache if expired.
// Don't do it on upgrade, because upgrade does it itself.
if (($installed) && ($db_installed) && !(defined('upgrading') && upgrading == 'upgrading')) {
	$lastupdate = datalist_get("simplecache_lastupdate_$oldview");
	$lastcached = datalist_get("simplecache_lastcached_$oldview");
	if ($lastupdate == 0 || $lastcached < $lastupdate) {
		elgg_view_regenerate_simplecache($oldview);
		$lastcached = datalist_get("simplecache_lastcached_$oldview");
	}
	// needs to be set for links in html head
	$CONFIG->lastcache = $lastcached;
}
