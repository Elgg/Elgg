<?php
/**
 * Bootstraps and starts the Elgg engine.
 *
 * This file loads the full Elgg engine, checks the installation
 * state, then emits a series of events to finish booting Elgg:
 * 	- {@elgg_event boot system}
 * 	- {@elgg_event plugins_boot system}
 * 	- {@elgg_event init system}
 *
 * If Elgg is uninstalled, the browser will be redirected to an
 * installation page.
 *
 * If in an installation, attempts to save the .htaccess and
 * settings.php files during {@link sanitised()}
 *
 * @warning The view type is set to 'failsafe' during boot.  This means calling
 * {@link elgg_get_viewtype()} will return 'failsafe' in any function called by
 * the events listed above regardless of actual view.  The original view is restored
 * after booting.
 *
 * @see install.php
 * @package Elgg.Core
 * @subpackage Core
 */

/**
 * The time with microseconds when the Elgg engine was started.
 *
 * @global float
 */
global $START_MICROTIME;
$START_MICROTIME = microtime(true);

/**
 * Configuration values.
 *
 * The $CONFIG global contains configuration values required
 * for running Elgg as defined in the settings.php file.  The following
 * array keys are defined by core Elgg:
 *
 * Plugin authors are encouraged to use get_config() instead of accessing the
 * global directly.
 *
 * @see get_config()
 * @see engine/settings.php
 * @global stdClass $CONFIG
 */
global $CONFIG;
if (!isset($CONFIG)) {
	$CONFIG = new stdClass;
}

$lib_dir = dirname(__FILE__) . '/lib/';

/**
 * The minimum required libs to bootstrap an Elgg installation.
 *
 * @var array
 */
$required_files = array(
	'exceptions.php', 'elgglib.php', 'views.php', 'access.php', 'system_log.php', 'export.php',
	'sessions.php', 'languages.php', 'input.php', 'install.php', 'cache.php', 'output.php'
);

// include bootstraping libs
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
		// these need to be loaded first.
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

// Trigger boot events for core. Plugins can't hook
// into this because they haven't been loaded yet.
trigger_elgg_event('boot', 'system');

// Check if installed
$installed = is_installed();
$db_installed = is_db_installed();

/**
 * Forward if Elgg is not installed.
 */
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
	}
	// needs to be set for links in html head
	$CONFIG->lastcache = $lastcached;
}