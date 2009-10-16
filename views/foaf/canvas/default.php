<?php
/**
 * Elgg default layout
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

for ($i = 1; $i < 8; $i++) {
	if (isset($vars["area{$i}"])) {
		echo $vars["area{$i}"];
	}
}