<?php
/**
 * Elgg exception
 * Displays a single exception
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['object'] An exception
 */

$export = $vars['object'];

global $jsonexport;
$jsonexport['exceptions'][] = $export;