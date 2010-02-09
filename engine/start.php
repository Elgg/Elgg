<?php
/**
 * Elgg engine bootstrapper
 * Loads the various elements of the Elgg engine
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
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

/**
 * Load important prerequisites
 */

if (!include_once(dirname(__FILE__) . "/lib/exceptions.php")) {		// Exceptions
	echo "Error in installation: could not load the Exceptions library.";
	exit;
}

if (!include_once(dirname(__FILE__) . "/lib/elgglib.php")) {		// Main Elgg library
	echo "Elgg could not load its main library.";
	exit;
}

if (!include_once(dirname(__FILE__) . "/lib/access.php")) {		// Access library
	echo "Error in installation: could not load the Access library.";
	exit;
}

if (!include_once(dirname(__FILE__) . "/lib/system_log.php")) {		// Logging library
	echo "Error in installation: could not load the System Log library.";
	exit;
}

if (!include_once(dirname(__FILE__) . "/lib/export.php")) {		// Export library
	echo "Error in installation: could not load the Export library.";
	exit;
}

if (!include_once(dirname(__FILE__) . "/lib/sessions.php")) {
	echo ("Error in installation: Elgg could not load the Sessions library");
	exit;
}

if (!include_once(dirname(__FILE__) . "/lib/languages.php")) {		// Languages library
	echo "Error in installation: could not load the languages library.";
	exit;
}

if (!include_once(dirname(__FILE__) . "/lib/input.php")) {		// Input library
	echo "Error in installation: could not load the input library.";
	exit;
}

if (!include_once(dirname(__FILE__) . "/lib/install.php")) {		// Installation library
	echo "Error in installation: could not load the installation library.";
	exit;
}

if (!include_once(dirname(__FILE__) . "/lib/cache.php")) {		// Installation library
	echo "Error in installation: could not load the cache library.";
	exit;
}



// Use fallback view until sanitised
$oldview = get_input('view');
set_input('view', 'failsafe');

/**
 * Set light mode default
 */
$lightmode = false;

/**
 * Establish handlers
 */

// Register the error handler
set_error_handler('__elgg_php_error_handler');
set_exception_handler('__elgg_php_exception_handler');

/**
 * If there are basic issues with the way the installation is formed, don't bother trying
 * to load any more files
 */
// Begin portion for sanitised installs only
if ($sanitised = sanitised()) {
	/**
	 * Load the system settings
	 */
	if (!include_once(dirname(__FILE__) . "/settings.php")) {
		throw new InstallationException("Elgg could not load the settings file.");
	}

	/**
	 * Load and initialise the database
	 */
	if (!include_once(dirname(__FILE__) . "/lib/database.php")) {
		throw new InstallationException("Elgg could not load the main Elgg database library.");
	}

	if (!include_once(dirname(__FILE__) . "/lib/actions.php")) {
		throw new InstallationException("Elgg could not load the Actions library");
	}

	// Get config
	global $CONFIG;

	// load the rest of the library files from engine/lib/
	$lib_files = array(
		'activity.php', 'admin.php', 'annotations.php', 'api.php',
		'cache.php', 'calendar.php', 'configuration.php', 'cron.php',
		'entities.php', 'export.php', 'extender.php', 'filestore.php',
		'group.php', 'input.php', 'install.php', 'location.php', 'mb_wrapper.php',
		'memcache.php', 'metadata.php', 'metastrings.php', 'notification.php',
		'objects.php', 'opendd.php', 'pagehandler.php', 'pageowner.php', 'pam.php',
		'plugins.php', 'query.php', 'relationships.php', 'river2.php', 'sites.php',
		'social.php', 'statistics.php', 'system_log.php', 'tags.php',
		'usersettings.php', 'users.php', 'version.php', 'widgets.php', 'xml.php',
		'xml-rpc.php'
	);

	$lib_dir = dirname(__FILE__) . '/lib/';

	// Include them
	foreach($lib_files as $file) {
		$file = $lib_dir . $file;
		elgg_log("Loading $file...");
		if (!include_once($file)) {
			throw new InstallationException("Could not load {$file}");
		}
	}
} else {	// End portion for sanitised installs only
	throw new InstallationException(elgg_echo('installation:error:configuration'));
}

// Autodetect some default configuration settings
set_default_config();

// Trigger events
trigger_elgg_event('boot', 'system');

// Load plugins
$installed = is_installed();
$db_installed = is_db_installed();

// Determine light mode
$lm = strtolower(get_input('lightmode'));
if ($lm == 'true') {
	$lightmode = true;
}

// Load plugins, if we're not in light mode
if (($installed) && ($db_installed) && ($sanitised) && (!$lightmode)) {
	load_plugins();

	trigger_elgg_event('plugins_boot', 'system');
}

// Forward if we haven't been installed
if ((!$installed || !$db_installed)
	&& !substr_count($_SERVER["PHP_SELF"], "install.php")
	&& !substr_count($_SERVER["PHP_SELF"],"css.php")
	&& !substr_count($_SERVER["PHP_SELF"],"action_handler.php")) {

		header("Location: install.php");
		exit;
}

// Trigger events
if (!substr_count($_SERVER["PHP_SELF"],"install.php") &&
	!substr_count($_SERVER["PHP_SELF"],"setup.php") &&
	!$lightmode
	&& !(defined('upgrading') && upgrading == 'upgrading')) {


	trigger_elgg_event('init', 'system');
}

// System booted, return to normal view
set_input('view', $oldview);
if (empty($oldview)) {
	if (empty($CONFIG->view)) {
		$oldview = 'default';
	} else {
		$oldview = $CONFIG->view;
	}
}

if (($installed) && ($db_installed)) {
	$lastupdate = datalist_get('simplecache_lastupdate');
	$lastcached = datalist_get('simplecache_'.$oldview);
	if ($lastupdate == 0 || $lastcached < $lastupdate) {
		elgg_view_regenerate_simplecache();
		$lastcached = time();
		datalist_set('simplecache_lastupdate',$lastcached);
		datalist_set('simplecache_'.$oldview,$lastcached);
	}
	$CONFIG->lastcache = $lastcached;
}