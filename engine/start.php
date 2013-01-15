<?php
/**
 * Bootstraps the Elgg engine.
 *
 * This file loads the full Elgg engine, checks the installation
 * state, and triggers a series of events to finish booting Elgg:
 * 	- {@elgg_event boot system}
 * 	- {@elgg_event init system}
 * 	- {@elgg_event ready system}
 *
 * If Elgg is fully uninstalled, the browser will be redirected to an
 * installation page.
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
 * for running Elgg as defined in the settings.php file.
 *
 * Plugin authors are encouraged to use elgg_get_config() instead of accessing
 * the global directly.
 *
 * @see elgg_get_config()
 * @see engine/settings.php
 * @global stdClass $CONFIG
 */
global $CONFIG;
if (!isset($CONFIG)) {
	$CONFIG = new stdClass;
}
$CONFIG->boot_complete = false;

$engine_dir = dirname(__FILE__);

/*
 * No settings means a fresh install
 */
if (!include_once("$engine_dir/settings.php")) {
	header("Location: install.php");
	exit;
}

$lib_dir = "$engine_dir/lib";

require_once("$lib_dir/autoloader.php");
require_once("$lib_dir/elgglib.php");

// These have to be registered here instead of in autoloader.php since the
// functions are defined in elgglib.php.
elgg_register_event_handler('shutdown', 'system', '_elgg_save_autoload_cache', 1000);
elgg_register_event_handler('ugprade', 'all', '_elgg_delete_autoload_cache');

// load the rest of the library files from engine/lib/
// All on separate lines to make diffs easy to read + make it apparent how much
// we're actually loading on every page (Hint: it's too much).
$lib_files = array(
	'access.php',
	'actions.php',
	'admin.php',
	'annotations.php',
	'cache.php',
	'configuration.php',
	'cron.php',
	'database.php',
	'entities.php',
	'export.php',
	'extender.php',
	'filestore.php',
	'group.php',
	'input.php',
	'languages.php',
	'location.php',
	'mb_wrapper.php',
	'memcache.php',
	'metadata.php',
	'metastrings.php',
	'navigation.php',
	'notification.php',
	'objects.php',
	'opendd.php',
	'output.php',
	'pagehandler.php',
	'pageowner.php',
	'pam.php',
	'plugins.php',
	'private_settings.php',
	'relationships.php',
	'river.php',
	'sessions.php',
	'sites.php',
	'statistics.php',
	'system_log.php',
	'tags.php',
	'user_settings.php',
	'users.php',
	'upgrade.php',
	'views.php',
	'web_services.php',
	'widgets.php',
	'xml.php',
	
	// backward compatibility
	'deprecated-1.7.php',
	'deprecated-1.8.php',
	'deprecated-1.9.php',
);

foreach ($lib_files as $file) {
	require_once("$lib_dir/$file");
}

// Connect to database, load language files, load configuration, init session
// Plugins can't use this event because they haven't been loaded yet.
elgg_trigger_event('boot', 'system');

// Load the plugins that are active
elgg_load_plugins();

// @todo move loading plugins into a single boot function that replaces 'boot', 'system' event
// and then move this code in there.
// This validates the view type - first opportunity to do it is after plugins load.
$view_type = elgg_get_viewtype();
if (!elgg_is_valid_view_type($view_type)) {
	elgg_set_viewtype('default');
}

// @todo deprecate as plugins can use 'init', 'system' event
elgg_trigger_event('plugins_boot', 'system');

// Complete the boot process for both engine and plugins
elgg_trigger_event('init', 'system');

$CONFIG->boot_complete = true;

// System loaded and ready
elgg_trigger_event('ready', 'system');
