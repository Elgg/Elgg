<?php
/**
 * Elgg JSON output pageshell
 *
 * @package Elgg
 * @subpackage Core
 *
 */

header("Content-Type: application/json");

global $jsonexport;
echo json_encode($jsonexport);