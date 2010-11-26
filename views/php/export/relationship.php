<?php
/**
 * Elgg relationship export.
 * Displays a relationship using PHP serialised data
 *
 * @package Elgg
 * @subpackage Core
 */

$r = $vars['relationship'];

$export = new stdClass;
$exportable_values = $r->getExportableValues();

foreach ($exportable_values as $v) {
	$export->$v = $r->$v;
}

echo serialize($export);