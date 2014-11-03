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
	'boot_complete' => false,
	'dataroot' => __DIR__ . "/test_files/",
	'dbprefix' => 'elgg_',
	'site_guid' => 1,
	'wwwroot' => 'http://localhost/',
);

require_once "$engine/load.php";
