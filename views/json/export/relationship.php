<?php
/**
 * Elgg relationship export.
 * Displays a relationship using JSON.
 *
 * @package Elgg
 * @subpackage Core
 * @deprecated 1.9
 */

$r = $vars['relationship'];

$export = new stdClass;

$exportable_values = $entity->getExportableValues();

foreach ($exportable_values as $v) {
	$export->$v = $r->$v;
}

global $jsonexport;
$jsonexport['relationships'][] = $export;