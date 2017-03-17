<?php
/**
 * Elgg JSON output
 * This outputs the api results as JSON
 *
 * @package Elgg
 * @subpackage Core
 */

$result = $vars['result'];
$export = $result->export();
echo json_encode($export);
