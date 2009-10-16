<?php
/**
 * Elgg Entity export.
 * Displays an entity as ODD
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

$entity = $vars['entity'];
$serialised = exportAsArray($vars['entity']->guid);
foreach ($serialised as $s) {
	echo $s;
}