<?php

/**
 * Elgg default object view
 *
 * @package Elgg
 * @subpackage Core
 */

$serialised = exportAsArray($vars['entity']->guid);
foreach ($serialised as $s) {
	echo $s;
}