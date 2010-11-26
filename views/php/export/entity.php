<?php
/**
 * Elgg Entity export.
 * Displays an entity as PHP serialised data
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

echo serialize($export);
