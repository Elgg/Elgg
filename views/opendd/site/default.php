<?php

/**
 * Elgg default object view
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

$serialised = exportAsArray($vars['entity']->guid);
foreach ($serialised as $s) {
	echo $s;
}