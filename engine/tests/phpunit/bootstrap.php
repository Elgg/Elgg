<?php
$engine = dirname(dirname(dirname(__FILE__)));

date_default_timezone_set('America/Los_Angeles');

error_reporting(E_STRICT);

/**
 * This is here as a temporary solution only. Instead of adding more global
 * state to this file as we migrate tests, try to refactor the code to be
 * testable without global state.
 */
global $CONFIG;
$CONFIG = (object) array(
	'site_guid' => 0,
	'dbprefix' => 'elgg_',
	'boot_complete' => false,
	'wwwroot' => 'http://localhost/',
	'cookies' => array(
		'session' => session_get_cookie_params(),
	),
);

$CONFIG->cookies['session']['name'] = 'Elgg';

// Provide some basic global functions/initialization.
require_once "$engine/lib/autoloader.php";
require_once "$engine/lib/elgglib.php";
require_once "$engine/lib/configuration.php";
require_once "$engine/lib/sessions.php";
require_once "$engine/lib/users.php";
