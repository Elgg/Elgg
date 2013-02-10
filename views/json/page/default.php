<?php
/**
 * Elgg JSON output pageshell
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['body']
 */

header("Content-Type: application/json");

echo $vars['body'];
