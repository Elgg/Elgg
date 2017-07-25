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
$version = 2017041200;

$composerJson = file_get_contents(dirname(__FILE__) . "/composer.json");
if ($composerJson === false) {
	throw new Exception("Unable to read composer.json file!");
}

$composer = json_decode($composerJson);
if ($composer === null) {
	throw new Exception("JSON parse error while reading composer.json!");
}

// Human-friendly version name
if (!isset($composer->version)) {
	throw new Exception("Version field must be set in composer.json!");
}
$release = $composer->version;
