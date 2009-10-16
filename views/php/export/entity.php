<?php
/**
 * Elgg Entity export.
 * Displays an entity as PHP serialised data
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

$entity = $vars['entity'];

$export = new stdClass;
$exportable_values = $entity->getExportableValues();

foreach ($exportable_values as $v) {
	$export->$v = $entity->$v;
}

echo serialize($export);
