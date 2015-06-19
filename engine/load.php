<?php
/**
 * This file is used to make all of Elgg's code available without going through
 * the boot process. Useful for internal testing purposes. 
 * 
 * @access private
 */

$lib_dir = __DIR__ . "/lib";

require_once "$lib_dir/autoloader.php";

$autoload_path = dirname(__DIR__) . '/vendor/autoload.php';
$autoload_available = include_once($autoload_path);
if (!$autoload_available) {
	die("Couldn't include '$autoload_path'. Did you run `composer install`?");
}

// set up autoloading and DIC
_elgg_services();

// load the rest of the library files from engine/lib/
// All on separate lines to make diffs easy to read + make it apparent how much
// we're actually loading on every page (Hint: it's too much).
$lib_files = array(
	// Needs to be loaded first to correctly bootstrap
	'elgglib.php',
	
	// The order of these doesn't matter, so keep them alphabetical
	'access.php',
	'actions.php',
	'admin.php',
	'annotations.php',
	'cache.php',
	'comments.php',
	'configuration.php',
	'cron.php',
	'database.php',
	'entities.php',
	'extender.php',
	'filestore.php',
	'friends.php',
	'group.php',
	'input.php',
	'languages.php',
	'mb_wrapper.php',
	'memcache.php',
	'metadata.php',
	'metastrings.php',
	'navigation.php',
	'notification.php',
	'objects.php',
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
	'widgets.php',

	// backward compatibility
	'deprecated-1.7.php',
	'deprecated-1.8.php',
	'deprecated-1.9.php',
	'deprecated-1.10.php',
	'deprecated-1.11.php',
	'deprecated-1.12.php',
);

// isolate global scope
call_user_func(function () use ($lib_dir, $lib_files) {

	$setups = array();

	// include library files, capturing setup functions
	foreach ($lib_files as $file) {
		$setup = (require_once "$lib_dir/$file");

		if ($setup instanceof Closure) {
			$setups[$file] = $setup;
		}
	}

	$events = _elgg_services()->events;
	$hooks = _elgg_services()->hooks;

	// run setups
	foreach ($setups as $func) {
		$func($events, $hooks);
	}
});
