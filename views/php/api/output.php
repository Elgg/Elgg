<?php
/**
 * Elgg PHP output
 * This outputs the api as PHP
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 */

$result = $vars['result'];
$export = $result->export();

echo serialize($export);