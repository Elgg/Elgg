<?php
/**
 * Elgg version number.
 * This file defines the current version of the core Elgg code being used.
 * This is compared against the values stored in the database to determine
 * whether upgrades should be performed.
 *
 * @package    Elgg
 * @subpackage Core
 */

// YYYYMMDD = Elgg Date
// XX = Interim incrementer
$version = 2014032200;

$composer = json_decode(dirname(__FILE__) . "/composer.json");

// Human-friendly version name
$release = $composer->version;
