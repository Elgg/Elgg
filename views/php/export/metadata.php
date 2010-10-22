<?php
/**
 * Elgg metadata export.
 * Displays a metadata item using PHP serialised data
 *
 * @package Elgg
 * @subpackage Core
 */

$m = $vars['metadata'];

$export = new stdClass;
$exportable_values = $m->getExportableValues();

foreach ($exportable_values as $v) {
	$export->$v = $m->$v;
}

echo serialize($export);