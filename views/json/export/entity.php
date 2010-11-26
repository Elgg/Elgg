<?php
/**
 * Elgg Entity export.
 * Displays an entity as JSON
 *
 * @package Elgg
 * @subpackage Core
 */

$entity = $vars['entity'];

$export = new stdClass;
$exportable_values = $entity->getExportableValues();

foreach ($exportable_values as $v) {
	$export->$v = $entity->$v;
}

$export->url = $entity->getURL();

global $jsonexport;
$jsonexport[$entity->getType()][$entity->getSubtype()][] = $export;