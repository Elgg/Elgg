<?php
/**
 * Elgg metadata export.
 * Displays a metadata item using json
 *
 * @package Elgg
 * @subpackage Core
 */

$m = $vars['metadata'];

$export = new stdClass;
$exportable_values = $entity->getExportableValues();

foreach ($exportable_values as $v) {
	$export->$v = $m->$v;
}

global $jsonexport;
$jsonexport['metadata'][] = $entity;
// echo json_encode($export);