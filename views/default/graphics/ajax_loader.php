<?php
/**
 * Elgg AJAX loader
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['id']
 */

if (isset($vars['internalid'])) {
	$id = "id=\"{$vars['internalid']}\"";
}

$loader = <<< END

<div align="center" class="ajax-loader hidden" $id></div>

END;

$loader = str_replace("\n","",$loader);
$loader = str_replace("\r","",$loader);

if (isset($vars['slashes']) && $vars['slashes'] == true) {
	$loader = addslashes($loader);
}

echo $loader;