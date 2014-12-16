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
 * @global \stdClass $CONFIG
 */
global $CONFIG;
if (!isset($CONFIG)) {
	$CONFIG = new \stdClass;
}
$CONFIG->boot_complete = false;

$engine_dir = dirname(__FILE__);

// No settings means a fresh install
if (!is_file("$engine_dir/settings.php")) {
	header("Location: install.php");
	exit;
}

if (!is_readable("$engine_dir/settings.php")) {
	echo "The Elgg settings file exists but the web server doesn't have read permission to it.";
	exit;
}

require_once "$engine_dir/settings.php";

// This will be overridden by the DB value but may be needed before the upgrade script can be run.
$CONFIG->default_limit = 10;

require_once "$engine_dir/load.php";

// Connect to database, load language files, load configuration, init session
// Plugins can't use this event because they haven't been loaded yet.
elgg_trigger_event('boot', 'system');

// Load the plugins that are active
_elgg_load_plugins();

// @todo move loading plugins into a single boot function that replaces 'boot', 'system' event
// and then move this code in there.
// This validates the view type - first opportunity to do it is after plugins load.
$viewtype = elgg_get_viewtype();
if (!elgg_is_registered_viewtype($viewtype)) {
	elgg_set_viewtype('default');
}

// @todo deprecate as plugins can use 'init', 'system' event
elgg_trigger_event('plugins_boot', 'system');

// Complete the boot process for both engine and plugins
elgg_trigger_event('init', 'system');

$CONFIG->boot_complete = true;

// System loaded and ready
elgg_trigger_event('ready', 'system');
