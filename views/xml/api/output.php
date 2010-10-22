<?php
/**
 * Elgg XML output
 * This outputs the api as XML
 *
 * @package Elgg
 * @subpackage Core
 *
 */

$result = $vars['result'];
$export = $result->export();

echo serialise_object_to_xml($export, "elgg");