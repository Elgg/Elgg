<?php
$engine = dirname(dirname(__DIR__));

/**
 * This is here as a temporary solution only. Instead of adding more global
 * state to this file as we migrate tests, try to refactor the code to be
 * testable without global state.
 */
global $CONFIG;
$CONFIG = new stdClass;

// Set up class auto-loading
require_once "$engine/lib/elgglib.php";
require_once "$engine/lib/autoloader.php";