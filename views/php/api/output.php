<?php
/**
 * Elgg PHP output
 * This outputs the api as PHP
 *
 * @package Elgg
 * @subpackage Core
 *
 */

$result = $vars['result'];
$export = $result->export();

echo serialize($export);