<?php
/**
 * Elgg metadata export.
 * Displays a metadata item using the current view.
 *
 * @package Elgg
 * @subpackage Core
 */

$m = $vars['metadata'];
$uuid = $vars['uuid'];

//$odd = new ODDDocument();
//$odd->addElement($m->export());

//echo $odd;

echo $m->export();