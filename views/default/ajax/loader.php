<?php
/**
 * Elgg AJAX loader
 *
 * @package Elgg
 * @subpackage Core
 */

$loader = <<< END

<div align="center" class="ajax_loader"></div>

END;

$loader = str_replace("\n","",$loader);
$loader = str_replace("\r","",$loader);

if (isset($vars['slashes']) && $vars['slashes'] == true) {
	$loader = addslashes($loader);
}

echo $loader;