<?php
/**
 * Elgg JSON output
 * This outputs the api as JSON
 *
 * @package Elgg
 * @subpackage Core
 *
 */

$result = $vars['result'];
$export = $result->export();

global $jsonexport;

// with api calls, we don't want extra baggage found in other json views
// so we skip the associative array
$jsonexport = $export;