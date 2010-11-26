<?php
/**
 * Elgg default layout
 *
 * @package Elgg
 * @subpackage Core
 */

for ($i = 1; $i < 8; $i++) {
	if (isset($vars["area{$i}"])) {
		echo $vars["area{$i}"];
	}
}