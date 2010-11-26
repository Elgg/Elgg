<?php
/**
 * Elgg JS pageshell
 *
 * @package Elgg
 * @subpackage Core
 */

$body = $vars['body'];

// Remove excess carriage returns
$body = str_replace("\r",'',$body);
$body = explode("\n",$body);

foreach($body as $line) {
	echo "document.write('" . addslashes($line) . "');\n";
}