<?php
/**
 * Elgg relationship export.
 * Displays a relationship using ODD.
 *
 * @package Elgg
 * @subpackage Core
 */

$r = $vars['relationship'];

//$odd = new ODDDocument();
//$odd->addElement($r->export());

//echo $odd;

echo $r->export();
