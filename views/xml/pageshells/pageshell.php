<?php
/**
 * Elgg XML output pageshell
 *
 * @package Elgg
 * @subpackage Core
 *
 */

header("Content-Type: text/xml");
// web server will handle setting the content length
//header("Content-Length: " . strlen($vars['body']));
echo "<?xml version='1.0' encoding='UTF-8'?>\n";
echo $vars['body'];