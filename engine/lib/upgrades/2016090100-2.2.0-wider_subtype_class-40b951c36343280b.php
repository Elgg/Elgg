<?php
/**
 * Elgg 2.2.0 upgrade 2016090100
 * wider_subtype_class
 *
 * Widen entity_subtypes.class to 255 chars.
 */

$db = _elgg_services()->db;
$db->updateData("
	ALTER TABLE {$db->prefix}entity_subtypes
	MODIFY `class` VARCHAR(255) NOT NULL DEFAULT '';
");
