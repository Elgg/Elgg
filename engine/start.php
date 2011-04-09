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
 * If Elgg is fully uninstalled, the browser will be redirected to an
 * installation page.
 *
 * @see install.php
 * @package Elgg.Core
 * @subpackage Core
 */

/*
 * No settings means a fresh install
 */
if (!file_exists(dirname(__FILE__) . '/settings.php')) {
	header("Location: install.php");
	exit;
}

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
	'elgglib.php', 'views.php', 'access.php', 'system_log.php', 'export.php',
	'sessions.php', 'languages.php', 'input.php', 'cache.php', 'output.php'
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

// Register the error handler
set_error_handler('_elgg_php_error_handler');
set_exception_handler('_elgg_php_exception_handler');

/**
 * Load the system settings
 */
if (!include_once(dirname(__FILE__) . "/settings.php")) {
	$msg = elgg_echo('InstallationException:CannotLoadSettings');
	throw new InstallationException($msg);
}


// load the rest of the library files from engine/lib/
$lib_files = array(
	// these need to be loaded first.
	'database.php', 'actions.php',

	'admin.php', 'annotations.php', 'calendar.php',
	'configuration.php', 'cron.php', 'entities.php', 'export.php',
	'extender.php', 'filestore.php', 'group.php', 
	'location.php', 'mb_wrapper.php', 'memcache.php', 'metadata.php',
	'metastrings.php', 'navigation.php', 'notification.php', 'objects.php',
	'opendd.php', 'pagehandler.php', 'pageowner.php', 'pam.php', 'plugins.php',
	'private_settings.php', 'relationships.php', 'river.php', 'sites.php',
	'statistics.php', 'tags.php', 'user_settings.php', 'users.php',
	'upgrade.php', 'web_services.php', 'widgets.php', 'xml.php', 'xml-rpc.php',
	
	//backwards compatibility
	'deprecated-1.7.php', 'deprecated-1.8.php',
);

foreach ($lib_files as $file) {
	$file = $lib_dir . $file;
	elgg_log("Loading $file...");
	if (!include_once($file)) {
		$msg = sprintf(elgg_echo('InstallationException:MissingLibrary'), $file);
		throw new InstallationException($msg);
	}
}

// confirm that the installation completed successfully
verify_installation();

// Autodetect some default configuration settings
set_default_config();

// needs to be set for links in html head
$viewtype = get_input('view', 'default');
$lastcached = datalist_get("simplecache_lastcached_$viewtype");
$CONFIG->lastcache = $lastcached;

// Trigger boot events for core. Plugins can't hook
// into this because they haven't been loaded yet.
elgg_trigger_event('boot', 'system');

// Load the plugins that are active
elgg_load_plugins();
elgg_trigger_event('plugins_boot', 'system');

// Trigger system init event for plugins
elgg_trigger_event('init', 'system');

// Regenerate the simple cache if expired.
// Don't do it on upgrade because upgrade does it itself.
// @todo - move into function and perhaps run off init system event
if (!defined('UPGRADING')) {
	$lastupdate = datalist_get("simplecache_lastupdate_$viewtype");
	$lastcached = datalist_get("simplecache_lastcached_$viewtype");
	if ($lastupdate == 0 || $lastcached < $lastupdate) {
		elgg_regenerate_simplecache($viewtype);
	}
}

// System loaded and ready
elgg_trigger_event('ready', 'system');
