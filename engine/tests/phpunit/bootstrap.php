<?php
$engine = dirname(dirname(dirname(__FILE__)));

date_default_timezone_set('America/Los_Angeles');

error_reporting(E_ALL | E_STRICT);

/**
 * This is here as a temporary solution only. Instead of adding more global
 * state to this file as we migrate tests, try to refactor the code to be
 * testable without global state.
 */
global $CONFIG;
$CONFIG = (object) array(
	'dbprefix' => 'elgg_',
	'boot_complete' => false,
);

// @todo remove once views service and menu tests no longer need it
function elgg_get_site_url() {
	return 'http://localhost/';
}

// Set up class auto-loading
require_once "$engine/lib/autoloader.php";

// Provide some basic global functions/initialization.
require_once "$engine/lib/elgglib.php";

// This is required by ElggEntity
require_once "$engine/lib/sessions.php";
